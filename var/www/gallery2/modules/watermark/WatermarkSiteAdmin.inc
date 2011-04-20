<?php
/*
 * $RCSfile: WatermarkSiteAdmin.inc,v $
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
 * @version $Revision: 1.15 $ $Date: 2006/01/17 01:52:04 $
 * @package Watermark
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class WatermarkSiteAdminController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = array();
	if (isset($form['action']['add']) && !empty($form['tmp_name'][1])) {

	    /* Get the mime type from the upload info. */
	    $mimeType = $form['type'][1];

	    /*
	     * If we don't get useful data from that or its a type we don't
	     * recognize, take a swing at it using the file name.
	     */
	    list ($ret, $mimeExtensions) = GalleryCoreApi::convertMimeToExtensions($mimeType);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if ($mimeType == 'application/octet-stream' ||
		    $mimeType == 'application/unknown' || empty($mimeExtensions)) {
		$extension = GalleryUtilities::getFileExtension($form['name'][1]);
		list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
		if ($ret || empty($mimeType)) {
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
	    foreach (array('id.allUserGroup' => 'core.viewSource',
			   'id.adminGroup' => 'core.all') as $key => $perm) {
		list ($ret, $id) = GalleryCoreApi::getPluginParameter('module', 'core', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::addGroupPermission($item->getId(), $id, $perm);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }

	    $status['add'] = 1;
	    $results['redirect'] = array('view' => 'core.SiteAdmin',
					 'subView' => 'watermark.WatermarkSiteAdminEdit',
					 'watermarkId' => $item->getId());
	} else if (isset($form['action']['add'])) {
	    $status['add_error'] = 1;
	} else if (isset($form['action']['delete']) && isset($form['delete']['watermarkId'])) {
	    GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	    list ($ret, $wasDeleted) =
		WatermarkHelper::deleteWatermarkImageById($form['delete']['watermarkId']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($wasDeleted) {
		$status['delete'] = 1;
	    } else {
		/* Watermark is in use.. redirect to confirm page */
		$results['redirect'] = array('view' => 'core.SiteAdmin',
					     'subView' => 'watermark.ConfirmDelete',
					     'watermarkId' => $form['delete']['watermarkId'],
					     'fromAdmin' => 1);
	    }
	} else if (isset($form['action']['save'])) {
	    if (empty($form['hotlinkWatermarkId'])) {
		$status['missingId'] = 1;
	    } else {
		$ret = GalleryCoreApi::setPluginParameter('module', 'watermark',
		    'hotlinkWatermarkId', $form['hotlinkWatermarkId']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$status['idSaved'] = 1;
	    }
	}

	if (!isset($results['redirect'])) {
	    $results['redirect'] = array('view' => 'core.SiteAdmin',
					 'subView' => 'watermark.WatermarkSiteAdmin');
	}
	$results['status'] = $status;
	$results['error'] = array();

	return array(null, $results);
    }
}

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class WatermarkSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'WatermarkSiteAdmin') {
	    $form['formName'] = 'WatermarkSiteAdmin';

	    list ($ret, $form['hotlinkWatermarkId']) =
		GalleryCoreApi::getPluginParameter('module', 'watermark', 'hotlinkWatermarkId');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}
	list ($ret, $list) = WatermarkHelper::fetchWatermarks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$owners = array();
	$form['list'] = array();

	if (!empty($list)) {
	    $ownerIds = array();
	    foreach ($list as $id => $watermark) {
		$ownerIds[] = $watermark->getOwnerId();
		$form['list'][] = (array)$watermark;
	    }
	    list ($ret, $tmp) = GalleryCoreApi::loadEntitiesById($ownerIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    for ($i = 0; $i < sizeof($tmp); $i++) {
		$owners[$tmp[$i]->getId()] = (array)$tmp[$i];
	    }
	}

	/* Set the form's encoding type since we're uploading binary files */
	if ($template->hasVariable('SiteAdmin')) {
	    $SiteAdmin =& $template->getVariableByReference('SiteAdmin');
	    $SiteAdmin['enctype'] = 'multipart/form-data';
	} else {
	    $SiteAdmin['enctype'] = 'multipart/form-data';
	    $template->setVariable('SiteAdmin', $SiteAdmin);
	}

	$WatermarkSiteAdmin = array();
	$WatermarkSiteAdmin['owners'] = $owners;

	$template->setVariable('controller', 'watermark.WatermarkSiteAdmin');
	$template->setVariable('WatermarkSiteAdmin', $WatermarkSiteAdmin);
	return array(null,
		     array('body' => 'modules/watermark/templates/WatermarkSiteAdmin.tpl'));
    }
}
?>
