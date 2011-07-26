<?php
/*
 * $RCSfile: ItemAddWebCam.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/01/10 04:43:34 $
 * @package WebCam
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package WebCam
 * @subpackage UserInterface
 */
class ItemAddWebCam extends ItemAddPlugin {

    /**
     * @see ItemAddPlugin::handleRequest
     */
    function handleRequest($form, &$item) {
	global $gallery;

	$status = array();
	$error = array();
	if (isset($form['action']['checkUrl'])) {
	    /* Delegate back to the same view */
	} else if (isset($form['action']['addWebCam'])) {
	    /* fetch url, create item, set WebCam handler, save params */
	    $platform =& $gallery->getPlatform();
	    $tmpDir = $gallery->getConfig('data.gallery.tmp');
	    $tmpFile = $platform->tempnam($tmpDir, 'webcam');
	    $url = GalleryUtilities::htmlEntityDecode($form['imageUrl']);

	    list ($successfullyCopied, $response, $headers) =
		GalleryCoreApi::fetchWebFile($url, $tmpFile);
	    if (!$successfullyCopied) {
		$error[] = 'form[error][imageUrl][unavailable]';
	    }

	    if (!empty($headers['Content-Type'])) {
		$mimeType = $headers['Content-Type'];
	    } else if (!empty($headers['Content-type'])) {
		$mimeType = $headers['Content-type'];
	    } else {
		list ($base, $extension) = GalleryUtilities::getFileNameComponents(basename($url));
		list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
		if ($ret) {
		    $mimeType = 'application/unknown';
		}
	    }

	    if (empty($error)) {
		list ($ret, $newItem) = GalleryCoreApi::addItemToAlbum(
		    $tmpFile, basename($url), basename($url), '', '', $mimeType, $item->getId());
		@$platform->unlink($tmpFile);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}

		if (GalleryUtilities::isA($newItem, 'GalleryPhotoItem')) {
		    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($newItem->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $newItem->addOnLoadHandler('WebCam');
		    $ret = $newItem->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $ret = GalleryCoreApi::setPluginParameter('module', 'webcam',
							      'imageUrl', $url, $newItem->getId());
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $ret = GalleryCoreApi::releaseLocks($lockId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    $status['addedFiles'][] = array('fileName' => basename($form['imageUrl']),
						    'id' => $newItem->getId(),
						    'warnings' => array());
		} else {
		    $ret = GalleryCoreApi::deleteEntityById($newItem->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    $error[] = 'form[error][imageUrl][notImage]';
		}
	    } else {
		@$platform->unlink($tmpFile);
	    }
	}

	return array(null, $error, $status);
    }

    /**
     * @see ItemAdd:loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	global $gallery;

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'webcam');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($form['formName'] != 'ItemAddWebCam') {
	    $form['formName'] = 'ItemAddWebCam';
	    $form['imageUrl'] = '';
	}

	if (isset($form['action']['checkUrl'])) {
	    if (empty($form['imageUrl'])) {
		$form['error']['imageUrl']['missing'] = 1;
	    } else {
		$baseUrlComponents = parse_url($form['imageUrl']);
		if (empty($baseUrlComponents['scheme']) || $baseUrlComponents['scheme'] != 'http') {
		    $form['error']['imageUrl']['invalid'] = 1;
		}
	    }
	}

	return array(null,
		     'modules/webcam/templates/ItemAddWebCam.tpl', 'modules_webcam');
    }

    /**
     * @see ItemAddPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'webcam');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('WebCam/Live Image'));
    }
}
?>
