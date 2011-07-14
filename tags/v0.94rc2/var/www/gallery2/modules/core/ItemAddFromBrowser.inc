<?php
/*
 * $RCSfile: ItemAddFromBrowser.inc,v $
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
 * @version $Revision: 1.20 $ $Date: 2006/02/06 17:55:30 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This ItemAdd plugin adds items uploaded from the web browser.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemAddFromBrowser extends ItemAddPlugin {

    /**
     * @see ItemAddPlugin::handleRequest
     */
    function handleRequest($form, &$item) {
	global $gallery;

	$status = array();
	$error = array();
	if (isset($form['action']['addFromBrowser'])) {

	    /* Upload any new files */
	    for ($i = 1; $i <= $form['uploadBoxCount']; $i++) {
		$newItem = null;

		/* Placeholder for later */
		if (!empty($form['tmp_name'][$i]) && !empty($form['size'][$i])) {
		    $file = array('name' => $form['name'][$i],
				  'type' => $form['type'][$i],
				  'tmp_name' => $form['tmp_name'][$i],
				  'size' => $form['size'][$i],
				  'caption' => $form['caption'][$i]);

		    /* Get the mime type from the upload info. */
		    $mimeType = $file['type'];

		    /*
		     * If we don't get useful data from that or its a type we don't
		     * recognize, take a swing at it using the file name.
		     */
		    list ($ret, $mimeExtensions) =
			GalleryCoreApi::convertMimeToExtensions($mimeType);
		    if ($mimeType == 'application/octet-stream' ||
			    $mimeType == 'application/unknown' ||
			    empty($mimeExtensions)) {
			$extension = GalleryUtilities::getFileExtension($file['name']);
			list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
			if ($ret) {
			    $mimeType = 'application/unknown';
			}
		    }

		    $filename = basename($file['name']);
		    list ($base, $extension) = GalleryUtilities::getFileNameComponents($filename);
		    $title = ($form['set']['title'] == 'filename') ? $base
			   : (($form['set']['title'] == 'caption') ? $file['caption'] : '');
		    $summary = empty($form['set']['summary']) ? '' : $file['caption'];
		    $description = empty($form['set']['description']) ? '' : $file['caption'];

		    /*
		     * Don't use uploaded files, because the framework cannot safely copy them.
		     * Move it to our temporary directory first.
		     */

		    $platform =& $gallery->getPlatform();
		    if ($platform->is_uploaded_file($file['tmp_name'])) {
			$tmpFile = $platform->move_uploaded_file($file['tmp_name']);
			if (!$tmpFile) {
			    return array(GalleryCoreApi::error(
				ERROR_PLATFORM_FAILURE, __FILE__, __LINE__), null, null);
			}
			$needToDeleteTmpFile = true;
		    } else {
			$tmpFile = $file['tmp_name'];
			$needToDeleteTmpFile = false;
		    }

		    list ($ret, $newItem) = GalleryCoreApi::addItemToAlbum(
			$tmpFile, $filename, $title, $summary,
			$description, $mimeType, $item->getId());

		    /* Get rid of the tmp file if necessary */
		    if ($needToDeleteTmpFile) {
			@$platform->unlink($tmpFile);
		    }

		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    $status['addedFiles'][] = array('fileName' => $file['name'],
						    'id' => $newItem->getId(),
						    'warnings' => array());
		}
	    }
	}

	return array(null, $error, $status);
    }

    /**
     * @see ItemAdd:loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	$fileUploadsBool = GalleryUtilities::getPhpIniBool('file_uploads');
	$totalUploadSize = ini_get('post_max_size');
	if (preg_match("/(\d+)M/", $totalUploadSize, $matches)) {
	    $totalUploadSize = $matches[1] * 1024 * 1024;
	}

	$maxFileSize = ini_get('upload_max_filesize');
	if (preg_match("/(\d+)M/", $maxFileSize, $matches)) {
	    $maxFileSize = $matches[1] * 1024 * 1024;
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	foreach (array('totalUploadSize', 'maxFileSize') as $key) {
	    if ($$key >= 1024 * 1024) {
		$$key = $module->translate(array('one' => '%d megabyte',
						 'many' => '%d megabytes',
						 'count' => (int)($$key / (1024 * 1024)),
						 'arg1' => (int)($$key / (1024 * 1024))));
	    } else if ($$key >= 1024) {
		$$key = $module->translate(array('one' => '%d kilobytes',
						 'many' => '%d kilobytes',
						 'count' => (int)($$key / (1024)),
						 'arg1' => (int)($$key / (1024))));
	    }
	}

	if ($form['formName'] != 'ItemAddFromBrowser') {
	    /* First time around, load the form with item data */
	    $form['uploadBoxCount'] = 20;
	    $form['visibleBoxCount'] = 4;
	    $form['set'] = array('title' => 'filename', 'summary' => 1, 'description' => 1);
	    $form['formName'] = 'ItemAddFromBrowser';
	}

	$titleList = array('filename' => $module->translate('Base filename'),
			   'caption' => $module->translate('Caption'),
			   '' => $module->translate('Blank'));

	$template->setVariable('ItemAddFromBrowser',
	    array('totalUploadSize' => $totalUploadSize,
		  'maxFileSize' => $maxFileSize,
		  'uploadsPermitted' => $fileUploadsBool,
		  'titleList' => $titleList));

	/* Set the ItemAdmin form's encoding type specially since we're uploading binary files */
	if ($template->hasVariable('ItemAdmin')) {
	    $ItemAdmin =& $template->getVariableByReference('ItemAdmin');
	    $ItemAdmin['enctype'] = 'multipart/form-data';
	} else {
	    $ItemAdmin['enctype'] = 'multipart/form-data';
	    $template->setVariable('ItemAdmin', $ItemAdmin);
	}

	return array(null,
		     'modules/core/templates/ItemAddFromBrowser.tpl',
		     'modules_core');
    }

    /**
     * @see ItemAddPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('From Web Browser'));
    }
}
?>
