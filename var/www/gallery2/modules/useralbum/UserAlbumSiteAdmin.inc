<?php
/*
 * $RCSfile: UserAlbumSiteAdmin.inc,v $
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
 * @version $Revision: 1.12 $ $Date: 2006/01/17 01:52:04 $
 * @package UserAlbum
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package UserAlbum
 * @subpackage UserInterface
 */
class UserAlbumSiteAdminController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = array();
	if (isset($form['action']['save'])) {
	    foreach (array('create', 'view', 'fullSize', 'targetLocation') as $key) {
		$ret = GalleryCoreApi::setPluginParameter('module', 'useralbum', $key, $form[$key]);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	    foreach (array('loginRedirect', 'homeLink') as $key) {
		$ret = GalleryCoreApi::setPluginParameter('module', 'useralbum', $key,
							  isset($form[$key]) ? 1 : 0);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	    $status['saved'] = 1;
	} /* else $form['action']['reset'] */

	/* Figure out where to redirect upon success */
	$results['redirect']['view'] = 'core.SiteAdmin';
	$results['redirect']['subView'] = 'useralbum.UserAlbumSiteAdmin';
	$results['status'] = $status;
	$results['error'] = array();

	return array(null, $results);
    }
}

/**
 * @package UserAlbum
 * @subpackage UserInterface
 */
class UserAlbumSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'UserAlbumSiteAdmin') {
	    $form['formName'] = 'UserAlbumSiteAdmin';
	    foreach (array('create', 'view', 'fullSize', 'targetLocation',
			   'loginRedirect', 'homeLink') as $key) {
		list ($ret, $form[$key]) =
		    GalleryCoreApi::getPluginParameter('module', 'useralbum', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'useralbum');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	/* Get ids of all all albums where we can add new album items */
	list ($ret, $albumIds) =
	    GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.addAlbumItem');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load all the album entities */
	list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$g2AlbumTree = GalleryUtilities::createAlbumTree($albums);

	$UserAlbumSiteAdmin = array(
	    'createList' => array('immediate' => $module->translate('When user is created'),
				  'access' => $module->translate('When first accessed')),
	    'viewList' => array('everybody' => $module->translate('Everybody'),
				'allusers' => $module->translate('All Users'),
				'user' => $module->translate('User Only')),
	    'sizeList' => array('1' => $module->translate('Yes'),
				'0' => $module->translate('No')),
	    'targetLocation' => $g2AlbumTree
	);

	$template->setVariable('UserAlbumSiteAdmin', $UserAlbumSiteAdmin);
	$template->setVariable('controller', 'useralbum.UserAlbumSiteAdmin');
	return array(null,
		     array('body' => 'modules/useralbum/templates/UserAlbumSiteAdmin.tpl'));
    }
}
?>
