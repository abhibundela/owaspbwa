<?php
/*
 * $RCSfile: ItemEditAlbum.inc,v $
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
 * @version $Revision: 1.77 $ $Date: 2006/03/14 18:36:07 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the changes users make to an album.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemEditAlbum extends ItemEditPlugin {

    /**
     * @see ItemEditPlugin::handleRequest
     */
    function handleRequest($form, &$item, &$preferred) {
	global $gallery;

	$status = null;
	$error = array();
	$requiresProgressBar = false;
	if (isset($form['action']['undo'])) {

	    /*
	     * Take no action and we'll be redirected back to the same page
	     * which will reset the form
	     */
	} else if (isset($form['action']['save'])) {
	    /* Validate the input data. */
	    if (!is_numeric($form['thumbnail']['size']) || $form['thumbnail']['size'] < 1) {
		$error[] = 'form[error][thumbnail][size][invalid]';
	    }

	    for ($i = 0; $i < sizeof($form['resizes']); $i++) {
		if (!empty($form['resizes'][$i]['active'])) {
		    if (empty($form['resizes'][$i]['width']) ||
			    empty($form['resizes'][$i]['height'])) {
			$error[] = 'form[error][resizes][' . $i . '][size][missing]';
		    } else if (!is_numeric($form['resizes'][$i]['width'])
			      || $form['resizes'][$i]['width'] < 1
			      || !is_numeric($form['resizes'][$i]['height'])
			      || $form['resizes'][$i]['height'] < 1) {
			$error[] = 'form[error][resizes][' . $i . '][size][invalid]';
		    }
		}
	    }

	    if (empty($error)) {
		/* Delete existing derivative preferences */
		$ret = GalleryCoreApi::removeDerivativePreferencesForItem($item->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$changeTypes = array();
		if (isset($form['changeInDescendents'])) {
		    list ($ret, $subAlbumIds) =
			GalleryCoreApi::fetchDescendentAlbumItemIds($item);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}
		/* Add the thumbnail size back in */
		$ret = GalleryCoreApi::addDerivativePreference(0, $item->getId(),
		    DERIVATIVE_TYPE_IMAGE_THUMBNAIL,
		    'thumbnail|' . $form['thumbnail']['size']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
		if (isset($form['changeInDescendents']['thumbnail'])) {
		    $changeTypes[] = DERIVATIVE_TYPE_IMAGE_THUMBNAIL;
		}

		/* Add the resize-sizes back in */
		for ($i = 0; $i < sizeof($form['resizes']); $i++) {
		    if (empty($form['resizes'][$i]['active'])) {
			continue;
		    }

		    $ret = GalleryCoreApi::addDerivativePreference($i, $item->getId(),
			DERIVATIVE_TYPE_IMAGE_RESIZE, 'scale|' .
			$form['resizes'][$i]['width'] . ',' . $form['resizes'][$i]['height']);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}
		if (isset($form['changeInDescendents']['resizes'])) {
		    $changeTypes[] = DERIVATIVE_TYPE_IMAGE_RESIZE;
		}

		/* Use appropriate settings in descendent albums */
		if (!empty($changeTypes) && !empty($subAlbumIds)) {
		    $ret = GalleryCoreApi::removeDerivativePreferenceForItemType(
			    $subAlbumIds, $changeTypes);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		    if (isset($form['changeInDescendents']['thumbnail'])) {
			$ret = GalleryCoreApi::addDerivativePreference(0, $subAlbumIds,
				DERIVATIVE_TYPE_IMAGE_THUMBNAIL,
				'thumbnail|' . $form['thumbnail']['size']);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			}
		    }
		    if (isset($form['changeInDescendents']['resizes'])) {
			for ($i = 0; $i < sizeof($form['resizes']); $i++) {
			    if (empty($form['resizes'][$i]['active'])) {
				continue;
			    }

			    $ret = GalleryCoreApi::addDerivativePreference($i, $subAlbumIds,
				    DERIVATIVE_TYPE_IMAGE_RESIZE, 'scale|' .
				    $form['resizes'][$i]['width'] . ',' .
				    $form['resizes'][$i]['height']);
			    if ($ret) {
				return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			    }
			}
		    }
		}

		/* Recreate the thumbnails, if requested to do so */
		if (isset($form['recreateThumbnails'])) {
		    $templateAdapter =& $gallery->getTemplateAdapter();
		    $templateAdapter->registerTrailerCallback(
			array($this, 'runRecreateThumbnails'),
			array($form, $item, $preferred, $status));
		    $requiresProgressBar = true;
		}

		/*
		 * Recreate the resizes, if requested to do so.
		 * TODO: We should not delete resizes that we will be
		 *       adding again moments later again
		 */
		if (isset($form['recreateResizes'])) {
		    $templateAdapter =& $gallery->getTemplateAdapter();
		    $templateAdapter->registerTrailerCallback(
			array($this, 'runRecreateResizes'),
			array($form, $item, $preferred, $status));
		    $requiresProgressBar = true;
		}

		/* Save basic settings */
		$item->setTheme($form['theme']);
		if (!empty($form['presort'])) {
		    $item->setOrderBy($form['presort'] . '|' . $form['orderBy']);
		} else {
		    $item->setOrderBy($form['orderBy']);
		}
		if (isset($form['orderDirection'])) {
		    $item->setOrderDirection($form['orderDirection']);
		}
		$item->setSerialNumber($form['serialNumber']);

		list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($item->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$ret = $item->save();
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockId);
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		/* for sort order and theme we need to load subalbums */
		if (!empty($subAlbumIds) && (isset($form['changeInDescendents']['sort'])
			    || isset($form['changeInDescendents']['theme']))) {
		    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($subAlbumIds);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    list ($ret, $subAlbums) = GalleryCoreApi::loadEntitiesById($subAlbumIds);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		    foreach($subAlbums as $album) {
			if (isset($form['changeInDescendents']['sort'])) {
			    if (!empty($form['presort'])) {
				$album->setOrderBy($form['presort'] . '|' . $form['orderBy']);
			    } else {
				$album->setOrderBy($form['orderBy']);
			    }
			    if (isset($form['orderDirection'])) {
				$album->setOrderDirection($form['orderDirection']);
			    }
			}
			if (isset($form['changeInDescendents']['theme'])) {
			    $album->setTheme($form['theme']);
			}
			$ret = $album->save();
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockId);
			    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			}
		    }
		    $ret = GalleryCoreApi::releaseLocks($lockId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}

		/* Prepare our status message */
		list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$status = $module->translate('Settings saved successfully.');
	    }
	}

	return array(null, $error, $status, $requiresProgressBar);
    }

    /**
     * @see ItemEditPlugin::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	global $gallery;

	if ($form['formName'] != 'ItemEditAlbum') {
	    $tmp = explode('|', $item->getOrderBy(), 2);
	    if (count($tmp) < 2) {
		$form['orderBy'] = $item->getOrderBy();
		$form['presort'] = '';
	    } else {
		$form['orderBy'] = $tmp[1];
		$form['presort'] = $tmp[0];
	    }
	    $form['orderDirection'] = $item->getOrderDirection();
	    $form['theme'] = $item->getTheme();
	    $form['theme'] = $item->getTheme();
	    $form['formName'] = 'ItemEditAlbum';

	    /* Load up the data for the resizes table */
	    list ($ret, $preferences) =
		GalleryCoreApi::fetchDerivativePreferencesForItem($item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    foreach ($preferences as $preference) {
		if (preg_match('/(?:resize|scale|thumbnail)\|(\d+)(?:,(\d+))?/',
			       $preference['derivativeOperations'],
			       $matches)) {
		    $size = $matches[1];
		    $height = empty($matches[2]) ? $size : $matches[2];
		}

		switch ($preference['derivativeType']) {
		case DERIVATIVE_TYPE_IMAGE_THUMBNAIL:
		    $form['thumbnail']['size'] = $size;
		    break;

		case DERIVATIVE_TYPE_IMAGE_RESIZE:
		    if (empty($size)) {
			$form['resizes'][] = array('active' => 0, 'width' => '', 'height' => '');
		    } else {
			$form['resizes'][] = array('active' => 1, 'width' => $size,
						   'height' => $height);
		    }
		    break;
		}
	    }

	    /* Tag on a few form blanks */
	    if (empty($form['resizes'])) {
		$extraBlanks = 3;
	    } else {
		$extraBlanks = max(2 - sizeof($form['resizes']), 0) + 1;
	    }

	    while ($extraBlanks-- > 0) {
		$form['resizes'][] = array('active' => 0, 'width' => '', 'height' => '');
	    }

	    /* Always force these to be false */
	    $form['recreateThumbnails'] = false;
	    $form['recreateResizes'] = false;
	}

	/* Checkboxes are annoying in that they are empty if false */
	$form['recreateThumbnails'] = !empty($form['recreateThumbnails']);
	$form['recreateResizes'] = !empty($form['recreateThumbnails']);
	for ($i = 0; $i < sizeof($form['resizes']); $i++) {
	    $form['resizes'][$i]['active'] = !empty($form['resizes'][$i]['active']);
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Set up our sort order selection list */
	GalleryCoreApi::requireOnce('modules/core/classes/GallerySortInterface_1_1.class');
	list ($ret, $orderByList, $presortList, $orderDirectionList) =
	    GallerySortInterface_1_1::getAllSortOrders();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Set up our theme selection list */
	list ($ret, $themeStatusList) = GalleryCoreApi::fetchPluginStatus('theme');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	$themeList = array('' => $module->translate('&laquo; default theme &raquo;'));
	foreach ($themeStatusList as $themeId => $themeStatus) {
	    if (empty($themeStatus['active'])) {
		continue;
	    }

	    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    $themeList[$themeId] = $theme->translate($theme->getName());
	}

	$ItemEditAlbum['themeList'] = $themeList;
	$ItemEditAlbum['orderByList'] = $orderByList;
	$ItemEditAlbum['presortList'] = $presortList;
	$ItemEditAlbum['orderDirectionList'] = $orderDirectionList;

	$template->setVariable('ItemEditAlbum', $ItemEditAlbum);
	$template->setVariable('controller', 'core.ItemEditAlbum');
	return array(null,
		     'modules/core/templates/ItemEditAlbum.tpl', 'modules_core');
    }

    function runRecreateResizes($form, $item, $preferred, $status) {
	global $gallery;
	$storage =& $gallery->getStorage();

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$templateAdapter =& $gallery->getTemplateAdapter();
	$ret = $templateAdapter->updateProgressBar(
	    $module->translate('Recreating resized images'), 'Preparing...', 0);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Get the child id => child types */
	list ($ret, $childIds) = GalleryCoreApi::fetchDescendentItemIds($item);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/*
	 * for the progress bar feedback we will assume that the number of deleted
	 * items is the same as the number of created.
	 */
	$total = sizeof($childIds) * 2;
	$runningTotal = 0;
	$step = min(200, intval($total/20));
	$batchSize = 100;
	$resizes = array();
	while (!empty($childIds)) {
	    $ind = $runningTotal;	/* little true-up */
	    $currentChildIds = array_splice($childIds, 0, $batchSize);
	    /* Load the children */
	    list ($ret, $childItems) = GalleryCoreApi::loadEntitiesById($currentChildIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Get and delete all current resizes */
	    list ($ret, $resizesTable) = GalleryCoreApi::fetchResizesByItemIds($currentChildIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    foreach ($resizesTable as $resizes) {
		if (!(++$ind % $step)) {
		    $message = $module->translate(array('text' => 'Processing image %d of %d',
							'arg1' => $ind, 'arg2' => $total));
		    $ret = $templateAdapter->updateProgressBar($message,
			    $module->translate('Rebuilding resized images...'), $ind / $total);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
		foreach ($resizes as $resize) {
		    $ret = GalleryCoreApi::deleteEntityById($resize->getId());
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }

	    /* Add the new resizes */
	    foreach ($childItems as $child) {
		if (!(++$ind % $step)) {
		    $message = $module->translate(array('text' => 'Processing image %d of %d',
							'arg1' => $ind, 'arg2' => $total));
		    $ret = $templateAdapter->updateProgressBar($message,
			    $module->translate('Rebuilding resized images...'), $ind / $total);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
		if (!GalleryUtilities::isA($child, 'GalleryDataItem')) {
		    continue;
		}

		list ($ret, $source) = GalleryCoreApi::fetchPreferredSource($child);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/* Make sure that we have a toolkit before adding back the resizes */
		list ($ret, $toolkit, $outputMimeType) =
		    GalleryCoreApi::getToolkitByOperation($source->getMimeType(), 'scale');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (isset($toolkit)) {
		    $albumId = $child->getParentId();
		    if (empty($resizes[$albumId])) {
			/* Keep resizes for albums in memory */
			list ($ret, $preferences) =
			    GalleryCoreApi::fetchDerivativePreferencesForItem($albumId);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			foreach ($preferences as $preference) {
			    if (preg_match('/(?:resize|scale)\|(\d+)(?:,(\d+))?/',
					   $preference['derivativeOperations'],
					   $matches)) {
				$width = $matches[1];
				$height = empty($matches[2]) ? $width : $matches[2];
			    }

			    if ($preference['derivativeType'] == DERIVATIVE_TYPE_IMAGE_RESIZE
				    && !empty($width)) {
				$resizes[$albumId][] =
				    array('width' => $width, 'height' => $height);
			    }
			}
		    }
		    if (empty($resizes[$albumId])) {
			return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
		    }
		    $resize = $resizes[$albumId];
		    for ($i = 0; $i < sizeof($resize); $i++) {
			/* Special case to make sure that we don't upsample photos */
			if (GalleryUtilities::isA($child, 'GalleryPhotoItem')) {
			    if ($child->getWidth() <= $resize[$i]['width']
				    && $child->getHeight() <= $resize[$i]['height']) {
				continue;
			    }
			}

			list ($ret, $derivative) =
			    GalleryCoreApi::newFactoryInstanceByHint('GalleryDerivative',
								     $source->getEntityType());
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			if (!isset($derivative)) {
			    return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
			}

			$ret = $derivative->create($child->getId(), DERIVATIVE_TYPE_IMAGE_RESIZE);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$derivative->setMimeType($outputMimeType);
			$derivative->setDerivativeSourceId($source->getId());
			$derivative->setDerivativeOperations('scale|' .
			    $resize[$i]['width'] . ',' . $resize[$i]['height']);

			$ret = GalleryCoreApi::estimateDerivativeDimensions($derivative, $source);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$ret = $derivative->save();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }
	    $runningTotal += (sizeof($currentChildIds) * 2);
	    $ret = $storage->checkPoint();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}
	$ret = $templateAdapter->updateProgressBar(
	    $module->translate('Recreating resized images'), 'Complete', 1);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$redirect['view'] = 'core.ItemAdmin';
	$redirect['subView'] = 'core.ItemEdit';
	$redirect['itemId'] = $item->getId();

	$urlGenerator =& $gallery->getUrlGenerator();
	$templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));

	return null;
    }

    function runRecreateThumbnails($form, $item, $preferred, $status) {
	global $gallery;
	$storage =& $gallery->getStorage();

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$templateAdapter =& $gallery->getTemplateAdapter();
	$ret = $templateAdapter->updateProgressBar(
	    $module->translate('Rebuilding thumbnails...'), '', 0);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Get the child id => child types */
	list ($ret, $childIds) = GalleryCoreApi::fetchDescendentItemIds($item);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$batchSize = 100;
	$childItems = array();
	$total = sizeof($childIds);
	$ind = 0;
	$step = min(500, intval($total/20));
	$thumbnailSizes = array();
	while (!empty($childIds)) {
	    $currentChildIds = array_splice($childIds, 0, $batchSize);
	    /* Load the children */
	    list ($ret, $childItems) = GalleryCoreApi::loadEntitiesById($currentChildIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    /* Load the thumbnail of the children */
	    list ($ret, $thumbTable) = GalleryCoreApi::fetchThumbnailsByItemIds($currentChildIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $lockIds = array();
	    foreach ($childItems as $child) {
		if (!(++$ind % $step) || $ind == $total) {
		    $message = $module->translate(array('text' => 'Processing image %d of %d',
							'arg1' => $ind, 'arg2' => $total));
		    $ret = $templateAdapter->updateProgressBar(
			$message, $module->translate('Rebuilding thumbnails...'), $ind / $total);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		$childId = $child->getId();
		$albumId = $child->getParentId();
		if (empty($thumbnailSizes[$albumId])) {
		    /* Keep thumbnail sizes for albums in memory */
		    list ($ret, $preferences) =
			GalleryCoreApi::fetchDerivativePreferencesForItem($albumId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    foreach ($preferences as $preference) {
			if (preg_match('/thumbnail\|(\d+)/',
			    $preference['derivativeOperations'], $matches)) {
			    $thumbnailSizes[$albumId] = $matches[1];
			    break;
			}
		    }
		    if (empty($thumbnailSizes[$albumId])) {
			return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
		    }
		}
		$thumbnailSize = $thumbnailSizes[$albumId];
		$thumbnail = null;
		if (isset($thumbTable[$childId])) {
		    /* We already have a thumbnail */
		    $thumbnail = $thumbTable[$childId];
		    $sourceId = $thumbnail->getDerivativeSourceId();

		    /* Load the source of the thumbnail */
		    list($ret, $source) = GalleryCoreApi::loadEntitiesById($sourceId);
		    if ($ret && $ret->getErrorCode() & ERROR_MISSING_OBJECT) {
			/*
			 * Someone deleted the source image, we can do
			 * nothing but delete the thumbnail
			 */
			list ($ret, $lockIds[]) =
			    GalleryCoreApi::acquireWriteLock($thumbnail->getId());
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			$ret = $thumbnail->delete();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			continue;
		    } else if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $operation = preg_replace('/((^|;)thumbnail)\|\d+/',
					      '$1|' . $thumbnailSize,
					      $thumbnail->getDerivativeOperations());
		} else {
		    /*
		     * There is no thumbnail yet (maybe the file was uploaded
		     * when there was no graphic toolkit). Build new thumbnail
		     * from source if its a GalleryDataItem (with mimeType)
		     */
		    if (!GalleryUtilities::isA($child, 'GalleryDataItem')) {
			/* Its an album or something else, we can't make a thumbnail */
			continue;
		    }

		    list ($ret, $source) = GalleryCoreApi::fetchPreferredSource($child);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    list ($ret, $thumbnail) =
			GalleryCoreApi::newFactoryInstanceByHint('GalleryDerivative',
								 $source->getEntityType());
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $ret = $thumbnail->create($child->getId(), DERIVATIVE_TYPE_IMAGE_THUMBNAIL);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $operation = 'thumbnail|' . $thumbnailSize;
		}

		if ($thumbnail == null) {
		    return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
		}

		/* Change the thumbnail */
		list ($ret, $operation, $outputMimeType) =
		    GalleryCoreApi::makeSupportedViewableOperationSequence(
			$source->getMimeType(), $operation);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (!empty($operation)) {
		    $thumbnail->setMimeType($outputMimeType);
		    $thumbnail->setDerivativeSourceId($source->getId());
		    $thumbnail->setDerivativeOperations($operation);
		    $thumbnail->expireCache();

		    if ($thumbnail->isModified()) {
			list ($ret, $lockIds[]) =
			    GalleryCoreApi::acquireWriteLock($thumbnail->getId());
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			$ret = GalleryCoreApi::estimateDerivativeDimensions($thumbnail, $source);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$ret = $thumbnail->save();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }
	    $ret = GalleryCoreApi::releaseLocks($lockIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $ret = $storage->checkPoint();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}
	$redirect['view'] = 'core.ItemAdmin';
	$redirect['subView'] = 'core.ItemEdit';
	$redirect['itemId'] = $item->getId();

	$urlGenerator =& $gallery->getUrlGenerator();
	$templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));
	return null;
    }

    /**
     * @see ItemEditPlugin::isSupported
     */
    function isSupported($item, $thumbnail) {
	return (GalleryUtilities::isA($item, 'GalleryAlbumItem'));
    }

    /**
     * @see ItemEditPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Album'));
    }
}
?>
