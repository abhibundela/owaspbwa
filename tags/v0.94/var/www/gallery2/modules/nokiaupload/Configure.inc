<?php
/*
 * $RCSfile: Configure.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/01/10 04:41:12 $
 * @package NokiaUpload
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package NokiaUpload
 * @subpackage UserInterface
 */
class ConfigureController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	GalleryCoreApi::requireOnce('modules/nokiaupload/classes/ImageUploadHelper.class');
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$error = array();
	$status = array();
	if (isset($form['action']['save'])) {

	    if (empty($form['group'])) {
		$error[] = 'form[error][group][missing]';
	    }
	    if (empty($form['album'])) {
		$error[] = 'form[error][album][missing]';
	    }

	    if (empty($error)) {

		/* Create group for the mobile users. */
		list ($ret, $groupId) = ImageUploadHelper::createMobileUserGroup($form['group']);
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_COLLISION) {
			$error[] = 'form[error][group][duplicate]';
		    } else {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}

		if (empty($error)) {
		    list ($ret, $albumId) =
			ImageUploadHelper::createUploadAlbum($form['album'], $form['parent']);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $ret = ImageUploadHelper::setAlbumPermissions($albumId, $groupId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $status['configured'] = 'nokiaupload';
		}
	    }
	} /* else $form['action']['cancel'] */

	if (empty($error)) {
	    $results['redirect'] = array('view' => 'core.SiteAdmin',
					 'subView' => 'core.AdminModules');
	} else {
	    $results['delegate'] = array('view' => 'core.SiteAdmin',
					 'subView' => 'nokiaupload.Configure');
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package NokiaUpload
 * @subpackage UserInterface
 */
class ConfigureView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'nokiaupload');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load our default values if we didn't just come from this form. */
	if ($form['formName'] != 'NokiaUploadConfigure') {
	    $form['formName'] = 'NokiaUploadConfigure';
	    $form['group'] = $module->translate('Mobile Users');
	    $form['album'] = $module->translate('Pictures from mobile phones');
	    $form['parent'] = '';
	}

	list ($ret, $albumIds) =
	    GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.addAlbumItem');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$albumTree = GalleryUtilities::createAlbumTree($albums);

	$Configure = array('albumTree' => $albumTree);
	$template->setVariable('NokiaUploadConfigure', $Configure);
	$template->setVariable('controller', 'nokiaupload.Configure');

	return array(null,
		     array('body' => 'modules/nokiaupload/templates/Configure.tpl'));
    }
}
?>
