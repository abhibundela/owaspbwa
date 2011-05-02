<?php
/*
 * $RCSfile: RatingCallback.inc,v $
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
 * @version $Revision: 1.2 $ $Date: 2006/01/10 04:41:53 $
 * @package Rating
 * @author  Don Seiler <don@seiler.us>
 */

/**
 * This view handles calls from the javascript (AJAX) side and returns
 * data to the javascript by printing results.
 *
 * @package Rating
 */
class RatingCallbackView extends GalleryView {

    /**
     * @see GalleryView::isImmediate()
     */
    function isImmediate() {
	return true;
    }

    /**
     * @see GalleryView::isImmediate()
     */
    function renderImmediate($status, $error) {
	global $gallery;

	GalleryCoreApi::requireOnce('modules/rating/classes/RatingHelper.class');

	list ($command, $itemId, $rating) = GalleryUtilities::getRequestVariables('command',
		'itemId', 'rating');
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	if (!headers_sent()) {
	    header("Content-type: text/plain; charset=UTF-8");
	}

	switch($command) {
	case 'rate':
	    list ($ret, $permission) = GalleryCoreApi::hasItemPermission($itemId, 'rating.add');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (!$permission) {
		return null;
	    }

	    list ($ret, $results) = RatingHelper::rateItem($itemId, (int)$rating,
		    $gallery->getActiveUserId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    print $results['itemId'] . "\n"
		. $results['rating'] . "\n"
		. $results['votes'] . "\n"
		. $results['userRating'];

	    break;
	}

	return null;
    }
}
?>
