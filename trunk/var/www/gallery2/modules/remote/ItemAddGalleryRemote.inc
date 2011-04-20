<?php
/*
 * $RCSfile: ItemAddGalleryRemote.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/13 03:39:49 $
 * @package Remote
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This ItemAdd plugin adds items uploaded from the web browser.
 *
 * @package Remote
 * @subpackage UserInterface
 */
class ItemAddGalleryRemote extends ItemAddPlugin {

    /**
     * @see ItemAdd:loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	if ($form['formName'] != 'ItemAddGalleryRemote') {
	    $form['formName'] = 'ItemAddGalleryRemote';
	}

	$ItemAddGalleryRemote = array();
	$template->setVariable('ItemAddGalleryRemote', $ItemAddGalleryRemote);

	return array(null,
		     'modules/remote/templates/ItemAddGalleryRemote.tpl',
		     'modules_remote');
    }

    /**
     * @see ItemAddPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'remote');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Gallery Remote'));
    }

    /**
     * @see ItemAddPlugin::isAppropriate
     */
    function isAppropriate() {
	list ($ret, $showItemAdd) =
	    GalleryCoreApi::getPluginParameter('module', 'remote', 'showItemAdd');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $showItemAdd);
    }
}
?>
