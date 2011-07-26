<?php
/*
 * $RCSfile: UploadItems.inc,v $
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
 * Controller to process the uploading of photos from Windows XP.
 *
 * Each photo is uploaded via a seperate HTTP request.  The initial response from
 * this page is to return JavaScript code that instructs Windows how to upload
 * the photos.  Subsequent requests from Windows include the photo data and text.
 * This page processes the data and applies the options to be used while uploading.
 *
 * @version $Revision: 1.12 $ $Date: 2006/01/13 15:39:38 $
 * @package PublishXp
 * @author Timothy Webb <tiwebb@cisco.com>
 */
class UploadItemsController extends GalleryController {
    /**
     * ItemAddOption instances to use when handling this request.  Only used by
     * test code.
     *
     * @var array (optionId => object ItemAddOption) $_optionInstances
     * @access private
     */
    var $_optionInstances;

    /**
     * Tests can use this method to hardwire a specific set of option instances to use.
     * This avoids situations where some of the option instances will do unpredictable
     * things and derail the tests.
     *
     * @param array (optionId => ItemAddOption, ...)
     */
    function setOptionInstances($optionInstances) {
	$this->_optionInstances = $optionInstances;
    }

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$results = array();
	$error = array();
	$status = array();
	if (isset($form['action']['uploadItem'])) {
	    /* Check the user has permissions in this album */
	    $ret = GalleryCoreApi::assertHasItemPermission($form['albumId'], 'core.addDataItem');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Get the file passed in this submission */
	    $file = GalleryUtilities::getFile('userFile', false);
	    list($ret, $lockIds[]) = GalleryCoreApi::acquireReadLock($form['albumId']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (empty($file['name'])) {
		GalleryCoreApi::releaseLocks($lockIds);
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	    }

	    /*
	     * Get the mime type from the upload info.
	     * If we don't get useful data from that or its a type we don't
	     * recognize, take a swing at it using the file name.
	     */
	    $mimeType = $file['type'];
	    if (!empty($mimeType)) {
		list ($ret, $exts) = GalleryCoreApi::convertMimeToExtensions($mimeType);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockIds);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }

	    if (empty($mimeType) ||
		    $mimeType == 'application/octet-stream' ||
		    $mimeType == 'application/unknown' ||
		    empty($exts)) {
		$extension = GalleryUtilities::getFileExtension($file['name']);
		list($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockIds);
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	    $title = basename($file['name']);
	    $caption = '';
	    $description = '';
	    if (!empty($form['stripExtensions'])) {
		$title = GalleryUtilities::getFileBase($title);
	    }
	    if (!empty($form['setCaptions'])) {
		$caption = $title;
	    }
	    list($ret, $newItem) = GalleryCoreApi::addItemToAlbum(
		$file['tmp_name'], basename($file['name']), $title, $caption, $description,
		$mimeType, $form['albumId']);
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockIds);
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $ret = GalleryCoreApi::releaseLocks($lockIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (isset($this->_optionInstances)) {
		$optionInstances = $this->_optionInstances;
	    } else {
		GalleryCoreApi::requireOnce('modules/core/ItemAdd.inc');
		list ($ret, $optionInstances) = ItemAddOption::getAllAddOptions();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }

	    /* Allow ItemAddOptions to process added item(s) */
	    $session =& $gallery->getSession();
	    if ($session->exists('publishxp.extraOptionsForm')) {
		$extraOptionsForm = unserialize($session->get('publishxp.extraOptionsForm'));
	    } else {
		$extraOptionsForm = array();
	    }
	    foreach ($optionInstances as $option) {
		list ($ret, $optionErrors, $optionWarnings) =
		    $option->handleRequestAfterAdd($extraOptionsForm, array($newItem));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/*
		 * Swallow option warnings and errors for now.  XP uploads the images one at a
		 * time, so the right way to handle this would probably be to store the results
		 * into the session and then redirect to a final status page after all submissions
		 * are complete.
		 */
	    }

	    $redirect['view'] = 'publishxp.UploadedItem';
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'publishxp.UploadItems';
	}
	$results['status'] = $status;
	$results['error'] = $error;
	return array(null, $results);
    }
}

/**
 * View to process the uploading of photos from Windows XP.
 *
 * Each photo is uploaded via a seperate HTTP request.  The initial response from
 * this page is to return JavaScript code that instructs Windows how to upload
 * the photos.  Subsequent requests from Windows include the photo data and text.
 * This page processes the data and applies the options to be used while uploading.
 *
 * @package PublishXp
 * @subpackage UserInterface
 */
class UploadItemsView extends GalleryView {
    /**
     * Prepares any additional data before rendering the template.
     *
     * @author Timothy Webb <tiwebb@cisco.com>
     * @param template the template data for rendering.
     * @param form the form data for rendering.
     * @return array the status and the results containing the view.
     * @see GalleryControler:loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	if ($form['formName'] != 'UploadItems') {
	    $form['formName'] = 'UploadItems';
	}

	list ($UploadItems['albumId'],
	      $UploadItems['stripExtensions'],
	      $UploadItems['setCaptions']) = GalleryUtilities::getRequestVariables(
		  'albumId', 'stripExtensions', 'setCaptions');

	if (empty($UploadItems['stripExtensions'])) {
	    $UploadItems['stripExtensions'] = 0;
	}

	if (empty($UploadItems['setCaptions'])) {
	    $UploadItems['setCaptions'] = 0;
	}

	$template->setVariable('UploadItems', $UploadItems);
	$template->head('modules/publishxp/templates/Head.tpl');

	return array(null,
		     array('body' => 'modules/publishxp/templates/UploadItems.tpl',
			   'useFullScreen' => true));
    }
}
?>
