<?php
/*
 * $RCSfile: RatingSiteAdmin.inc,v $
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
 * @version $Revision: 1.4 $ $Date: 2006/01/17 01:52:03 $
 * @package Rating
 * @author  Don Seiler <don@seiler.us>
 */

/**
 * @package Rating
 * @subpackage UserInterface
 */
class RatingSiteAdminController extends GalleryController {

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
	    $ret = GalleryCoreApi::setPluginParameter('module', 'rating', 'allowAlbumRating',
		    (empty($form['allowAlbumRating']) ? 0 : 1));
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $status['saved'] = 1;
	}

	$results['redirect']['view'] = 'core.SiteAdmin';
	$results['redirect']['subView'] = 'rating.RatingSiteAdmin';
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Rating
 * @subpackage UserInterface
 */
class RatingSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	GalleryCoreApi::requireOnce('modules/rating/classes/RatingHelper.class');

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'RatingSiteAdmin') {
	    $form['formName'] = 'RatingSiteAdmin';
	}
	list ($ret, $form['allowAlbumRating']) =
	    GalleryCoreApi::getPluginParameter('module', 'rating',
		'allowAlbumRating');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$template->setVariable('controller', 'rating.RatingSiteAdmin');
	return array(null,
		array('body' => 'modules/rating/templates/RatingSiteAdmin.tpl'));
    }
}
?>
