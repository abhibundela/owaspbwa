<?php
/*
 * $RCSfile: MimeEdit.inc,v $
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
 * @version $Revision: 1.7 $ $Date: 2006/01/10 04:40:47 $
 * @package Mime
 * @author Felix Rabinovich <Felix@Rabinovich.org>
 */

/**
 * @package Mime
 * @subpackage Administration
 */
class MimeEditController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$error = array();
	$status = array();

	if (isset($form['action']['save']) || isset($form['action']['apply'])) {
	    if (empty($form['mimeType'])) {
		$error[] = 'form[error][mimeType][missing]';
	    }
	    $form['mimeType'] = strtolower($form['mimeType']);

	    $allEmpty = true;
	    foreach ($form['extensions'] as $ext) {
		if (!empty($ext)) {
		    $allEmpty = false;
		    $ext = strtolower($ext);
		    $mimeTypeMap[] = array(
			'extension' => $ext, 'mimeType' => $form['mimeType'],
			'viewable' => (int) isset($form['viewable']));

		    list ($ret, $existingMimeType) = GalleryCoreApi::convertExtensionToMime($ext);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    if ($existingMimeType != $form['mimeType'] &&
			    $existingMimeType != 'application/unknown') {
			$error[] = 'form[error][extensions][inuse][' . $ext . ']';
		    }
		}
	    }

	    if ($allEmpty) {
		$error[] = 'form[error][extensions][missing]';
	    }

	    if (empty($error)) {
		$ret = GalleryCoreApi::removeMimeType(array('mimeType' => $form['mimeType']));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		foreach ($mimeTypeMap as $mimeType) {
		    $ret = GalleryCoreApi::addMimeType(
			   $mimeType['extension'], $mimeType['mimeType'], $mimeType['viewable']);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
		$status['saved'] = true;
	    }
	}

	if (empty($error)) {
	    if (isset($form['action']['apply'])) {
		$results['redirect'] = array('view' => 'core.SiteAdmin',
					     'subView' => 'mime.MimeEdit',
					     'mimeType' => $form['mimeType']);
	    } else {
		$results['redirect'] = array('view' => 'core.SiteAdmin',
					     'subView' => 'mime.MimeAdmin');
	    }
	} else {
	    $results['delegate'] = array('view' => 'core.SiteAdmin', 'subView' => 'mime.MimeEdit');
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Mime
 * @subpackage Administration
 */
class MimeEditView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$mimeType = GalleryUtilities::getRequestVariables('mimeType');

	if ($form['formName'] != 'MimeEdit') {
	    $form['formName'] = 'MimeEdit';
	    $form['mimeType'] = $mimeType;

	    if ($mimeType) {
		list ($ret, $form['extensions']) =
		    GalleryCoreApi::convertMimeToExtensions($mimeType);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $form['viewable']) = GalleryCoreApi::isViewableMimeType($mimeType);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    } else {
		$form['viewable'] = false;
		$form['extensions'] = array();
	    }
	} else {
	    /*
	     * Force our checkbox to be false on empty (since browsers don't send a value
	     * if the checkbox is not checked.
	     */
	    if (empty($form['viewable'])) {
		$form['viewable'] = false;
	    }

	    /* Prune empty extension boxes */
	    $tmp = array();
	    foreach ($form['extensions'] as $extension) {
		if (!empty($extension)) {
		    $tmp[] = $extension;
		}
	    }
	    $form['extensions'] = $tmp;
	}

	$template->setVariable('controller', 'mime.MimeEdit');
	return array(null,
	     array('body' => 'modules/mime/templates/MimeEdit.tpl'));
    }
}
?>
