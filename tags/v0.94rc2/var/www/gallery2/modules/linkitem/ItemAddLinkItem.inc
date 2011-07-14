<?php
/*
 * $RCSfile: ItemAddLinkItem.inc,v $
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
 * @version $Revision: 1.2 $ $Date: 2006/01/10 04:40:35 $
 * @package LinkItem
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */
class ItemAddLinkItem extends ItemAddPlugin {

    /**
     * @see ItemAddPlugin::handleRequest
     */
    function handleRequest($form, &$item) {
	global $gallery;

	$status = $error = array();
	if (isset($form['action']['addLinkItem'])) {
	    if ($form['linkType'] == 'album' && empty($form['linkedAlbumId'])) {
		$error[] = 'form[error][linkedAlbumId][missing]';
	    } else if ($form['linkType'] == 'url' && empty($form['linkUrl'])) {
		$error[] = 'form[error][linkUrl][missing]';
	    }

	    if (empty($error)) {
		list ($ret, $lockId) = GalleryCoreApi::acquireReadLock($item->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}

		list ($ret, $newItem) =
		    GalleryCoreApi::newFactoryInstance('GalleryEntity', 'GalleryLinkItem');
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockId);
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}
		if (empty($newItem)) {
		    GalleryCoreApi::releaseLocks($lockId);
		    return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__),
				 null, null);
		}

		$link = ($form['linkType'] == 'album') ? (int)$form['linkedAlbumId']
						       : $form['linkUrl'];
		$ret = $newItem->create($item->getId(), $link);
		if ($ret) {
		    if (($ret->getErrorCode() & ERROR_MISSING_OBJECT) ||
			    ($ret->getErrorCode() & ERROR_BAD_PARAMETER)) {
			$error[] = 'form[error][linkedAlbumId][invalid]';
		    } else {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		}

		if (empty($error)) {
		    $ret = $newItem->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    /* set order weight -- add to end of parent album */
		    list ($ret, $maxWeight) =
			GalleryCoreApi::fetchExtremeChildWeight($item->getId(), HIGHER_WEIGHT);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $ret = GalleryCoreApi::setItemOrderWeight($newItem->getId(), $maxWeight + 1000);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }

		    /* add thumbnail */
		    $source = $newItem;
		    if ($form['linkType'] == 'album') {
			list ($ret, $thumbnailTable) =
			    GalleryCoreApi::fetchThumbnailsByItemIds(array($link));
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockId);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}
			if (!empty($thumbnailTable)) {
			    $source = array_shift($thumbnailTable);
			}
		    }
		    list ($ret, $derivative) = GalleryCoreApi::newFactoryInstanceByHint(
			'GalleryDerivative', $source->getEntityType());
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    if (!isset($derivative)) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT,
							  __FILE__, __LINE__), null, null);
		    }
		    $ret = $derivative->create($newItem->getId(), DERIVATIVE_TYPE_IMAGE_THUMBNAIL);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    list ($ret, $preferences) =
			GalleryCoreApi::fetchDerivativePreferencesForItem($item->getId());
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    foreach ($preferences as $preference) {
			if ($preference['derivativeType'] == DERIVATIVE_TYPE_IMAGE_THUMBNAIL) {
			    $derivativeOperations = $preference['derivativeOperations'];
			    break;
			}
		    }
		    if (empty($derivativeOperations)) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array(GalleryCoreApi::error(ERROR_MISSING_VALUE,
							  __FILE__, __LINE__), null, null);
		    }
		    list ($ret, $isSupported, $outputMimeType) =
			GalleryCoreApi::isSupportedOperationSequence(
			    $source->getMimeType(), $derivativeOperations);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    if ($isSupported) {
			$derivative->setDerivativeOperations($derivativeOperations);
			$derivative->setMimeType($outputMimeType);

			$watermarkOp = 'composite|plugins_data/modules/linkitem/arrow.png,' .
				       'image/png,14,14,bottom-right,0,0';
			list ($ret, $isSupported, $outputMimeType) =
			    GalleryCoreApi::isSupportedOperationSequence(
				$outputMimeType, $watermarkOp);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockIds);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}
			if ($isSupported) {
			    $derivative->setPostFilterOperations($watermarkOp);
			    $derivative->setMimeType($outputMimeType);
			}

			$derivative->setDerivativeSourceId($source->getId());
			$derivative->setWidth(0);
			$derivative->setHeight(0);
			$ret = GalleryCoreApi::estimateDerivativeDimensions($derivative, $source);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockIds);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}
			$ret = $derivative->save();
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockIds);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}

			/* Set as album highlight if no highlight yet */
			list ($ret, $thumbnailTable) =
			    GalleryCoreApi::fetchThumbnailsByItemIds(array($item->getId()));
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockIds);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}
			if (empty($thumbnailTable)) {
			    list ($ret) = GalleryCoreApi::setThumbnailFromItem(
							  $item->getId(), $newItem->getId());
			    if ($ret) {
				GalleryCoreApi::releaseLocks($lockIds);
				return array($ret->wrap(__FILE__, __LINE__), null, null);
			    }
			}
		    }

		    $fileName = $newItem->getTitle();
		    $status['addedFiles'][] = array('fileName' => $fileName,
						    'id' => $newItem->getId(),
						    'warnings' => array());
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}
	    }
	}

	return array(null, $error, $status);
    }

    /**
     * @see ItemAdd::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	if ($form['formName'] != 'ItemAddLinkItem') {
	    $form['formName'] = 'ItemAddLinkItem';
	    $form['linkType'] = 'album';
	    $form['linkedAlbumId'] = $form['linkUrl'] = '';
	}

	list ($ret, $albumIds) = GalleryCoreApi::fetchAllItemIds('GalleryAlbumItem', 'core.view');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	list ($ret, $albums) = GalleryCoreApi::loadEntitiesById($albumIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	$template->setVariable('ItemAddLinkItem',
	    array('albumTree' => GalleryUtilities::createAlbumTree($albums)));

	return array(null,
		     'modules/linkitem/templates/ItemAddLinkItem.tpl', 'modules_linkitem');
    }

    /**
     * @see ItemAddPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'linkitem');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Link'));
    }
}
?>
