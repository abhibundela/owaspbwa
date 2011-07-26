<?php
/*
 * $RCSfile: SetSizeOption.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/10 04:42:42 $
 * @package SizeLimit
 * @subpackage UserInterface
 * @author Felix Rabinovich <felix@rabinovich.org>
 */

/**
 * This ItemAddOption uses the size limit values and resizes
 * gallery item when the image is uploaded.
 *
 * @package SizeLimit
 * @subpackage UserInterface
 */
GalleryCoreApi::requireOnce('modules/sizelimit/classes/SizeLimitHelper.class');

class SetSizeOption extends ItemAddOption {

    /**
     * @see ItemAddOption::isAppropriate
     */
    function isAppropriate() {
	return array(null, true);
    }

    /**
     * @see ItemAddOption::handleRequestAfterAdd
     */
    function handleRequestAfterAdd($form, $items) {
	$errors = array();
	$warnings = array();
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'sizelimit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	for ($i = 0; $i < count($items); $i++) {
	    $warnings[$i] = array();
	    if (!GalleryUtilities::isA($items[$i], 'GalleryDataItem')) {
		continue;
	    }

	    list ($ret, $param) = GalleryCoreApi::fetchAllPluginParameters(
		'module', 'sizelimit', $items[$i]->getParentId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    $param = array_merge(
		array('keepOriginal' => 0, 'width' => 0, 'height' => 0, 'size' => 0), $param);

	    if ($param['width'] && $param['height'] && method_exists($items[$i], 'getwidth') &&
		    method_exists($items[$i], 'getheight') &&
		    ($items[$i]->getWidth() > $param['width'] ||
		     $items[$i]->getHeight() > $param['height'])) {
		$args = array($param['width'], $param['height']);
		if ($param['keepOriginal']) {
		    $ret = SizeLimitHelper::buildDerivativeWithLimits($items[$i], 'scale', $args);
		} else {
		    $ret = SizeLimitHelper::applyLimits($items[$i], 'scale', $args);
		}
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_UNSUPPORTED_FILE_TYPE) {
			$warnings[$i][] = $module->translate(
			    array('text' => 'WARNING: Cannot resize mime type %s',
				  'arg1' => $items[$i]->getMimeType()));
		    } else {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		}
	    }

	    if (isset($param['size']) && $param['size'] > 0 &&
		    ($items[$i]->getSize() >> 10) > $param['size']) {
		$args = array($param['size']);
		if ($param['keepOriginal']) {
		    $ret = SizeLimitHelper::buildDerivativeWithLimits($items[$i], 'compress',
								      $args);
		} else {
		    $ret = SizeLimitHelper::applyLimits($items[$i], 'compress', $args);
		}
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_UNSUPPORTED_FILE_TYPE) {
			$warnings[$i][] = $module->translate(
			    array('text' => 'WARNING: Cannot compress mime type %s',
				  'arg1' => $items[$i]->getMimeType()));
		    } else {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		}
	    }
	}
	return array(null, $errors, $warnings);
    }
}
?>
