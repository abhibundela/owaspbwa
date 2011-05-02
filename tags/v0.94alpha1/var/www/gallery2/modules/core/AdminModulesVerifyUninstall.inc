<?php
/*
 * $RCSfile: AdminModulesVerifyUninstall.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/02/24 20:44:18 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will perform a module uninstall, if requested and confirmed.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminModulesVerifyUninstallController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = array();
	$moduleId = GalleryUtilities::getRequestVariables('moduleId');

	if (!empty($moduleId) && isset($form['action']['uninstall'])) {
	    list ($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $isActive) = $module->isActive();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (!$isActive) {
		$ret = $module->uninstall();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if (empty($redirect)) {
		    $redirect['view'] = 'core.SiteAdmin';
		    $redirect['subView'] = 'core.AdminModules';
		}
		$status['uninstalled'] = $module->translate($module->getName());
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.SiteAdmin';
	    $results['delegate']['subView'] = 'core.AdminModules';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will prompt for confirmation when uninstalling a module
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class AdminModulesVerifyUninstallView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$moduleId = GalleryUtilities::getRequestVariables('moduleId');
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'AdminModulesVerifyUninstall') {
	    /* We have no form variables */
	    $form['formName'] = 'AdminModulesVerifyUninstall';
	    $form['moduleId'] = $moduleId;
	}

	$moduleInfo = array('id' => $module->getId(),
			    'name' => $module->translate($module->getName()),
			    'description' => $module->translate($module->getDescription()));
	$AdminModulesVerifyUninstall = array();
	$AdminModulesVerifyUninstall['module'] = $moduleInfo;
	$template->setVariable('AdminModulesVerifyUninstall', $AdminModulesVerifyUninstall);
	$template->setVariable('controller', 'core.AdminModulesVerifyUninstall');

	return array(null,
		     array('body' => 'modules/core/templates/AdminModulesVerifyUninstall.tpl'));
    }
}
?>
