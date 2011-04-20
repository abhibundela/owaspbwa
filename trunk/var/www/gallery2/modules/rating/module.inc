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
 * @package Rating
 * @version $Revision: 1.4 $ $Date: 2006/03/22 03:52:34 $
 * @author  Don Seiler <don@seiler.us>
 */

/**
 * Rating
 *
 * Provides item rating mechanism for Gallery2
 *
 * @package Rating
 */
class RatingModule extends GalleryModule {

    function RatingModule() {
	global $gallery;

	$this->setId('rating');
	$this->setName($gallery->i18n('Rating'));
	$this->setDescription($gallery->i18n('Item Rating Interface'));
	$this->setVersion('1.0.3'); /* Update upgrade() too */
	$this->setCallbacks('getSiteAdminViews|getItemSummaries|registerEventListeners');
	$this->setGroup('data', $gallery->i18n('Extra Data'));
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::registerEventListeners()
     */
    function registerEventListeners() {
	GalleryCoreApi::registerEventListener('GalleryEntity::delete', new RatingModule());
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation( 'ItemEditOption', 'RatingItemEdit',
		'RatingItemEdit', 'modules/rating/RatingItemEdit.inc', 'rating',
		array('ItemEditAlbum'));
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::registerFactoryImplementation( 'GallerySortInterface_1_1',
		'RatingSortOrder', 'RatingSortOrder',
		'modules/rating/classes/RatingSortOrder.class', 'rating', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }


    /**
     * @see GalleryModule::getItemSummaries()
     */
    function getItemSummaries($items, $permissions, &$template) {
	global $gallery;

	$itemIds = array();
	$enabledAlbums = array();
	$numEnabledAlbums = 0;
	foreach ($items as $item) {
	    $itemIds[] = $item->getId();

	    $parentId = $item->getParentId();
	    if (isset($enabledAlbums[$parentId])) {
		continue;
	    }

	    list ($ret, $enabled) = GalleryCoreApi::getPluginParameter('module', 'rating',
					'enabled', $parentId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $enabledAlbums[$parentId] = !empty($enabled);
	    if ($enabledAlbums[$parentId]) {
		$numEnabledAlbums++;
	    }
	}
 
	if (empty($numEnabledAlbums)) {
	    return array(null, array());
	}

	/* Load the G2 templating engine */
	GalleryCoreApi::requireOnce('modules/rating/classes/RatingHelper.class');

	$template->style('modules/rating/rating.css');
	$template->javascript('lib/javascript/XmlHttp.js');
	$template->javascript('modules/rating/rating.js');

	$template->setVariable('l10Domain', $this->getL10Domain());
	$RatingSummary = array('ratingValues' => array(1, 2, 3, 4, 5), 'firstCall' => true);

	/* Check to see if album rating is allowed */
	list ($ret, $allowAlbumRating) = GalleryCoreApi::getPluginParameter('module', 'rating',
		'allowAlbumRating');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Acquire the current rating for the items (populate $RatingData) */

	list ($ret, $RatingData) = RatingHelper::fetchRatings($itemIds,
		$gallery->getActiveUserId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$summaries = array();
	foreach ($items as $item) {
	    $itemId = $item->getId();
	    $parentId = $item->getParentId();

	    /* Check if item's parent album allows rating */
	    if (!$enabledAlbums[$parentId]) {
		continue;
	    }

	    /* Check if this user can view rating for this item */
	    if (!isset($permissions[$itemId]['rating.view'])) {
		continue;
	    }

	    if (GalleryUtilities::isA($item, 'GalleryAlbumItem') && !$allowAlbumRating) {
		continue;
	    }

	    if (!isset($RatingData[$itemId])) {
		$RatingData[$itemId] = array('itemId' => $itemId, 'rating' => 0, 'votes' => 0);
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

	    $RatingData[$itemId]['averagePercent'] = $RatingData[$itemId]['rating'] * 100 / 5;
	    $RatingData[$itemId]['canRate'] = isset($permissions[$itemId]['rating.add']);
	    $template->setVariable('RatingData', $RatingData[$itemId]);
	    $template->setVariable('RatingSummary', $RatingSummary);

	    /* Render and get the html */
	    list ($ret, $summaries[$itemId]) =
		$template->fetch('gallery:modules/rating/templates/RatingInterface.tpl');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $RatingSummary['firstCall'] = false;
	}

	return array(null, $summaries);
    }


    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	global $gallery;

	if (!isset($currentVersion)) {
	    $currentVersion = '0';
	}

	switch ($currentVersion) {
	case '0':
	    list ($ret, $coreParams) = GalleryCoreApi::fetchAllPluginParameters('module', 'core');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Initial install.  Register our permissions */
	    $permissions[] = array('add', $gallery->i18n('[rating] Add ratings'), 0, array());
	    $permissions[] = array('view', $gallery->i18n('[rating] View ratings'), 0, array());
	    $permissions[] = array('all', $gallery->i18n('[rating] All access'),
		    GALLERY_PERMISSION_COMPOSITE, array('rating.add', 'rating.view'));
	    foreach ($permissions as $p) {
		$ret = GalleryCoreApi::registerPermission($this->getId(),
			'rating.' . $p[0],
			$p[1], $p[2], $p[3]);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    /* Give everybody rating permission by default */
	    $ret = GalleryCoreApi::addGroupPermission(
		$coreParams['id.rootAlbum'], $coreParams['id.everybodyGroup'],
		'rating.all', true);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    break;

	case '1.0.0':
	case '1.0.1':
	case '1.0.2':

	case 'end of upgrade path':
	    break;

	default:
	    return GalleryCoreApi::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
		    sprintf('Unknown module version %s', $currentVersion));
	}

	return null;
    }


    /**
     * @see GalleryModule::getSiteAdminViews()
     */
    function getSiteAdminViews() {
	return array(null,
		array(array('name' => $this->translate('Rating'),
			'view' => 'rating.RatingSiteAdmin')));
    }

    /**
     * Event handler for GalleryEntity::delete events
     * Delete any item ratings if the associated item is deleted
     *
     * @see GalleryEventListener::handleEvent
     */
    function handleEvent($event) {
	$result = null;
	if ($event->getEventName() == 'GalleryEntity::delete') {
	    $entity = $event->getEntity();

	    if (GalleryUtilities::isA($entity, 'GalleryItem')) {
		$ret = GalleryCoreApi::removeMapEntry('RatingMap',
						      array('itemId' => $entity->getId()));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$ret = GalleryCoreApi::removeMapEntry('RatingCacheMap',
						      array('itemId' => $entity->getId()));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}
	return array(null, $result);
    }

}
?>
