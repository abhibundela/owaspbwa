<?php
/*
 * $RCSfile: SelectAlbum.inc,v $
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
 * Controller to select the album to add photos to.
 *
 * Allows the user to select the album that should have the photos added in to it.
 * Also lets the user select a parent album and go to the new album page should the
 * user wish to first create a new album.
 *
 * @version $Revision: 1.7 $ $Date: 2006/01/13 15:39:38 $
 * @package PublishXp
 * @author Timothy Webb <tiwebb@cisco.com>
 */
class SelectAlbumController extends GalleryController {
    /**
     * @see GalleryController:handleRequest
     */
    function handleRequest($form) {
	$results = array();
	$error = array();
	$status = array();

	/*
	 * We merely provide a redirection service here.  Permission checks will be
	 * handled by the target views.
	 */
	if (isset($form['action']['newAlbum'])) {
	    /* Redirect to the new album page. */
	    if (isset($form['albumId'])) {
		$redirect['view'] = 'publishxp.NewAlbum';
		$redirect['parentId'] = $form['albumId'];
	    } else {
		$error[] = 'form[error][albumId][missing]';
	    }
	} else if (isset($form['action']['select'])) {
	    /* Redirect to the options page */
	    if (isset($form['albumId'])) {
		$redirect['view'] = 'publishxp.Options';
		$redirect['albumId'] = $form['albumId'];
	    } else {
		$error[] = 'form[error][albumId][missing]';
	    }
	}

	$results['status'] = $status;
	$results['error'] = $error;
	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'publishxp.SelectAlbum';
	}
	return array(null, $results);
    }
}

/**
 * View to select the album to add photos to.
 *
 * Allows the user to select the album that should have the photos added in to it.
 * Also lets the user select a parent album and go to the new album page should the
 * user wish to first create a new album.
 *
 * @package PublishXp
 * @subpackage UserInterface
 */
class SelectAlbumView extends GalleryView {
    /**
     * Prepares any additional data before rendering the template.
     *
     * @see GalleryController:loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	if ($form['formName'] != 'SelectAlbum') {
	    $form['formName'] = 'SelectAlbum';
	    $form['albumId'] = GalleryUtilities::getRequestVariables('albumId');
	} else {
	    /*
	     * In case they didn't select an album in the prior page (they're going
	     * to get an error message, but we require form[albumId] to be set in
	     * the template).
	     */
	    if (!isset($form['albumId'])) {
		$form['albumId'] = null;
	    }
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
	$albumIds['allIds'] =
	    array_unique(array_merge($albumIds['addDataItem'], $albumIds['addAlbumItem']));

	/* Load all the album entities */
	list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds['allIds']);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$SelectAlbum = array();
	$SelectAlbum['albumTree'] = GalleryUtilities::createAlbumTree($albums);

	$template->setVariable('SelectAlbum', $SelectAlbum);
	$template->setVariable('controller', 'publishxp.SelectAlbum');
	$template->head('modules/publishxp/templates/Head.tpl');

	return array(null,
		     array('body' => 'modules/publishxp/templates/SelectAlbum.tpl',
			   'useFullScreen' => true));
    }
}
?>
