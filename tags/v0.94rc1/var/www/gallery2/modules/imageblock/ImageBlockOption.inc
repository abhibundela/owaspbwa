<?php
/*
 * $RCSfile: ImageBlockOption.inc,v $
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
 * @version $Revision: 1.7 $ $Date: 2006/01/10 04:40:16 $
 * @package ImageBlock
 * @author Jay Rossiter <cryptographite@users.sf.net>
 */

/**
 * @package ImageBlock
 * @subpackage UserInterface
 */
class ImageBlockOption extends ItemEditOption {

    /**
     * @see ItemEditOption::handleRequestAfterEdit
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	$error = array();
	$warning = array();

	GalleryCoreApi::requireOnce('modules/imageblock/classes/ImageBlockHelper.class');

	if (empty($form['ImageBlockOption']['setDisabled']) ||
		$form['ImageBlockOption']['setDisabled'] == 'off') {
	    $setDisabled = false;
	} else {
	    $setDisabled = true;
	}

	/*
	 *  We default to 'on' in the template, but checking empty() isn't valid
	 *  because 'off' doesn't necessarily send a value.
	 */
	if (isset($form['ImageBlockOption']['setRecursive']) &&
		$form['ImageBlockOption']['setRecursive'] == 'on') {
	    $setRecursive = true;
	} else {
	    $setRecursive = false;
	}

	list ($ret, $currentlyDisabled) = ImageBlockHelper::getDisabledFlag($item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($setDisabled != $currentlyDisabled) {
	    $ret = ImageBlockHelper::setDisabledFlag($item, $setRecursive, $setDisabled);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}
	return array(null, $error, $warning);
    }

    /**
     * @see ItemEditOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	GalleryCoreApi::requireOnce('modules/imageblock/classes/ImageBlockHelper.class');
	list($ret, $form['ImageBlockOption']['setDisabled']) =
	    ImageBlockHelper::getDisabledFlag($item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	return array(null,
		     'modules/imageblock/templates/ImageBlockOption.tpl',
		     'modules_imageblock');
    }

    /**
     * @see ItemEditOption::isSupported
     */
    function isAppropriate($item, $thumbnail) {
	return array(null, GalleryUtilities::isA($item, 'GalleryAlbumItem'));
    }
}
?>
