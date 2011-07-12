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
 * @version $Revision: 1.41 $ $Date: 2006/03/22 03:52:34 $
 * @package RandomHighlight
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * RandomHighlight module - periodically change the album highlight
 *
 * @package RandomHighlight
 */
class RandomHighlightModule extends GalleryModule {

    function RandomHighlightModule() {
	global $gallery;
	$this->setId('randomhighlight');
	$this->setName($gallery->i18n('Random Highlight'));
	$this->setDescription($gallery->i18n('Periodically change the album highlight'));
	$this->setVersion('1.0.3'); /* Update upgrade() function below too */
	$this->setGroup('display', $gallery->i18n('Display'));
	$this->setCallbacks('getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	global $gallery;

	list ($ret, $params) =
	    GalleryCoreApi::fetchAllPluginParameters('module', 'randomhighlight');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	foreach (array('duration' => 7200 /* 2 hours */) as $key => $value) {
	    if (!isset($params[$key])) {
		$ret = $this->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	if (!empty($currentVersion)) {
	    switch ($currentVersion) {
	    case '0.5':
	    case '0.8.1':
	    case '0.8.2':
	    case '0.8.3':
	    case '0.8.4':
	    case '0.8.5':
	    case '0.8.6':
		/* Changed from RandomHighlightDerivativeImage entity type to onLoadHandler */
		$storage =& $gallery->getStorage();
		$query = 'UPDATE [GalleryEntity]
		    SET [::entityType] = \'GalleryDerivativeImage\',
			[::onLoadHandlers] = \'|RandomHighlight|\'
		  WHERE [GalleryEntity::entityType] = \'RandomHighlightDerivativeImage\'
		    AND [GalleryEntity::onLoadHandlers] IS NULL';
		$ret = $storage->execute($query);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $handlers) = $storage->getFunctionSql('CONCAT',
		    array('[GalleryEntity::onLoadHandlers]', "'RandomHighlight|'"));
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		$query = 'UPDATE [GalleryEntity]
		    SET [::entityType] = \'GalleryDerivativeImage\',
			[::onLoadHandlers] = ' . $handlers . '
		  WHERE [GalleryEntity::entityType] = \'RandomHighlightDerivativeImage\'
		    AND [GalleryEntity::onLoadHandlers] IS NOT NULL';
		$ret = $storage->execute($query);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

	    case '0.9.0':
	    case '0.9.1':
	    case '0.9.2':
	    case '0.9.3':
	    case '0.9.4':
	    case '0.9.5':
	    case '0.9.6':
	    case '0.9.7':
	    case '0.9.8':
	    case '0.9.9':
	    case '1.0.0':
	    case '1.0.1':
	    case '1.0.2':

	    case 'end of upgrade path':
		break;

	    default:
		return GalleryCoreApi::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
					    sprintf('Unknown module version %s', $currentVersion));
	    }
	}

	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'GalleryOnLoadHandler', 'RandomHighlightModule', 'RandomHighlight',
	    'modules/randomhighlight/module.inc', 'randomhighlight', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'GalleryOnLoadHandler', 'RandomHighlightModule', 'RandomHighlightPrivate',
	    'modules/randomhighlight/module.inc', 'randomhighlight', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'ItemEditOption', 'RandomHighlightOption', 'RandomHighlightOption',
	    'modules/randomhighlight/RandomHighlightOption.inc', 'randomhighlight',
	    array('ItemEditAlbum'));
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::getOnLoadHandlerIds()
     */
    function getOnLoadHandlerIds() {
	return array('RandomHighlight', 'RandomHighlightPrivate');
    }

    /**
     * @see GalleryModule::getSiteAdminViews()
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('Random Highlight'),
				 'view' => 'randomhighlight.RandomHighlightSiteAdmin')));
    }

    /**
     * Check age and pick new highlight if needed..
     */
    function onLoad(&$thumbnail, $duringUpgrade) {
	$picknew = false;
	$id = $thumbnail->getId();
	$loop = GalleryDataCache::containsKey("randomhighlight:load-$id");
	/* We don't want to refresh the highlight during an upgrade */
	if ($duringUpgrade) {
	    return null;
	}
	/* Acquire information whether we need to choose a new highlight */
	list ($ret, $duration) = $this->getParameter('duration');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$isOld = (time() - $thumbnail->getModificationTimestamp() > $duration);
	$canSee = true;
	if (!$loop && !$isOld && $thumbnail->hasOnLoadHandler('RandomHighlightPrivate')) {
	    /* Highlight is currently a non-public item.. can current user view that item? */
	    list ($ret, $highlight) =
		GalleryCoreApi::loadEntitiesById($thumbnail->getDerivativeSourceId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $canSee) =
		GalleryCoreApi::hasItemPermission($highlight->getParentId(), 'core.view');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}
	$viewName = GalleryUtilities::getRequestVariables('view');
	/*
	 * Check if we need to pick a new highlight. Don't pick a new one on DownloadItem
	 * requests, unless we don't have the permission to see the current highlight
	 */
	if (!$loop && (($isOld && $viewName != 'core.DownloadItem') || !$canSee)) {
	    /* Lock and refresh to ensure another request didn't just update highlight */
	    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($id);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    /* ->refresh() will load the onLoad handler again, thus use the GDC $loop variable */
	    GalleryDataCache::put("randomhighlight:load-$id", true);
	    list ($ret, $refresh) = $thumbnail->refresh();
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if ($refresh->getSerialNumber() == $thumbnail->getSerialNumber()) {
		$picknew = true;
	    }
	}
	
	if ($picknew) {
	    list ($ret, $album) = GalleryCoreApi::loadEntitiesById($thumbnail->getParentId());
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $childIds) = GalleryCoreApi::fetchChildItemIds($album);
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds($childIds);
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (empty($thumbnails)) {
		GalleryCoreApi::releaseLocks($lockId);
		return null;
	    }
	    /* Prevent from choosing the old highlight again if there are 2 or more candidates */
	    if (count($thumbnails) > 1) {
		/* Get the id of the highlight if we don't have it already */
		if (!isset($highlight)) {
		    list ($ret, $highlight) =
			GalleryCoreApi::loadEntitiesById($thumbnail->getDerivativeSourceId());
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
		/* $thumbnails array is indexed by itemIds */
		if (isset($thumbnails[$highlight->getParentId()])) {
		    unset($thumbnails[$highlight->getParentId()]);
		}
	    }
	    /* Pick a new random highlight */
	    $itemId = array_keys($thumbnails);
	    $itemId = $itemId[rand(0, count($itemId)-1)];
	    $highlight = $thumbnails[$itemId];

	    /* Check if we have selected a public item for the highlight */
	    list ($ret, $guestId) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $isPublic) =
		GalleryCoreApi::hasItemPermission($itemId, 'core.view', $guestId);
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $onLoadHandlers = $thumbnail->getOnLoadHandlers();
	    $thumbnail->setOnLoadHandlers(
		preg_replace('{\|RandomHighlight(Private)?\|}',
			     '|RandomHighlight' . ($isPublic ? '|' : 'Private|'), $onLoadHandlers));

	    $thumbnail->setDerivativeSourceId($highlight->getId());
	    GalleryCoreApi::estimateDerivativeDimensions($thumbnail, $highlight);
	    $thumbnail->save();
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $ret = GalleryCoreApi::expireDerivativeTreeBySourceIds(array($thumbnail->getId()));
	    if ($ret) {
		GalleryCoreApi::releaseLocks($lockId);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}
	if (isset($lockId)) {
	    $ret = GalleryCoreApi::releaseLocks($lockId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}

	return null;
    }
}
?>
