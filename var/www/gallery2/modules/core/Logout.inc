<?php
/*
 * $RCSfile: Logout.inc,v $
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
 * @version $Revision: 1.18 $ $Date: 2006/01/30 23:00:11 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle an user logging out of Gallery
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class LogoutController extends GalleryController {

    /**
     * @see GalleryController::isAllowedInMaintenance()
     */
    function isAllowedInMaintenance() {
	return true;
    }

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest() {
	global $gallery;

	$event = GalleryCoreApi::newEvent('Gallery::Logout');
	$event->setEntity($gallery->getActiveUser());
	list ($ret) = GalleryCoreApi::postEvent($event);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$session =& $gallery->getSession();
	$ret = $session->reset();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $guestUser) = GalleryCoreApi::loadEntitiesById($anonymousUserId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	
	$gallery->setActiveUser($guestUser);

	/* Force return to core.ShowItem, as we don't know if the guest user
	 * has necessary permissions for the return page
	 */
	$results['redirect']['view'] = 'core.ShowItem';
	$results['status'] = array();
	$results['error'] = array();

	return array(null, $results);
    }
}
?>
