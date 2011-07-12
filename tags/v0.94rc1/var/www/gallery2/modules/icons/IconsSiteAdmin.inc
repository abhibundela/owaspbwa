<?php
/*
 * $RCSfile: IconsSiteAdmin.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/01/17 01:52:01 $
 * @package Icons
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package Icons
 * @subpackage UserInterface
 */
class IconsSiteAdminController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = array();
	if (isset($form['action']['save'])) {
	    if (!isset($form['iconpack'])) {
		$error[] = 'form[error][iconpack][missing]';
	    } else {
		$ret = GalleryCoreApi::setPluginParameter(
		    'module', 'icons', 'iconpack', $form['iconpack']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$status['saved'] = 1;
	    }
	}

	/* Figure out where to redirect upon success */
	$method = empty($error) ? 'redirect' : 'delegate';
	$results[$method]['view'] = 'core.SiteAdmin';
	$results[$method]['subView'] = 'icons.IconsSiteAdmin';
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Icons
 * @subpackage UserInterface
 */
class IconsSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'IconsSiteAdmin') {
	    $form['formName'] = 'IconsSiteAdmin';
	    list ($ret, $form['iconpack']) =
		GalleryCoreApi::getPluginParameter('module', 'icons', 'iconpack');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	list ($ret, $icons) = GalleryCoreApi::newFactoryInstance('IconsInterface_1_0');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (isset($icons)) {
	    list ($ret, $list) = $icons->getIconPacks();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $template->setVariable('IconsSiteAdmin', array('iconpacks' => $list));
	}

	$template->setVariable('controller', 'icons.IconsSiteAdmin');
	return array(null,
		     array('body' => 'modules/icons/templates/IconsSiteAdmin.tpl'));
    }
}
?>
