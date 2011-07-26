<?php
/*
 * $RCSfile: UserWatermarks.inc,v $
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
 * @version $Revision: 1.15 $ $Date: 2006/01/13 03:39:53 $
 * @package Watermark
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class UserWatermarksController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$status = array();
	$error = array();
	if (isset($form['action']['add']) && !empty($form['tmp_name'][1])) {

	    /* Get the mime type from the upload info. */
	    $mimeType = $form['type'][1];

	    /*
	     * If we don't get useful data from that or its a type we don't
	     * recognize, take a swing at it using the file name.
	     */
	    list ($ret, $mimeExtensions) = GalleryCoreApi::convertMimeToExtensions($mimeType);
	    if ($mimeType == 'application/octet-stream' ||
		$mimeType == 'application/unknown' || empty($mimeExtensions)) {
		$extension = GalleryUtilities::getFileExtension($form['name'][1]);
		list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
		if ($ret) {
		    $mimeType = 'application/unknown';
		}
	    }

	    list ($ret, $item) =
		GalleryCoreApi::newFactoryInstance('GalleryEntity', 'WatermarkImage');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (!isset($item)) {
		return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__), null);
	    }

	    $ret = $item->create($form['tmp_name'][1], $mimeType, $form['name'][1]);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $ret = $item->save();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Give appropriate permissions */
	    list ($ret, $id) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'id.adminGroup');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $ret = GalleryCoreApi::addGroupPermission($item->getId(), $id, 'core.all');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $ret = GalleryCoreApi::addUserPermission($item->getId(), $gallery->getActiveUserId(),
						     'core.all');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $results['redirect']['view'] = 'core.UserAdmin';
	    $results['redirect']['subView'] = 'watermark.UserWatermarkEdit';
	    $results['redirect']['watermarkId'] = $item->getId();
	} else if (isset($form['action']['add'])) {
	    $error[] = 'form[error][missingFile]';
	    $results['delegate']['view'] = 'core.UserAdmin';
	    $results['delegate']['subView'] = 'watermark.UserWatermarks';
	} else if (isset($form['action']['delete']) && isset($form['delete']['watermarkId'])) {
	    GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	    list ($ret, $wasDeleted) =
		WatermarkHelper::deleteWatermarkImageById($form['delete']['watermarkId']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($wasDeleted) {
		$status['delete'] = 1;
		$results['redirect']['view'] = 'core.UserAdmin';
		$results['redirect']['subView'] = 'watermark.UserWatermarks';
	    } else {
		/* Watermark is in use.. redirect to confirm page: */
		$results['redirect']['view'] = 'core.UserAdmin';
		$results['redirect']['subView'] = 'watermark.ConfirmDelete';
		$results['redirect']['watermarkId'] = $form['delete']['watermarkId'];
	    }
	}

	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class UserWatermarksView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($gallery->getActiveUserId() == $anonymousUserId) {
	    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
	}

	list ($ret, $watermarks) = WatermarkHelper::fetchWatermarks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	foreach ($watermarks as $id => $watermark) {
	    /* Exclude site-wide watermarks (and, in the case of admins -- other people's) */
	    if ($watermark->getOwnerId() != $gallery->getActiveUserId()) {
		unset($watermarks[$id]);
		continue;
	    }

	    $watermarks[$id] = (array)$watermark;
	}

	if ($form['formName'] != 'UserWatermarks') {
	    $form['formName'] = 'UserWatermarks';
	    if (!empty($watermarks)) {
		$ids = array_keys($watermarks);
		$form['watermarkId'] = array_shift($ids);
	    }
	}

	/* Set the form's encoding type since we're uploading binary files */
	if ($template->hasVariable('UserAdmin')) {
	    $UserAdmin =& $template->getVariableByReference('UserAdmin');
	    $UserAdmin['enctype'] = 'multipart/form-data';
	} else {
	    $UserAdmin['enctype'] = 'multipart/form-data';
	    $template->setVariable('UserAdmin', $UserAdmin);
	}

	$UserWatermarks = array();
	$UserWatermarks['watermarks'] = $watermarks;

	$template->setVariable('UserWatermarks', $UserWatermarks);
	$template->setVariable('controller', 'watermark.UserWatermarks');
	return array(null,
		     array('body' => 'modules/watermark/templates/UserWatermarks.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'watermark');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('user watermarks'));
    }
}
?>
