<?php
/*
 * $RCSfile: Panorama.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/18 01:39:22 $
 * @package Panorama
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package Panorama
 * @subpackage UserInterface
 */
class PanoramaView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	GalleryCoreApi::requireOnce('modules/panorama/classes/PanoramaHelper.class');

	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $image) = PanoramaHelper::fetchViewableImages(array($item));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (empty($image)) {
	    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	}
	$image = $image[$item->getId()];

	list ($ret, $viewWidth) = GalleryCoreApi::getPluginParameter('module', 'panorama', 'width');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$urlGenerator =& $gallery->getUrlGenerator();
	$src = $urlGenerator->generateUrl(
	    array('view' => 'core.DownloadItem', 'itemId' => $image->getId(),
		  'serialNumber' => $image->getSerialNumber()),
	    array('forceSessionId' => true, 'forceFullUrl' => true));

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'panorama');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$Panorama['item'] = (array)$item;
	$Panorama['image'] = (array)$image;
	$Panorama['imageUrl'] = $src;
	$Panorama['width'] = $viewWidth;
	$Panorama['moduleUrl'] =
	    GalleryUtilities::convertPathToUrl(dirname(__FILE__), array('forceFullUrl' => true));

	$template->setVariable('Panorama', $Panorama);
	$template->title($module->translate('Panorama'));
	return array(null,
	    array('body' => 'modules/panorama/templates/Panorama.tpl'));
    }
}
?>
