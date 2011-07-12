<?php
/*
 * $RCSfile: ItemCreateLinkSingle.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle linking one or more items from one album to
 * another.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemCreateLinkSingleController extends GalleryController {

    /**
     * Link the selected items into the destination album, if all the
     * permissions are set correctly.
     */
    function handleRequest($form) {
	global $gallery;

	$itemId = GalleryUtilities::getRequestVariables('itemId');

	$status = array();
	$error = array();
	if (isset($form['action']['link'])) {
	    if (empty($form['destination'])) {
		$error[] = 'form[error][destination][empty]';
	    }

	    if (empty($error)) {
		$destinationId = $form['destination'];

		/* Make sure we can write to the destination */
		list ($ret, $permissions) =
		    GalleryCoreApi::fetchPermissionsForItems(array($itemId, $destinationId));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if (!isset($permissions[$destinationId]['core.addDataItem'])) {
		    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__),
				 null);
		}

		if (!isset($permissions[$itemId]['core.viewSource'])) {
		    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__),
				 null);
		}

		/* Load the item */
		list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Look out for monkey business */
		if (!$item->getIsLinkable()) {
		    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__),
				 null);
		}

		/*
		 * Ok we've got a linkable item and a legal destination album.
		 * Lock everything up and start linkin'.  We need to read lock
		 * the source ids, source hierarchy and destination hierarchy.
		 */
		list ($ret, $locks[]) =
		    GalleryCoreApi::acquireReadLock(array($itemId, $destinationId));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $locks[]) = GalleryCoreApi::acquireReadLockParents($itemId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($locks);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $locks[]) = GalleryCoreApi::acquireReadLockParents($destinationId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($locks);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Create all our links */
		$classType = get_class($item);
		$linkedItem = new $classType;

		/*
		 * If we're linking to an item that's already a link,
		 * then link to its source instead.
		 */
		if ($item->isLinked()) {
		    $linkedEntity = $item->getLinkedEntity();
		    $ret = $linkedItem->createLink($linkedEntity, $destinationId);
		} else {
		    $ret = $linkedItem->createLink($item, $destinationId);
		}
		if ($ret) {
		    GalleryCoreApi::releaseLocks($locks);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = $linkedItem->save();
		if ($ret) {
		    GalleryCoreApi::releaseLocks($locks);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::addExistingItemToAlbum($linkedItem, $destinationId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($locks);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$status['linked'] = 1;

		/* Release the locks */
		$ret = GalleryCoreApi::releaseLocks($locks);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Figure out where to redirect upon success */
		$redirect['view'] = 'core.ItemAdmin';
		$redirect['subView'] = 'core.ItemCreateLinkSingle';
		$redirect['itemId'] = $itemId;
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.ItemAdmin';
	    $results['delegate']['subView'] = 'core.ItemCreateLinkSingle';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view lets you choose where you want to put the new link
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemCreateLinkSingleView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$itemId = GalleryUtilities::getRequestVariables('itemId');
	if ($form['formName'] != 'ItemCreateLinkSingle') {
	    /* First time around, load the form with item data */
	    $form['destination'] = '';
	    $form['formName'] = 'ItemCreateLinkSingle';
	}

	list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Find all the possible locations where this item can be linked. */
	list ($ret, $albumIds) =
	    GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.addDataItem');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load all the album entities */
	list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$ItemCreateLinkSingle = array();
	$ItemCreateLinkSingle['albumTree'] = GalleryUtilities::createAlbumTree($albums);
	$ItemCreateLinkSingle['itemTypeNames'] = $item->itemTypeName();

	$template->setVariable('ItemCreateLinkSingle', $ItemCreateLinkSingle);
	$template->setVariable('controller', 'core.ItemCreateLinkSingle');
	return array(null,
		     array('body' => 'modules/core/templates/ItemCreateLinkSingle.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('link an item'));
    }
}
?>
