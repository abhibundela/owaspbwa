<?php
/*
 * $RCSfile: CaptchaConfigAdmin.inc,v $
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
 * @version $Revision: 1.9 $ $Date: 2006/01/30 18:25:52 $
 * @package Captcha
 * @author Stefan Ioachim <stefanioachim@gmail.com>
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * @package Captcha
 * @subpackage UserInterface
 */
class CaptchaConfigAdminController extends GalleryController {
    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$results['status'] = $results['error'] = array();
	$results['redirect'] = array('view' => 'core.SiteAdmin', 'subView' => 'core.AdminModules');
	return array(null, $results);
    }
}


/**
 * Show the user which required GD functions are missing.  They can't install the module, and
 * there's nothing we can do about it.
 */
class CaptchaConfigAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'CaptchaConfigAdmin') {
	    $form['formName'] = 'CaptchaConfigAdmin';
	}

	GalleryCoreApi::requireOnce('modules/captcha/classes/CaptchaHelper.class');
	$gdReport = CaptchaHelper::testRequiredGdFunctions();

	$template->setVariable('CaptchaConfigAdmin', array('gdReport' => $gdReport));
	$template->setVariable('controller', 'captcha.CaptchaConfigAdmin');
	return array(null,
		     array('body' => 'modules/captcha/templates/CaptchaConfigAdmin.tpl'));
    }
}
?>
