<?php
/*
 * $RCSfile: DiskQuotaOption.inc,v $
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
 * @version $Revision: 1.7 $ $Date: 2006/01/13 03:39:48 $
 * @package Quotas
 * @subpackage UserInterface
 * @author Robert Balousek <volksport@users.sf.net>
 */

/**
 * This ItemAddOption enforces disk quotas
 *
 * @package Quotas
 * @subpackage UserInterface
 */

class DiskQuotaOption extends ItemAddOption {

    /**
     * @see ItemAddOption::isAppropriate
     */
    function isAppropriate() {
        return array(null, true);
    }

    /**
     * @see ItemAddOption::handleRequestAfterAdd
     */
    function handleRequestAfterAdd($form, $items) {
	GalleryCoreApi::requireOnce('modules/quotas/classes/GalleryQuotasHelper.class');

	global $gallery;
	$warnings = array();
	$errors = array();

	/* user's disk quota in KB */
        list ($ret, $quotaExists, $userDiskQuota) =
            GalleryQuotasHelper::getUserDiskQuota($gallery->getActiveUserId());
        if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
        }

        /* if user has a quota */
	if ($quotaExists) {
	    $excludedIds = array();
	    for ($j = 0; $j < count($items); $j++) {
		$excludedIds[] = $items[$j]->getId();
	    }
	    list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'quotas');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    for ($i = 0; $i < count($items); $i++) {
		$warnings[$i] = array();

		/* we should come here only if we are adding a data item, not sub-album */
		$albumId = $items[$i]->getParentId();

		/* calculate the item ids of the newly added items */
		array_shift($excludedIds);

		/* user's disk usage in Bytes */
		list ($ret, $userDiskUsage) = GalleryQuotasHelper::getUserDiskUsage(
		    $gallery->getActiveUserId(), $excludedIds);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}

		/* make disk usage into KB */
		$userDiskUsage /= 1024.00;

		if ($userDiskUsage > $userDiskQuota) {
		    list ($ret, $userDiskQuotaHumanReadable, $userDiskQuotaHumanReadableUnit) =
			GalleryQuotasHelper::humanReadableFromKilobytes($userDiskQuota);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $warnings[$i][] = $module->translate(
			array('text' => 'Warning: You have reached your disk quota (%s %s), ' .
			      'this item will not be added.',
			      'arg1' => $userDiskQuotaHumanReadable,
			      'arg2' => $userDiskQuotaHumanReadableUnit));

		    $gallery->addShutdownAction(
			array('GalleryCoreApi', 'deleteEntityById'), array($items[$i]->getId()));
		} else {
		    list ($ret, $userDiskQuotaHumanReadable, $userDiskQuotaHumanReadableUnit) = 
			GalleryQuotasHelper::humanReadableFromKilobytes($userDiskQuota);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    list ($ret, $userDiskUsageHumanReadable, $userDiskUsageHumanReadableUnit) = 
			GalleryQuotasHelper::humanReadableFromKilobytes($userDiskUsage);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    $warnings[$i][] = $module->translate(
			array('text' => 'You are using %s %s of your allotted %s %s.',
			      'arg1' => $userDiskUsageHumanReadable,
			      'arg2' => $userDiskUsageHumanReadableUnit,
			      'arg3' => $userDiskQuotaHumanReadable,
			      'arg4' => $userDiskQuotaHumanReadableUnit));
		}
	    }
        }
	return array(null, $errors, $warnings);
    }

}
?>
