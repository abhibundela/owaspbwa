<?php
/*
 * $RCSfile: WatermarkOption.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/10 04:43:26 $
 * @package Watermark
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This ItemAddOption allows the user to pick a watermark to apply
 * to the image upon upload.
 *
 * @package Watermark
 * @subpackage UserInterface
 */
class WatermarkOption extends ItemAddOption {

    /**
     * @see ItemAddOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	list ($ret, $watermarks) = WatermarkHelper::fetchWatermarks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	$currentId = null;
	foreach (array_keys($watermarks) as $id) {
	    if (!isset($currentId)) {
		$currentId = $id;
	    }
	    $watermarks[$id] = (array)$watermarks[$id];
	}

	$WatermarkOption = array();
	$WatermarkOption['watermarks'] = $watermarks;
	$WatermarkOption['currentId'] = $currentId;
	$template->setVariable('WatermarkOption', $WatermarkOption);
	return array(null,
		     'modules/watermark/templates/WatermarkOption.tpl',
		     'modules_watermark');
    }

    /**
     * @see ItemAddOption::isAppropriate
     */
    function isAppropriate() {
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	/* Only appropriate if we've got some watermarks to show */
	list ($ret, $watermarks) = WatermarkHelper::fetchWatermarks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, !empty($watermarks));
    }

    /**
     * @see ItemAddOption::handleRequestAfterAdd
     */
    function handleRequestAfterAdd($form, $items) {
	$errors = array();
	$warnings = array();
	for ($i = 0; $i < count($items); $i++) {
	    $warnings[$i] = array();
	    if (!empty($form['WatermarkOption']['watermarkId']) &&
		GalleryUtilities::isA($items[$i], 'GalleryPhotoItem')) {
		list ($ret, $watermark) =
		    GalleryCoreApi::loadEntitiesById($form['WatermarkOption']['watermarkId']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}
		$watermarkArray =
		    array(DERIVATIVE_TYPE_IMAGE_THUMBNAIL => $watermark->getApplyToThumbnail(),
			  DERIVATIVE_TYPE_IMAGE_RESIZE => $watermark->getApplyToResizes(),
			  DERIVATIVE_TYPE_IMAGE_PREFERRED => $watermark->getApplyToPreferred());
		$ret = WatermarkHelper::watermarkItem($watermark, $items[$i],
						      $watermark->getXPercentage(),
						      $watermark->getYPercentage(),
						      $watermarkArray);
	    	if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}
	    }
	}
	return array(null, $errors, $warnings);
    }
}
?>
