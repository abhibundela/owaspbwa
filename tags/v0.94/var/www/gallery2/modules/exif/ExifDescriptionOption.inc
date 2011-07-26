<?php
/*
 * $RCSfile: ExifDescriptionOption.inc,v $
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
 * @version $Revision: 1.16 $ $Date: 2006/01/10 04:39:49 $
 * @package Exif
 * @subpackage UserInterface
 * @author Elliot Shepherd <elliot@jarofworms.com>
 * @author Georg Rehfeld <rehfeld@georg-rehfeld.de>
 */

/**
 * This ItemAddOption uses the EXIF description value for the
 * gallery item summary/description and the IPTC keywords for the
 * gallery item keywords when the image is uploaded.
 *
 * @package Exif
 * @subpackage UserInterface
 */
class ExifDescriptionOption extends ItemAddOption {

    /**
     * @see ItemAddOption::isAppropriate
     */
    function isAppropriate() {
	list ($ret, $addOption) = GalleryCoreApi::getPluginParameter('module', 'exif', 'addOption');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null, $addOption > 0);
    }

    /**
     * @see ItemAddOption::handleRequestAfterAdd
     */
    function handleRequestAfterAdd($form, $items) {
	$errors = array();
	$warnings = array();
	GalleryCoreApi::requireOnce('modules/exif/classes/ExifExtractor.class');
	GalleryCoreApi::requireOnce('modules/exif/classes/ExifHelper.class');

	list ($ret, $addOption) =
	    GalleryCoreApi::getPluginParameter('module', 'exif', 'addOption');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Copy the array because we will change it with do / while / array_splice */
	$itemsInBatches = $items;
	/*
	 * Batch size should be <= ulimit max open files, as long as we don't query this value,
	 * assume a value of 100 which is fairly low
	 */
	$batchSize = 100;
	do {
	    $currentItems = array_splice($itemsInBatches, 0, $batchSize);
	    $itemIds = array();
	    foreach ($currentItems as $item) {
		$itemIds[] = $item->getId();
	    }
	    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($itemIds);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    for ($i = 0; $i < count($currentItems); $i++) {
		$warnings[] = array();
		$itemId = $currentItems[$i]->getId();

		list ($ret, $exifData) =
		    ExifExtractor::getMetaData(
			array($itemId),
			array('IPTC/Caption',
			      'ImageDescription',
			      'UserComment',
			      'IPTC/Keywords',
			      )
			);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockId);
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}

		$mustSave = false;

		/* TODO(xlerb) reconsider, if ExifHelper should be changed to do the preferences. */
		$itemDescription = '';
		if (!empty($exifData[$itemId]['IPTC/Caption']['value'])) {
		    $itemDescription = $exifData[$itemId]['IPTC/Caption']['value'];
		}
		elseif (!empty($exifData[$itemId]['ImageDescription']['value'])) {
		    $itemDescription = $exifData[$itemId]['ImageDescription']['value'];
		}
		elseif (!empty($exifData[$itemId]['UserComment']['value'])) {
		    $itemDescription = $exifData[$itemId]['UserComment']['value'];
		}

		if (!empty($itemDescription)) {
		    if ($addOption & EXIF_ITEM_SUMMARY) {
			$currentItems[$i]->setSummary($itemDescription);
			$mustSave = true;
		    }
		    if ($addOption & EXIF_ITEM_DESCRIPTION) {
			$currentItems[$i]->setDescription($itemDescription);
			$mustSave = true;
		    }
		}

		if (!empty($exifData[$itemId]['IPTC/Keywords']['value'])) {
		    $iptcKeywords = $exifData[$itemId]['IPTC/Keywords']['value'];
		    if ($addOption & IPTC_ITEM_KEYWORDS) {
			$currentItems[$i]->setKeywords($iptcKeywords);
			$mustSave = true;
		    }
		}

		if ($mustSave) {
		    $ret = $currentItems[$i]->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		}
	    }

	    $ret = GalleryCoreApi::releaseLocks($lockId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	} while (!empty($itemsInBatches));

	return array(null, $errors, $warnings);
    }
}
?>
