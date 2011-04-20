<?php
/*
 * $RCSfile: ShowItem.inc,v $
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
 * @version $Revision: 1.72 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

class ShowItemController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	/*
	 * Note that this always changes user preview mode; if we add management of other
	 * variables to this controller then we should guard this properly.  (Maybe delete this
	 * comment after writing the unit test that verifies it).
	 */
	$guestPreviewMode = GalleryUtilities::getRequestVariables('guestPreviewMode');
	if ($guestPreviewMode != null) {
	    $session =& $gallery->getSession();
	    $session->put('theme.guestPreviewMode', $guestPreviewMode ? 1 : 0);
	}

	return array(null,
		     array('return' => 1,
			   'status' => array(),
			   'error' => array()));
    }
}

/**
 * This controller will handle the rendering of an album or item.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ShowItemView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Make sure we have permission to view this item */
	$ret = GalleryCoreApi::assertHasItemPermission($item->getId(), 'core.view');
	if ($ret) {
	    if ($ret->getErrorCode() & ERROR_PERMISSION_DENIED) {
		list ($ret2, $anonymousId) = GalleryCoreApi::getPluginParameter(
		    'module', 'core', 'id.anonymousUser');
		if ($ret2) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($gallery->getActiveUserId() == $anonymousId) {
		    /* Redirect to login view */
		    return array(null,
				 array('redirect' => GalleryCapabilities::get('loginRedirect')));
		}
		/* Try to redirect to root */
		list ($ret2, $rootId) = GalleryCoreApi::getPluginParameter(
		    'module', 'core', 'id.rootAlbum');
		if ($ret2) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($item->getId() == $rootId) {
		    /* No permission on root album; redirect to login view */
		    return array(null,
				 array('redirect' => GalleryCapabilities::get('loginRedirect')));
		}
		return array(null, array('redirect' =>
		    array('view' => 'core.ShowItem', 'itemId' => $rootId)));
	    }
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Increment the view count */
	$ret = GalleryCoreApi::incrementItemViewCount($item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, array());
    }

    /**
     * @see GalleryView::_getItem()
     */
    function _getItem() {
	list ($ret, $item, $wasSpecified) = parent::_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	/* Always return wasSpecified==true no itemId param still uses root album theme/params */
	return array(null, $item, true);
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	global $gallery;

	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$typeName = $item->itemTypeName(true);
	return array(null, $typeName[1]);
    }

    /**
     * @see GalleryView::getViewType()
     */
    function getViewType() {
	return VIEW_TYPE_SHOW_ITEM;
    }
}
?>
