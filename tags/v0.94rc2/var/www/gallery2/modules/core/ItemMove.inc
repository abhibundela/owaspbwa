<?php
/*
 * $RCSfile: ItemMove.inc,v $
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
 * @version $Revision: 1.44 $ $Date: 2006/01/13 03:39:31 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Ernesto Baschny <ernst@baschny.de>
 */

/**
 * This controller will handle moving one or more items from one album to
 * another.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemMoveController extends GalleryController {

    /**
     * Move the selected items into the destination album, if all the
     * permissions are set correctly.
     */
    function handleRequest($form) {
	global $gallery;

	$itemId = GalleryUtilities::getRequestVariables('itemId');

	$status = array();
	$error = array();
	if (isset($form['action']['move'])) {

	    /* First check if everything would be okay with the change */
	    $canAddItem = false;
	    $canAddAlbum = false;
	    if (!empty($form['destination'])) {
		/* Check if we can add albums or items here */
		$newParentId = $form['destination'];
		list ($ret, $permissions) = GalleryCoreApi::getPermissions($newParentId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$canAddItem = isset($permissions['core.addDataItem']);
		$canAddAlbum = isset($permissions['core.addAlbumItem']);
		if (!$canAddAlbum && !$canAddItem) {
		    $error[] = 'form[error][destination][permission]';
		}

		/* Load destination parent ids: we don't want recursive moves */
		list ($ret, $newParentAncestorIds) =
		    GalleryCoreApi::fetchParentSequence($newParentId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$newParentAncestorIds[] = $newParentId;
	    } else {
		$error[] = 'form[error][destination][empty]';
	    }

	    if (empty($error) && !empty($form['selectedIds'])) {
		$selectedIds = array_keys($form['selectedIds']);

		/* Load the source items */
		list ($ret, $selectedItems) = GalleryCoreApi::loadEntitiesById($selectedIds);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$ret = GalleryCoreApi::studyPermissions($selectedIds);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$oldParentIds = array();
		foreach ($selectedItems as $selectedItem) {
		    $selectedId = $selectedItem->getId();

		    /* Can't move into a tree that is included in the source */
		    if (in_array($selectedId, $newParentAncestorIds)) {
			$error[] = 'form[error][source][' . $selectedId . '][selfMove]';
			continue;
		    }

		    $oldParentIds[] = $selectedItem->getParentId();
		    list ($ret, $permissions) = GalleryCoreApi::getPermissions($selectedId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    /* Can we delete this item from here? */
		    if (! isset($permissions['core.delete'])) {
			$error[] = 'form[error][source][' . $selectedId . '][permission][delete]';
		    }

		    /* Check if the destination allows this source to be added */
		    if ($selectedItem->getCanContainChildren() && ! $canAddAlbum) {
			$error[] = 'form[error][source][' . $selectedId .
				   '][permission][addAlbumItem]';
		    } else if (! $selectedItem->getCanContainChildren() && ! $canAddItem) {
			$error[] = 'form[error][source][' . $selectedId .
				   '][permission][addDataItem]';
		    }
		}
		$oldParentIds = array_values(array_unique($oldParentIds));
	    }

	    if (empty($error) && !empty($selectedIds) && !empty($newParentId)) {

		/* Read lock myself, my parent id and the new parent id */
		$lockIds = array();
		list ($ret, $lockIds[]) =
		    GalleryCoreApi::acquireReadLock(array($newParentId, $itemId));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Do the move / locking in batches to prevent too many open files issues */
		$batchSize = 100;
		$status['moved']['count'] = 0;
		do {
		    $currentItems = array_splice($selectedItems, 0, $batchSize);
		    $currentIds = array();
		    foreach ($currentItems as $item) {
			$currentIds[] = $item->getId();
		    }
		    /* Write lock all the items we're moving */
		    list ($ret, $currentLockIds) = GalleryCoreApi::acquireWriteLock($currentIds);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    /* If we have no problems, do the moves */
		    foreach ($currentItems as $selectedItem) {
			$ret = $selectedItem->move($newParentId);
			if ($ret) {
			    GalleryCoreApi::releaseLocks(array_merge($lockIds, $currentLockIds));
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
			$status['moved']['count']++;

			$ret = $selectedItem->save();
			if ($ret) {
			    GalleryCoreApi::releaseLocks(array_merge($lockIds, $currentLockIds));
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}

			if (GalleryUtilities::isA($selectedItem, 'GalleryDataItem')) {
			    /* Update for derivative preferences of new parent */
			    $ret =
				GalleryCoreApi::addExistingItemToAlbum($selectedItem, $newParentId);
			    if ($ret) {
				GalleryCoreApi::releaseLocks(
				    array_merge($lockIds, $currentLockIds));
				return array($ret->wrap(__FILE__, __LINE__), null);
			    }
			}
		    }
		    $ret = GalleryCoreApi::releaseLocks($currentLockIds);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		} while (!empty($selectedItems));

		$ret = GalleryCoreApi::releaseLocks($lockIds);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $success) = GalleryCoreApi::guaranteeAlbumHasThumbnail($itemId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Figure out where to redirect upon success */
		$redirect['view'] = 'core.ItemAdmin';
		$redirect['subView'] = 'core.ItemMove';
		$redirect['itemId'] = $itemId;
	    }
	} else if (isset($form['action']['next'])) {
	    $page = GalleryUtilities::getRequestVariables('page');
	    list ($ret, $peerIds) =
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
	    $results['return'] = true;
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.ItemAdmin';
	    $results['delegate']['subView'] = 'core.ItemMove';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will prompt for which files to move/copy and which is the
 * destination.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemMoveView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	/* itemId is the album where we want to move items from */
	list ($itemId, $selectedId, $page) =
	    GalleryUtilities::getRequestVariables('itemId', 'selectedId', 'page');
	if ($form['formName'] != 'ItemMove') {
	    /* First time around, load the form with item data */
	    if ($selectedId) {
		$form['selectedIds'][$selectedId] = true;
	    }
	    $form['destination'] = '';
	    $form['formName'] = 'ItemMove';
	    $form['numPerPage'] = 15;
	}

	list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Get all peers that we can delete */
	list ($ret, $peerIds) =
	    GalleryCoreApi::fetchChildItemIdsWithPermission($itemId, 'core.delete');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$albumIds = array();
	$peers = array();
	$peerTypes = array();
	$peerTypes['album'] = array();
	$peerTypes['data'] = array();
	$peerDescendentCounts = array();
	$albumTree = array();
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

	    /* Add any items with error messages that would otherwise not be shown */
	    if (!empty($form['error']['source'])) {
		foreach ($form['error']['source'] as $id => $tmp) {
		    if (!in_array($id, $peerIds)) {
			array_unshift($peerIds, $id);
			unset($selectedIds[$id]);
		    }
		}
	    }

	    /* Load all the peers */
	    list ($ret, $peerItems) = GalleryCoreApi::loadEntitiesById($peerIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* get peer thumbnails */
	    list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds($peerIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Build our peers table */
	    $peers = array();
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
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $peers[$peerItem->getId()]['thumbnail'] = (array)$thumbnail;
		}
	    }

	    /* Get child counts */
	    if (!empty($peerTypes['album'])) {
		list ($ret, $peerDescendentCounts) =
		    GalleryCoreApi::fetchDescendentCounts(array_keys($peerTypes['album']));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }

	    /* Get all possible destinations (where user has write permission) */
	    list ($ret, $rootId) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Get ids of all all albums where we can add new data items */
	    list ($ret, $albumIds['addDataItem']) =
		GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.addDataItem');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Get ids of all all albums where we can add new album items */
	    list ($ret, $albumIds['addAlbumItem']) =
		GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.addAlbumItem');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Merge them together to get the master list of ids */
	    $albumIds['allIds'] = array_unique(array_merge($albumIds['addDataItem'],
							   $albumIds['addAlbumItem']));

	    /* Load all the album entities */
	    list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds['allIds']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $albumTree = GalleryUtilities::createAlbumTree($albums);
	}

	$urlGenerator =& $gallery->getUrlGenerator();

	$ItemMove = array();
	$ItemMove['canCancel'] = $urlGenerator->isNavigationBackPossible();
	$ItemMove['albumIds'] = $albumIds;
	$ItemMove['peers'] = $peers;
	$ItemMove['peerTypes'] = $peerTypes;
	$ItemMove['peerDescendentCounts'] = $peerDescendentCounts;
	$ItemMove['albumTree'] = $albumTree;
	$ItemMove['page'] = $page;
	$ItemMove['numPages'] = $numPages;
	$ItemMove['numPerPage'] = $form['numPerPage'];
	$ItemMove['selectedIds'] = array_keys($selectedIds);
	$ItemMove['selectedIdCount'] = count($selectedIds);

	$template->setVariable('ItemMove', $ItemMove);
	$template->setVariable('controller', 'core.ItemMove');

	return array(null,
		     array('body' => 'modules/core/templates/ItemMove.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('move item'));
    }
}
?>
