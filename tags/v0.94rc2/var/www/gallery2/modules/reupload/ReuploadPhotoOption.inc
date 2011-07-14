<?php
/*
 * $RCSfile: ReuploadPhotoOption.inc,v $
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
 * @version $Revision: 1.1 $ $Date: 2006/01/27 04:36:04 $
 * @package Reupload
 * @author Piotr P. Karwasz <piotr.karwasz@ens.fr>
 */

/**
 * This controller will handle the reupload of a photo
 *
 * @package Reupload
 */
class ReuploadPhotoOption extends ItemEditOption {

    /**
     * @see ItemEditOption::handleRequestAfterEdit
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	$status = array();
	$error = array();

	if (!empty($form['tmp_name']['reupload']) && !empty($form['size']['reupload'])) {
	    $inputFileName = $form['tmp_name']['reupload'];

	    list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'reupload');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    if (!$platform->is_uploaded_file($inputFileName)
		    || !$platform->file_exists($inputFileName)) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__),
			     null, null);
	    }

	    list ($ret, $path) = $item->fetchPath();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    /*
	     * The parent must be read locked at this point to make sure that it's
	     * not going to be moved around while we're copying a file to its directory.
	     */
	    list ($ret, $lockIds[]) = GalleryCoreApi::acquireReadLockParents($item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    list ($ret, $lockIds[]) = GalleryCoreApi::acquireWriteLock($item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    /* take backup first */
	    $tmpfname = $platform->tempnam($gallery->getConfig('data.gallery.tmp'), 'tmp_');
	    if (!$tmpfname) {
		GalleryCoreApi::releaseLocks($lockIds);
		return array(GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__),
			     null, null);
	    }
	    if (!$platform->copy($path, $tmpfname)) {
		$platform->unlink($tmpfname);
		GalleryCoreApi::releaseLocks($lockIds);
		return array(GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__),
			     null, null);
	    }
	    if (!$platform->copy($inputFileName, $path)) {
		$platform->unlink($tmpfname);
		GalleryCoreApi::releaseLocks($lockIds);
		return array(GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__),
			     null, null);
	    }

	    $ret = $item->rescan();
	    if ($ret) {
		if ($gallery->getDebug()) {
		    $gallery->debug('Failed to rescan item after reupload. ' .
		    'This is the error stack trace: ' . $ret->getAsText());
		}
		$platform->copy($tmpfname, $path);
		$platform->unlink($tmpfname);
		GalleryCoreApi::releaseLocks($lockIds);
		$error[] = 'form[error][reupload][toolkit]';
		return array(null, $error, $status);
	    }

	    $ret = $item->save();
	    if ($ret) {
		$platform->copy($tmpfname, $path);
		$platform->unlink($tmpfname);
		GalleryCoreApi::releaseLocks($lockIds);
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    $platform->unlink($tmpfname);
	    $ret = GalleryCoreApi::releaseLocks($lockIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    $ret = GalleryCoreApi::invalidateDerivativeDimensionsBySourceIds(
		array($item->getId()));
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    $status[] = $module->translate('Reuploaded file successfully.');
	} else {
	    /*
	     * The only way to reupload the file is through the browser,
	     * which leaves out those who wants to reupload larger files
	     */
	    if (!empty($form['name']['reupload'])) {
		/* if we tried and failed to upload the file */
		$error[] = 'form[error][reupload][failure]';
	    }
	}
	return array(null, $error, $status);
    }

    /**
     * @see ItemEditOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'reupload');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($template->hasVariable('ItemAdmin')) {
	    $ItemAdmin =& $template->getVariableByReference('ItemAdmin');
	    $ItemAdmin['enctype'] = 'multipart/form-data';
	} else {
	    $ItemAdmin = array('enctype' => 'multipart/form-data');
	    $template->setVariable('ItemAdmin', $ItemAdmin);
	}

	$fileUploadsBool = GalleryUtilities::getPhpIniBool('file_uploads');

	$maxFileSize = ini_get('upload_max_filesize');
	if (preg_match("/(\d+)M/", $maxFileSize, $matches)) {
	    $maxFileSize = $matches[1] * 1024 * 1024;
	}

	if ($maxFileSize >= 1024 * 1024) {
	    $maxFileSize = $module->translate(array('one' => '%d megabyte',
						    'many' => '%d megabytes',
						    'count' => (int)($maxFileSize / (1024 * 1024)),
						    'arg1' => (int)($maxFileSize / (1024 * 1024))));
	} else if ($maxFileSize >= 1024) {
	    $maxFileSize = $module->translate(array('one' => '%d kilobytes',
						    'many' => '%d kilobytes',
						    'count' => (int)($maxFileSize / (1024)),
						    'arg1' => (int)($maxFileSize / (1024))));
	}

	$template->setVariable('ReuploadPhotoOption',
		array('maxFileSize' => $maxFileSize,
		      'uploadsPermitted' => $fileUploadsBool));


	return array(null,
		     'modules/reupload/templates/ReuploadPhotoOption.tpl',
		     'modules_reupload');
    }

    /**
     * @see ItemEditOption::isAppropriate
     */
    function isAppropriate($item, $thumbnail) {
	return array(null,
		     GalleryUtilities::isA($item, 'GalleryPhotoItem'));
    }
}
?>
