<?php
/*
 * $RCSfile: SquareThumbSiteAdmin.inc,v $
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
 * @version $Revision: 1.4 $ $Date: 2006/01/17 01:52:04 $
 * @package SquareThumb
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package SquareThumb
 * @subpackage UserInterface
 */
class SquareThumbSiteAdminController extends GalleryController {

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
	    if (empty($form['color'])) {
		$error[] = 'form[error][color][missing]';
	    } else if (!preg_match('/^[0-9a-f]{6}$/i', $form['color'])) {
		$error[] = 'form[error][color][invalid]';
	    } else {
		foreach (array('mode', 'color') as $key) {
		    $ret = GalleryCoreApi::setPluginParameter('module', 'squarethumb',
							      $key, $form[$key]);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
		$status['saved'] = 1;
	    }
	} /* else $form['action']['reset'] */

	$method = empty($error) ? 'redirect' : 'delegate';
	$results = array($method => array('view' => 'core.SiteAdmin',
					  'subView' => 'squarethumb.SquareThumbSiteAdmin'),
			 'status' => $status,
			 'error' => $error);

	return array(null, $results);
    }
}

/**
 * @package SquareThumb
 * @subpackage UserInterface
 */
class SquareThumbSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'SquareThumbSiteAdmin') {
	    $form['formName'] = 'SquareThumbSiteAdmin';
	    foreach (array('mode', 'color') as $key) {
		list ($ret, $form[$key]) =
		    GalleryCoreApi::getPluginParameter('module', 'squarethumb', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}

	$template->setVariable('controller', 'squarethumb.SquareThumbSiteAdmin');
	return array(null,
		     array('body' => 'modules/squarethumb/templates/SquareThumbSiteAdmin.tpl'));
    }
}
?>
