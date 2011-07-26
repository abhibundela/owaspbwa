<?php
/*
 * $RCSfile: CantActivate.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/01/10 04:40:52 $
 * @package MultiLang
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This controller will handle the setting of MultiLang module options
 *
 * @package MultiLang
 * @subpackage UserInterface
 */
class CantActivateController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	/* Figure out where to redirect upon success */
	$results['redirect']['view'] = 'core.SiteAdmin';
	$results['redirect']['subView'] = 'core.AdminModules';
	$results['status'] = array();
	$results['error'] = array();

	return array(null, $results);
    }
}

/**
 * This view will complain about missing gettext
 *
 * @package MultiLang
 * @subpackage UserInterface
 */
class CantActivateView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	$translator =& $gallery->getTranslator();

	if ($form['formName'] != 'CantActivate') {
	    $form['formName'] = 'CantActivate';
	}

	$template->setVariable('controller', 'multilang.CantActivate');
	return array(null,
		     array('body' => 'modules/multilang/templates/CantActivate.tpl'));
    }
}
?>
