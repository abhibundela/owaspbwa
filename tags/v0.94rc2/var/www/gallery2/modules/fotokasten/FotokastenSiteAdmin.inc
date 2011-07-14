<?php
/*
 * $RCSfile: FotokastenSiteAdmin.inc,v $
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
 * @version $Revision: 1.1 $ $Date: 2006/02/06 18:09:40 $
 * @package Fotokasten
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package Fotokasten
 * @subpackage UserInterface
 */
class FotokastenSiteAdminController extends GalleryController {

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
	    if (empty($form['affiliateId']) || !is_numeric($form['affiliateId'])) {
		$error[] = 'form[error][affiliateId]';
	    }
	    if (empty($form['affiliateIdPass'])) {
		$error[] = 'form[error][affiliateIdPass]';
	    }
	    if (empty($error)) {
		foreach (array('affiliateId', 'affiliateIdPass') as $key) {
		    $ret = GalleryCoreApi::setPluginParameter(
			    'module', 'fotokasten', $key, $form[$key]);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
		$status['saved'] = 1;
	    }
	}

	$method = empty($error) ? 'redirect' : 'delegate';
	$results[$method]['view'] = 'core.SiteAdmin';
	$results[$method]['subView'] = 'fotokasten.FotokastenSiteAdmin';
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Fotokasten
 * @subpackage UserInterface
 */
class FotokastenSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'FotokastenSiteAdmin') {
	    $form['formName'] = 'FotokastenSiteAdmin';
	    foreach (array('affiliateId', 'affiliateIdPass') as $key) {
		list ($ret, $form[$key]) =
		    GalleryCoreApi::getPluginParameter('module', 'fotokasten', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}

	$template->setVariable('controller', 'fotokasten.FotokastenSiteAdmin');
	return array(null,
		     array('body' => 'modules/fotokasten/templates/FotokastenSiteAdmin.tpl'));
    }
}
?>
