<?php
/*
 * $RCSfile: PrintPhoto.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/01/10 04:41:23 $
 * @package PhotoAccess
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller handles printing a single photo
 *
 * @package PhotoAccess
 * @subpackage UserInterface
 */
class PrintPhotoController extends GalleryController {

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	global $gallery;

	list ($itemId, $returnUrl) = GalleryUtilities::getRequestVariables('itemId', 'returnUrl');

	GalleryCoreApi::requireOnce('modules/photoaccess/classes/PhotoAccessHelper.class');
	list ($ret, $cartUrl) = PhotoAccessHelper::printPhotos(array($itemId => 1), $returnUrl);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Prepare our results */
	$results['redirect']['href'] = $cartUrl;
	$results['status'] = array();
	$results['error'] = array();

	return array(null, $results);
    }
}
?>
