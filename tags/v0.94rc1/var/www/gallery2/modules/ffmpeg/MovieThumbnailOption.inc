<?php
/*
 * $RCSfile: MovieThumbnailOption.inc,v $
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
 * @version $Revision: 1.2 $ $Date: 2006/01/10 04:39:54 $
 * @package Ffmpeg
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This ItemAddOption adds a watermark on movie thumbnails.
 *
 * @package Ffmpeg
 * @subpackage UserInterface
 */
class MovieThumbnailOption extends ItemAddOption {

    /**
     * @see ItemAddOption::isAppropriate
     */
    function isAppropriate() {
	list ($ret, $useWatermark) =
	    GalleryCoreApi::getPluginParameter('module', 'ffmpeg', 'useWatermark');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $toolkit) = GalleryCoreApi::getToolkitByOperation('image/jpeg', 'composite');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null, $useWatermark && isset($toolkit));
    }

    /**
     * @see ItemAddOption::handleRequestAfterAdd
     */
    function handleRequestAfterAdd($form, $items) {
	$errors = $warnings = $movieIds = $thumbIds = array();
	foreach ($items as $item) {
	    $warnings[] = array();
	    if (GalleryUtilities::isA($item, 'GalleryMovieItem')) {
		$movieIds[] = $item->getId();
	    }
	}
	if (!empty($movieIds)) {
	    list ($ret, $thumbTable) = GalleryCoreApi::fetchThumbnailsByItemIds($movieIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    foreach ($thumbTable as $itemId => $thumbnail) {
		$thumbIds[] = $thumbnail->getId();
	    }
	}
	if (empty($thumbIds)) {
	    return array(null, $errors, $warnings);
	}
	list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($thumbIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	$op = 'composite|plugins_data/modules/ffmpeg/filmreel.png,image/png,12,399';
	foreach ($thumbTable as $itemId => $thumbnail) {
	    /*
	     * Set operations not postFilter so if this item is selected as an album highlight
	     * then the highlight will get the watermark too.
	     */
	    $operations = $thumbnail->getDerivativeOperations();
	    $operations .= (empty($operations) ? '' : ';') . "$op,top-left,0,0;$op,top-right,0,0";
	    $thumbnail->setDerivativeOperations($operations);

	    $ret = $thumbnail->save();
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}

	$ret = GalleryCoreApi::releaseLocks($lockId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	return array(null, $errors, $warnings);
    }
}
?>
