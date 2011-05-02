<?php
/*
 * $RCSfile: RandomHighlightSiteAdmin.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/01/17 01:52:03 $
 * @package RandomHighlight
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package RandomHighlight
 * @subpackage UserInterface
 */
class RandomHighlightSiteAdminController extends GalleryController {

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
	    if (isset($form['duration'])) {
		/* Accept input for locales that use comma as fraction separator */
		$form['duration'] = str_replace(',', '.', $form['duration']);
	    }
	    if (!isset($form['duration']) || !is_numeric($form['duration'])) {
		$error[] = 'form[error][duration]';
	    } else {
		$ret = GalleryCoreApi::setPluginParameter(
		    'module', 'randomhighlight', 'duration', round($form['duration'] * 60));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$status['saved'] = 1;
	    }
	}

	/* Figure out where to redirect upon success */
	$method = empty($error) ? 'redirect' : 'delegate';
	$results[$method]['view'] = 'core.SiteAdmin';
	$results[$method]['subView'] = 'randomhighlight.RandomHighlightSiteAdmin';
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package RandomHighlight
 * @subpackage UserInterface
 */
class RandomHighlightSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'RandomHighlightSiteAdmin') {
	    $form['formName'] = 'RandomHighlightSiteAdmin';
	    list ($ret, $duration) =
		GalleryCoreApi::getPluginParameter('module', 'randomhighlight', 'duration');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    /* String conversion here may use comma as fraction separator, based on locale */
	    $form['duration'] = (string)round($duration / 60, 2);
	}

	$template->setVariable('controller', 'randomhighlight.RandomHighlightSiteAdmin');
	return array(null, array('body' =>
		     'modules/randomhighlight/templates/RandomHighlightSiteAdmin.tpl'));
    }
}
?>
