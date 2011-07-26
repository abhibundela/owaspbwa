<?php
/*
 * $RCSfile: Callbacks.inc,v $
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
 * @version $Revision: 1.3 $ $Date: 2006/01/10 17:07:48 $
 * @package Rating
 * @author  Don Seiler <don@seiler.us>
 */
class RatingCallbacks {

    /**
     */
    function callback($params, &$smarty, $callback, $userId) {

	switch ($callback) {
	case 'LoadRating':
	    $itemId = $params['itemId'];
	    list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $allowAlbumRating) = GalleryCoreApi::getPluginParameter('module',
		    'rating',
		    'allowAlbumRating');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (GalleryUtilities::isA($item, 'GalleryAlbumItem') && !$allowAlbumRating) {
		return null;
	    }

	    list ($ret, $permission) = GalleryCoreApi::hasItemPermission($itemId,
		    'rating.view',
		    $userId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (!$permission) {
		return null;
	    }


	    GalleryCoreApi::requireOnce('modules/rating/classes/RatingHelper.class');
	    list ($ret, $RatingData) = RatingHelper::fetchRatings(array($itemId), $userId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (!isset($RatingData[$itemId])) {
		$RatingData[$itemId] = array( 'itemId' => $itemId, 'rating' => 0, 'votes' => 0);
	    }

	    /* Set star information */
	    $RatingData[$itemId]['stars'] = round($RatingData[$itemId]['rating'] * 2);
	    if ($RatingData[$itemId]['rating'] == 0) {
		$RatingData[$itemId]['stars'] = 1;
	    }
	    if (!isset($RatingData[$itemId]['userRating'])) {
		$RatingData[$itemId]['userRating'] = 'N/A';
		$RatingData[$itemId]['userStars'] = 0;
	    } else {
		$RatingData[$itemId]['userStars'] = round($RatingData[$itemId]['userRating'] * 2);
	    }
	    $RatingData[$itemId]['averagePercent']
		= $RatingData[$itemId]['rating'] * 100 / 5;

	    list ($ret, $permission) = GalleryCoreApi::hasItemPermission($itemId,
		    'rating.add',
		    $userId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $RatingData[$itemId]['canRate'] = ($permission ? true : false);
	    $RatingSummary = array('ratingValues' => array(1, 2, 3, 4, 5), 'firstCall' => true);

	    $block =& $smarty->_tpl_vars['block'];
	    $block['rating']['RatingData'] = $RatingData[$itemId];
	    $block['rating']['RatingSummary'] = $RatingSummary;


	    return null;
	}

	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }
}
?>
