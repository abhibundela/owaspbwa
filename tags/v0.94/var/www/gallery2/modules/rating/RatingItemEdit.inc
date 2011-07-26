<?php
/*
 * $RCSfile: RatingItemEdit.inc,v $
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
 * @author Don Seiler <don@seiler.us>
 */

/**
 * This controller will handle storing rating options for Gallery albums
 *
 * @package Rating
 *
 */
class RatingItemEdit extends ItemEditOption {

    /**
     * @see ItemEditOption::handleRequestAfterEdit
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	global $gallery;

	if (isset($form['changeInDescendents']['rating'])) {
            list ($ret, $subAlbumIds) =
                GalleryCoreApi::fetchDescendentAlbumItemIds($item);
            if ($ret) {
                return array($ret->wrap(__FILE__, __LINE__), null, null);
            }
            array_unshift($subAlbumIds, $item->getId());
        } else {
            $subAlbumIds = array($item->getId());
        }
        
        foreach ($subAlbumIds as $id) {
	    $ret = GalleryCoreApi::setPluginParameter('module',
		    'rating',
		    'enabled',
		    (empty($form['rating']['enabled']) ? 0 : 1),
		    $id);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}

	return array(null, array(), array());
    }

    /**
     * @see ItemEditOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	if (empty($form['error'])) {

	    list ($ret, $form['rating']['enabled']) = GalleryCoreApi::getPluginParameter('module',
		    'rating',
		    'enabled',
		    $item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}


	return array(null,
		'modules/rating/templates/RatingItemEdit.tpl',
		'modules_rating');
    }

    /**
     * @see ItemEditOption::isAppropriate
     */
    function isAppropriate($item, $thumbnail) {
	return array(null, true);
    }

}
?>
