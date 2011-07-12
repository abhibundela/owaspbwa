<?php
/*
 * $RCSfile: module.inc,v $
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
 * @package Watermark
 * @version $Revision: 1.47.2.1 $ $Date: 2006/04/05 22:10:59 $
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * Watermark Module
 *
 * This module provides support for adding watermarks to images
 *
 * @package Watermark
 */
class WatermarkModule extends GalleryModule /* and GalleryEventListener */ {

    function WatermarkModule() {
	global $gallery;

	$this->setId('watermark');
	$this->setName($gallery->i18n('Watermark'));
	$this->setDescription($gallery->i18n('Watermark your images'));
	$this->setVersion('1.0.6'); /* Update upgrade() too */
	$this->setGroup('display', $gallery->i18n('Display'));
	$this->setCallbacks('registerEventListeners|getSiteAdminViews|getUserAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade
     */
    function upgrade($currentVersion) {
	global $gallery;

	$platform =& $gallery->getPlatform();
	$slash = $platform->getDirectorySeparator();
	$watermarkDir =
	    $gallery->getConfig('data.gallery.plugins_data') . 'modules/watermark' . $slash;

	if (!isset($currentVersion)) {
	    $currentVersion = '0';
	}

	switch ($currentVersion) {
	case '0':
	    /* Initial install */
	    list ($success) = GalleryUtilities::guaranteeDirExists($watermarkDir);
	    if (!$success) {
		return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
					    "Unable to create directory: $watermarkDir");
	    }
	    break;

	case '0.3':
	    $storage =& $gallery->getStorage();
	    $ret = $storage->configureStore($this->getId(), array('WatermarkImage:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.1':
	case '0.8.2':
	case '0.8.3':
	case '0.8.4':
	case '0.8.5':
	case '0.8.6':
	case '0.8.7':
	case '0.8.8':
	case '0.8.9':
	case '0.9.1':
	    /* Move watermark dir from g2data/ to g2data/plugins_data */
	    $oldDir = $gallery->getConfig('data.gallery.base') . 'watermark' . $slash;
	    if ($platform->is_dir($oldDir)) {
		if (!$platform->rename($oldDir, $watermarkDir)) {
		    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
						"Unable to move $oldDir to $watermarkDir");
		}
	    } else {
		list ($success) = GalleryUtilities::guaranteeDirExists($watermarkDir);
		if (!$success) {
		    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
						"Unable to create $watermarkDir");
		}
	    }

	    /* Update all derivatives that use watermarks */
	    $query = "
	    SELECT
	      [GalleryDerivative::id]
	    FROM
	      [GalleryDerivative]
	    WHERE
	      [GalleryDerivative::postFilterOperations] LIKE '%composite|watermark%'
	    ";

	    /* Do them 50 at a time to improve locking efficiency */
	    while (true) {
		list ($ret, $searchResults) =
		    $gallery->search($query, array(), array('limit' => array('count' => 50)));
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if ($searchResults->resultCount() == 0) {
		    /* We're done */
		    break;
		}

		while ($result = $searchResults->nextResult()) {
		    $ids[] = (int)$result[0];
		}

		list ($ret, $lock) = GalleryCoreApi::acquireWriteLock($ids);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		list ($ret, $derivatives) = GalleryCoreApi::loadEntitiesById($ids);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lock);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		foreach ($derivatives as $derivative) {
		    $derivative->setPostFilterOperations(
			str_replace('composite|watermark',
				    'composite|plugins_data/modules/watermark',
				    $derivative->getPostFilterOperations()));

		    $ret = $derivative->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lock);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		$ret = GalleryCoreApi::releaseLocks($lock);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.2':
	case '0.9.3':
	case '0.9.4':
	case '0.9.5':
	case '0.9.6':
	case '0.9.7':
	case '1.0.0':
	case '1.0.1':
	case '1.0.2':
	case '1.0.3':
	case '1.0.4':
	case '1.0.5':

	case 'end of upgrade path':
	    /*
	     * Leave this bogus case at the end of the legitimate case statements so that we
	     * always properly terminate our upgrade path with a break.
	     */
	    break;

	default:
	    return GalleryCoreApi::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
					sprintf('Unknown module version %s', $currentVersion));
	}

	return null;
    }

    /**
     * @see GalleryModule::uninstall()
     */
    function uninstall() {
	global $gallery;
	GalleryCoreApi::requireOnce('modules/watermark/classes/WatermarkHelper.class');

	/* Delete all watermarks and remove uses of them */
	$query = '
	SELECT [WatermarkImage::id]
	FROM [WatermarkImage]
	';
	list ($ret, $searchResults) = $gallery->search($query);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	while ($result = $searchResults->nextResult()) {
	    list ($ret) = WatermarkHelper::deleteWatermarkImageById((int)$result[0], true);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}

	$ret = parent::uninstall();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'GalleryEntity', 'WatermarkImage', 'WatermarkImage',
	    'modules/watermark/classes/WatermarkImage.class', 'watermark', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'ItemEditPlugin', 'ItemEditWatermark', 'ItemEditWatermark',
	    'modules/watermark/ItemEditWatermark.inc', 'watermark', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'ItemAddOption', 'WatermarkOption', 'WatermarkOption',
	    'modules/watermark/WatermarkOption.inc', 'watermark', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::registerEventListeners();
     */
    function registerEventListeners() {
	GalleryCoreApi::registerEventListener('GalleryEntity::delete', new WatermarkModule());
    }

    /**
     * @see GalleryModule::getSiteAdminViews()
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('Watermarks'),
				 'view' => 'watermark.WatermarkSiteAdmin')));
    }

    /**
     * @see GalleryModule::getUserAdminViews();
     */
    function getUserAdminViews($user) {
	global $gallery;

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$views = array();
	if ($user->getId() != $anonymousUserId) {
	    $views[] = array('name' => $this->translate('Watermarks'),
			     'view' => 'watermark.UserWatermarks');
	}

	return array(null, $views);
    }

    /**
     * Handler for GalleryEntity::delete event.  Get rid of any watermarks
     * for users that are deleted.
     *
     * @see GalleryEventListener::handleEvent
     */
    function handleEvent($event) {
	if ($event->getEventName() == 'GalleryEntity::delete') {
	    $entity = $event->getEntity();
	    if (GalleryUtilities::isA($entity, 'GalleryUser')) {
		GalleryCoreApi::requireOnce(
		    'modules/watermark/classes/WatermarkHelper.class');
		list ($ret, $watermarkIds) =
		    WatermarkHelper::fetchWatermarkIdsByOwnerId($entity->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		foreach ($watermarkIds as $id) {
		    list ($ret) = WatermarkHelper::deleteWatermarkImageById($id, true);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
	    }
	}
	return array(null, null);
    }

    /**
     * @see GalleryModule::getRewriteRules
     */
    function getRewriteRules() {
	return array(
	    array( 'comment' => $this->translate('Watermark hotlinked images'),
		   'pattern' => '.',
		   'parser' => 'preGallery',
		   'restrict' => array('view' => 'core.DownloadItem',
				       'itemId' => '([0-9]+)'),
		   'queryString' => array('view' => 'watermark.DownloadItem',
					  'itemId' => '%1'),
		   'flags' => array(),
		   'locked' => 1,
		   'exemptReferer' => 1,
		   'help' => $this->translate(
		       'Apply watermark to images downloaded from outside your Gallery. Select ' .
		       'which watermark to use in Watermark Site Admin.')
		 )
	    );
    }
}
?>
