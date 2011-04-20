<?php
/*
 * $RCSfile: PanoramaOption.inc,v $
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
 * @version $Revision: 1.7 $ $Date: 2006/01/10 04:41:16 $
 * @package Panorama
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This ItemEditOption will handle switching between Photo and Panorama types
 *
 * @package Panorama
 * @subpackage UserInterface
 *
 */
class PanoramaOption extends ItemEditOption {

    /**
     * @see ItemEditOption::isAppropriate
     */
    function isAppropriate($item, $thumbnail) {
	if (!GalleryUtilities::isExactlyA($item, 'GalleryPhotoItem') &&
		!GalleryUtilities::isExactlyA($item, 'PanoramaPhotoItem')) {
	    return array(null, false);
	}
	list ($ret, $param) = GalleryCoreApi::fetchAllPluginParameters('module', 'panorama');
	if ($ret || !$param['itemType']) {
	    return array(null, false);
	}
	list ($ret, $preferred) = GalleryCoreApi::fetchPreferredsByItemIds(array($item->getId()));
	if ($ret) {
	    return array(null, false);
	}
	$width = empty($preferred)
	    ? $item->getWidth() : $preferred[$item->getId()]->getWidth();
	$mimeType = empty($preferred)
	    ? $item->getMimeType() : $preferred[$item->getId()]->getMimeType();
	return array(null, $width > $param['width'] &&
		     ($mimeType == 'image/jpeg' || $mimeType == 'image/gif'));
    }

    /**
     * @see ItemEditOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	list ($ret, $preferred) = GalleryCoreApi::fetchPreferredsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	$form['PanoramaOption']['isPanorama'] = empty($preferred)
	    ? GalleryUtilities::isA($item, 'PanoramaPhotoItem')
	    : GalleryUtilities::isA($preferred[$item->getId()], 'PanoramaDerivativeImage');
	return array(null,
		     'modules/panorama/templates/PanoramaOption.tpl',
		     'modules_panorama');
    }

    /**
     * @see ItemEditOption::handleRequestAfterEdit
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	GalleryCoreApi::requireOnce('modules/panorama/classes/PanoramaHelper.class');

	$isPanorama = isset($form['PanoramaOption']['isPanorama']) &&
	    $form['PanoramaOption']['isPanorama'];
	$ret = PanoramaHelper::assignEntityType($item, $isPanorama);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if (isset($preferred) && GalleryUtilities::isA($preferred, 'GalleryDerivativeImage')) {
	    $ret = PanoramaHelper::assignEntityType($preferred, $isPanorama);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}

	return array(null, array(), array());
    }
}
?>
