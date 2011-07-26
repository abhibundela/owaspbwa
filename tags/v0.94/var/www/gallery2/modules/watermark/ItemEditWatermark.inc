<?php
/*
 * $RCSfile: ItemEditWatermark.inc,v $
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
 * @version $Revision: 1.15 $ $Date: 2006/01/13 06:50:03 $
 * @package Watermark
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This edit plugin allows you to customize the watermark for an image
 *
 * @package Watermark
 * @subpackage UserInterface
 *
 */
class ItemEditWatermark extends ItemEditPlugin {

    /**
     * @see ItemEditPlugin::handleRequest
     */
    function handleRequest($form, &$item, &$preferred) {
	global $gallery;

	$status = null;
	$error = array();

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'watermark');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	}

	if (isset($form['action']['save'])) {
	    if (!(isset($form['whichDerivative']['thumbnail']) ||
		  isset($form['whichDerivative']['resize']) ||
		  isset($form['whichDerivative']['preferred']))) {
		$error[] = 'form[error][versions][missing]';
	    }

	    if (empty($error)) {
		list ($ret, $watermark) = GalleryCoreApi::loadEntitiesById($form['watermarkId']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		GalleryCoreApi::requireOnce(
		    'modules/watermark/classes/WatermarkHelper.class');
		$ret = WatermarkHelper::watermarkItem(
		    $watermark, $item, $form['xPercent'], $form['yPercent'],
		    array(DERIVATIVE_TYPE_IMAGE_PREFERRED => isset(
			      $form['whichDerivative']['preferred']),
			  DERIVATIVE_TYPE_IMAGE_RESIZE => isset(
			      $form['whichDerivative']['resize']),
			  DERIVATIVE_TYPE_IMAGE_THUMBNAIL => isset(
			      $form['whichDerivative']['thumbnail'])));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
		$status = $module->translate('Watermark applied successfully');
	    }
	} else if (isset($form['action']['remove'])) {
	    list ($ret, $derivativeTable) =
		GalleryCoreApi::fetchDerivativesByItemIds(array($item->getId()));
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	    }

	    if (isset($derivativeTable[$item->getId()])) {
		$derivatives = $derivativeTable[$item->getId()];

		$ids = array();
		foreach ($derivatives as $derivative) {
		    $ids[] = $derivative->getId();
		}

		/* Lock them all */
		list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($ids);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		foreach ($derivatives as $derivative) {
		    list ($ret, $derivative) = $derivative->refresh();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $newPostFilters = GalleryCoreApi::removeDerivativeOperation(
			'composite', $derivative->getPostFilterOperations());
		    $derivative->setPostFilterOperations($newPostFilters);

		    if ($derivative->getDerivativeType() == DERIVATIVE_TYPE_IMAGE_PREFERRED) {
			$preferred = $derivative;
		    }

		    $ret = $derivative->save();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}


		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
	    }

	    if (isset($preferred)) {
		list ($ret, $preferred) = $preferred->refresh();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		if ($preferred->hasNoOperations()) {
		    $ret = GalleryCoreApi::remapSourceIds(
			$preferred->getId(), $preferred->getDerivativeSourceId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $ret = GalleryCoreApi::deleteEntityById($preferred->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}
	    }
	    $status = $module->translate('Watermark removed successfully');
	}

	return array(null, $error, $status, false);
    }

    /**
     * @see ItemEditPlugin::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	global $gallery;

	$ItemEditWatermark = array();
	$ItemEditWatermark['showApplet'] = $thumbnail != null;

	list ($ret, $ItemEditWatermark['isAdmin']) = GalleryCoreApi::isUserInSiteAdminGroup();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Load up all watermarks */
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');
	list ($ret, $watermarks) = WatermarkHelper::fetchWatermarks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if (!empty($watermarks)) {
	    foreach (array_keys($watermarks) as $watermarkId) {
		$watermarks[$watermarkId] = (array)$watermarks[$watermarkId];
	    }
	}

	if ($form['formName'] != 'ItemEditWatermark') {
	    /* First time around, reset the form */
	    $form['formName'] = 'ItemEditWatermark';

	    if (!empty($watermarks)) {
		/* Get the current watermark from any of the derivatives */
		list ($ret, $derivatives) =
		    GalleryCoreApi::fetchDerivativesByItemIds(array($item->getId()));
		if (isset($derivatives[$item->getId()])) {
		    foreach ($derivatives[$item->getId()] as $derivative) {
			$ops = $derivative->getPostFilterOperations();
			if (preg_match('{composite\|plugins_data/modules/watermark/([^;]*)}',
				       $ops, $matches)) {
			    switch ($derivative->getDerivativeType()) {
			    case DERIVATIVE_TYPE_IMAGE_PREFERRED:
				$form['whichDerivative']['preferred'] = 1;
				break;

			    case DERIVATIVE_TYPE_IMAGE_RESIZE:
				$form['whichDerivative']['resize'] = 1;
				break;

			    case DERIVATIVE_TYPE_IMAGE_THUMBNAIL:
				$form['whichDerivative']['thumbnail'] = 1;
				break;
			    }

			    $args = preg_split('/,/', $matches[1]);
			    $fileName = $args[0];
			    $form['xPercent'] = $args[5];
			    $form['yPercent'] = $args[6];
			    foreach ($watermarks as $watermark) {
				if ($watermark['fileName'] == $fileName) {
				    $form['watermarkId'] = $watermark['id'];
				    break;
				}
			    }
			}
		    }
		}

		if (!isset($form['watermarkId'])) {
		    $watermarkIds = array_keys($watermarks);
		    $form['watermarkId'] = $watermarkIds[0];
		    $form['xPercent'] = $watermarks[$watermarkIds[0]]['xPercentage'];
		    $form['yPercent'] = $watermarks[$watermarkIds[0]]['yPercentage'];
		}
	    }
	}


	/* Grab the smallest derivative that's greater than 400px on a side */
	list ($ret, $derivatives) =
	    GalleryCoreApi::fetchDerivativesByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	$smallestSize = null;
	$displayDerivative = null;
	if (isset($derivatives[$item->getId()])) {
	    foreach ($derivatives[$item->getId()] as $derivative) {
		if ($derivative->getWidth() >= 400 || $derivative->getHeight() >= 400) {
		    if (!isset($smallestSize) || $derivative->getDerivativeSize() < $smallestSize) {
			$displayDerivative = $derivative;
			$smallestSize = $derivative->getDerivativeSize();
		    }
		}
	    }
	}

	$ItemEditWatermark = array();
	$ItemEditWatermark['watermarks'] = $watermarks;
	$ItemEditWatermark['item'] = (array)$item;
	$ItemEditWatermark['derivative'] =
	    isset($displayDerivative) ? (array)$displayDerivative : null;

	$template->setVariable('ItemEditWatermark', $ItemEditWatermark);
	$template->setVariable('controller', 'core.ItemEditWatermark');
	return array(null,
		     'modules/watermark/templates/ItemEditWatermark.tpl', 'modules_watermark');
    }

    /**
     * @see ItemEditPlugin::isSupported
     * @TODO: Broaden this interface to allow a GalleryStatus return code
     */
    function isSupported($item, $thumbnail) {
	if (!GalleryUtilities::isA($item, 'GalleryDataItem')) {
	    return false;
	}

	list ($ret, $toolkit) =
	    GalleryCoreApi::getToolkitByOperation($item->getMimeType(), 'composite');
	if ($ret) {
	    return false; /* Swallow the error for now */
	}
	if (isset($toolkit)) {
	    return true;
	}

	/* Ok we have no composite for the item .. but maybe we do for its preferred? */
	list ($ret, $preferredTable) =
	    GalleryCoreApi::fetchPreferredsByItemIds(array($item->getId()));
	if ($ret) {
	    return false; /* Swallow the error for now */
	}

	if (!empty($preferredTable[$item->getId()])) {
	    $preferred = $preferredTable[$item->getId()];
	    list ($ret, $toolkit) =
		GalleryCoreApi::getToolkitByOperation($preferred->getMimeType(), 'composite');
	    if ($ret) {
		return false; /* Swallow the error for now */
	    }
	    if (isset($toolkit)) {
		return true;
	    }
	}

	return false;
    }

    /**
     * @see ItemEditPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'watermark');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Watermark'));
    }
}
?>
