<?php
/*
 * $RCSfile: ItemDelete.inc,v $
 *
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2006 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * @version $Revision: 1.45 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the deletion of an item
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemDeleteController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$itemId = GalleryUtilities::getRequestVariables('itemId');
	$status = $error = array();

	if (isset($form['action']['delete'])) {
	    $status['deleted']['count'] = 0;
	    if (!empty($form['selectedIds'])) {
		$selectedIds = array_keys($form['selectedIds']);

		/* get the rootId, so we don't try to delete it */
		list ($ret, $rootId) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::studyPermissions($selectedIds);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		foreach ($selectedIds as $selectedId) {
		    /* Make sure we have permission to delete this item */
		    list ($ret, $permissions) = GalleryCoreApi::getPermissions($selectedId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    if ( !isset($permissions['core.delete'])) {
			return array(
			    GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__,
				"Don't have permission to delete this item"), null);
		    }

		    /* Make sure we're not deleting the root album */
		    if ($selectedId == $rootId) {
			return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
							  "Can't delete the root album"), null);
		    }
		}

		/* if we're still here then all are deletable */
		foreach ($selectedIds as $selectedId) {
		    $ret = GalleryCoreApi::deleteEntityById($selectedId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $status['deleted']['count']++;
		}

		list ($ret, $success) = GalleryCoreApi::guaranteeAlbumHasThumbnail($itemId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* What do we do if we weren't successful?  No thumbnail, I guess */
	    }

	    /*
	     * Figure out where to redirect upon success.
	     * Can't use return url because it may be for an item we just deleted, but
	     * check for g2_page param and include that if found (matrix theme specific..)
	     */
	    $results['redirect'] = array('view' => 'core.ShowItem',
					 'itemId' => $itemId);

	    $urlGenerator =& $gallery->getUrlGenerator();
	    list ($ret, $links) = $urlGenerator->getNavigationLinks(1);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (count($links) > 0 &&
		    preg_match('{' . GalleryUtilities::prefixFormVariable('page') . '=(\d+)}',
			       $links[0]['url'], $match)) {
		$results['redirect']['page'] = $match[1];
	    }
	} else if (isset($form['action']['next'])) {
	    $page = GalleryUtilities::getRequestVariables('page');
	    list($ret, $peerIds) =
		GalleryCoreApi::fetchChildItemIdsWithPermission($itemId, 'core.delete');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $numPages = ceil(sizeof($peerIds) / $form['numPerPage']);

	    $results['delegate']['itemId'] = $itemId;
	    $results['delegate']['page'] = min($page + 1, $numPages);
	} else if (isset($form['action']['previous'])) {
	    $page = GalleryUtilities::getRequestVariables('page');
	    $results['delegate']['itemId'] = $itemId;
	    $results['delegate']['page'] = max($page - 1, 1);
	} else if (isset($form['action']['cancel'])) {
	    $results['return'] = 1;
	}

	if (!empty($results['delegate'])) {
	    $results['delegate']['view'] = 'core.ItemAdmin';
	    $results['delegate']['subView'] = 'core.ItemDelete';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will prompt for confirmation on the deletion of an item
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemDeleteView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	list ($itemId, $selectedId, $page, $return) =
		GalleryUtilities::getRequestVariables('itemId', 'selectedId',
						      'page', 'return');

	if ($form['formName'] != 'ItemDelete') {
	    if ($selectedId) {
		$form['selectedIds'][$selectedId] = true;
	    }
	    $form['destination'] = '';
	    $form['formName'] = 'ItemDelete';
	    $form['numPerPage'] = 15;
	}

	list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $peerIds) =
	    GalleryCoreApi::fetchChildItemIdsWithPermission($itemId, 'core.delete');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$peers = array();
	$peerTypes = array();
	$peerDescendentCounts = array();
	$numPages = 1;
	$selectedIds = array();
	if (!empty($peerIds)) {
	    $numPages = ceil(sizeof($peerIds) / $form['numPerPage']);
	    if (empty($page)) {
		/* determine which page we're on */
		$page = 1;
		for ($i = 0; $i < sizeof($peerIds); $i++) {
		    if ($peerIds[$i] == $selectedId) {
			$page = ceil(($i + 1) / $form['numPerPage']);
		    }
		}
	    }

	    $start = $form['numPerPage'] * ($page - 1);
	    $peerIds = array_slice($peerIds, $start, $form['numPerPage']);
	    if (isset($form['selectedIds'])) {
		$selectedIds = $form['selectedIds'];
		foreach ($peerIds as $peerId) {
		    if (isset($selectedIds[$peerId])) {
			unset($selectedIds[$peerId]);
		    }
		}
	    }

	    /* load peers */
	    list ($ret, $peerItems) = GalleryCoreApi::loadEntitiesById($peerIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* get peer thumbnails */
	    list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds($peerIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* build peers table */
	    foreach ($peerItems as $peerItem) {
		$peers[$peerItem->getId()] = (array)$peerItem;
		if (GalleryUtilities::isA($peerItem, 'GalleryAlbumItem')) {
		    $peerTypes['album'][$peerItem->getId()] = 1;
		} else {
		    $peerTypes['data'][$peerItem->getId()] = 1;
		}
		$peers[$peerItem->getId()]['selected'] =
		    isset($form['selectedIds'][$peerItem->getId()]);

		/* While we're at it, attach the thumbnails */
		if (isset($thumbnails[$peerItem->getId()])) {
		    $thumbnail = $thumbnails[$peerItem->getId()];
		    list ($ret, $thumbnail) =
			GalleryCoreApi::rebuildDerivativeCacheIfNotCurrent($thumbnail->getId());
		    if ($ret) {
			/* Ignore thumbnail errors so we can delete items with broken thumbnail */
		    } else {
			$peers[$peerItem->getId()]['thumbnail'] = (array)$thumbnail;
		    }
		}
	    }

	    /* Get child counts */
	    if (isset($peerTypes['album'])) {
		list ($ret, $peerDescendentCounts) =
		    GalleryCoreApi::fetchDescendentCounts(array_keys($peerTypes['album']));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}


	$urlGenerator =& $gallery->getUrlGenerator();

	$ItemDelete = array();
	$ItemDelete['canCancel'] = $urlGenerator->isNavigationBackPossible();
	$ItemDelete['peers'] = $peers;
	$ItemDelete['peerTypes'] = $peerTypes;
	$ItemDelete['peerDescendentCounts'] = $peerDescendentCounts;
	$ItemDelete['page'] = $page;
	$ItemDelete['numPages'] = $numPages;
	$ItemDelete['numPerPage'] = $form['numPerPage'];
	$ItemDelete['selectedIds'] = array_keys($selectedIds);
	$ItemDelete['selectedIdCount'] = count($selectedIds);

	$template->setVariable('controller', 'core.ItemDelete');
	$template->setVariable('ItemDelete', $ItemDelete);

	return array(null,
		     array('body' => 'modules/core/templates/ItemDelete.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('delete an item'));
    }
}
?>
