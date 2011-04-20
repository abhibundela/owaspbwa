<?php
/*
 * $RCSfile: UserWatermarkEdit.inc,v $
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
 * @version $Revision: 1.9 $ $Date: 2006/01/10 04:43:26 $
 * @package Watermark
 * @author Bharat Mediratta <bharat@menalto.com>
 */

GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class UserWatermarkEditController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	list ($ret, $results) = WatermarkHelper::handleEditRequest($form,
	    'core.UserAdmin', 'watermark.UserWatermarks', 'watermark.UserWatermarkEdit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null, $results);
    }
}

/**
 * @package Watermark
 * @subpackage UserInterface
 */
class UserWatermarkEditView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = WatermarkHelper::loadEditTemplate($template, $form, 'UserWatermarkEdit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null,
		     array('body' => 'modules/watermark/templates/UserWatermarkEdit.tpl'));
    }
}
?>
