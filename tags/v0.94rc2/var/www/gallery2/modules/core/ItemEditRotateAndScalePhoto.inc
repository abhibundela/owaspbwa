<?php
/*
 * $RCSfile: ItemEditRotateAndScalePhoto.inc,v $
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
 * @version $Revision: 1.22 $ $Date: 2006/01/13 03:39:31 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the editing of a photo
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemEditRotateAndScalePhoto extends ItemEditPlugin {

    /**
     * @see ItemEditPlugin::handleRequest
     */
    function handleRequest($form, &$item, &$preferred) {
	global $gallery;

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	}

	$status = null;
	$error = array();

	$operation = null;
	/* Figure out which command we're taking */
	if (isset($form['action']['resize'])) {
	    if (empty($form['resize']['width']) || empty($form['resize']['height'])) {
		$error[] = 'form[error][resize][size][missing]';
	    } else if (!is_numeric($form['resize']['width']) || $form['resize']['width'] < 1
		       || !is_numeric($form['resize']['height']) || $form['resize']['height'] < 1) {
		$error[] = 'form[error][resize][size][invalid]';
	    } else {
		$operation = 'scale';
		$args = array($form['resize']['width'], $form['resize']['height']);
	    }
	} else if (isset($form['action']['rotate']['clockwise'])) {
	    $operation = 'rotate';
	    $args = array(90);
	} else if (isset($form['action']['rotate']['counterClockwise'])) {
	    $operation = 'rotate';
	    $args = array(-90);
	} else if (isset($form['action']['rotate']['flip'])) {
	    $operation = 'rotate';
	    $args = array(180);
	} else if (isset($form['action']['revertToOriginal'])) {
	    if (!empty($preferred)) {
		/*
		 * Pull out the rotate and resize operations and let our change ripple
		 * down the derivative tree, if necessary
		 */
		$remainingOperations = array();
		foreach (split(';', $preferred->getDerivativeOperations()) as $tmpOperation) {
		    if (preg_match("/^(rotate|resize|scale)\|/", $tmpOperation)) {
			$ret = GalleryCoreApi::adjustDependentDerivatives($preferred->getId(),
									  $tmpOperation,
									  true);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			}
		    } else {
			$remainingOperations[] = $tmpOperation;
		    }
		}

		list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($preferred->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
		list ($ret, $preferred) = $preferred->refresh();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		if ($remainingOperations) {
		    $preferred->setDerivativeOperations(join(';', $remainingOperations));
		} else {
		    $preferred->setDerivativeOperations(null);
		}

		$ret = $preferred->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		if ($preferred->hasNoOperations()) {
		    $ret = GalleryCoreApi::remapSourceIds(
			$preferred->getId(), $preferred->getDerivativeSourceId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $ret = GalleryCoreApi::deleteEntityById($preferred->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}

		/* Prepare our status message */
		$status = $module->translate('Reverted rotate and scale changes successfully');
	    }
	}

	/*
	 * If we have a command, then apply it to the correct object.
	 */
	if (!empty($operation)) {
	    if ($item->isLinked()) {
		$isLinked = true;
		$isLinkedTo = false;
	    } else {
		list ($ret, $linkedIds) = GalleryCoreApi::fetchEntitiesLinkedTo($item->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$isLinked = false;
		$isLinkedTo = !empty($linkedIds);
	    }

	    /* Force us to preserve original if we're a link */
	    $preserveOriginal = isset($form['preserveOriginal']);
	    if (!$preserveOriginal && ($isLinked || $isLinkedTo)) {
		$preserveOriginal = true;
	    }

	    /* If we don't have a preferred and we're preserving, then create one */
	    $lockIds = array();

	    if (empty($preferred) && !$preserveOriginal) {
		/*
		 * We have no preferred photo, and we're not preserving the
		 * original.  So, we're changing the original photo here.  Make
		 * the appropriate change, then expire the derivative tree.
		 *
		 * Note that we are explicitly not allowing linked items to execute
		 * this code!
		 */

		/* Get the path of the source */
		list($ret, $sourcePath) = $item->fetchPath();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		/* Get the appropriate toolkit */
		list ($ret, $toolkit) =
		    GalleryCoreApi::getToolkitByOperation($item->getMimeType(), $operation);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		if (!isset($toolkit)) {
		    /* This should never happen */
		    return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__),
				 null, null, null);
		}

		/* Perform the operation */
		list ($ret, $outputMimeType) = $toolkit->performOperation(
		    $item->getMimeType(), $operation, $sourcePath, $sourcePath, $args);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
		$item->setMimeType($outputMimeType);

		/* Get the item to rescan its data object */
		$ret = $item->rescan();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		/* Let our change ripple down the derivative tree, if necessary */
		$ret = GalleryCoreApi::adjustDependentDerivatives(
		    $item->getId(), sprintf('%s|%s', $operation, join(',', $args)));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		$gallery->debug('checking is modified');
		/* Save the item, if it's modified */
		if ($item->isModified()) {
		    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($item->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $item->setSerialNumber($form['serialNumber']);

		    $ret = $item->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $ret = GalleryCoreApi::releaseLocks($lockId);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}

		$ret = GalleryCoreApi::expireDerivativeTreeBySourceIds(array($item->getId()));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
	    } else {
		/* If we have no preferred, then create one */
		if (empty($preferred)) {
		    list ($ret, $preferred) = GalleryCoreApi::newFactoryInstanceByHint(
			'GalleryDerivative', $item->getEntityType());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    if (!isset($preferred)) {
			return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT,
							   __FILE__, __LINE__), null, null, null);
		    }

		    $ret = $preferred->create($item->getId(), DERIVATIVE_TYPE_IMAGE_PREFERRED);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $preferred->setDerivativeSourceId($item->getId());
		    $preferred->setMimeType($item->getMimeType());

		    $ret = GalleryCoreApi::remapSourceIds($item->getId(), $preferred->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		} else {
		    /*
		     * Otherwise, lock the preferred so that we can modify it
		     */
		    list ($ret, $lockIds[]) = GalleryCoreApi::acquireWriteLock($preferred->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $ret = $preferred->expireCache();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}

		$operationString = $operation . '|' . join(',', $args);
		list ($ret, $operations) = GalleryCoreApi::mergeDerivativeOperations(
		    $preferred->getDerivativeOperations(), $operationString);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}
		$preferred->setDerivativeOperations($operations);

		/* Let our change ripple down the derivative tree, if necessary */
		$ret = GalleryCoreApi::adjustDependentDerivatives(
		    $preferred->getId(), $operationString);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		/*
		 * Perform a final check -- if the new derivative is exactly
		 * the same as the original then delete it.
		 */
		if ($preferred->hasNoOperations()) {
		    $ret = GalleryCoreApi::remapSourceIds(
			$preferred->getId(), $preferred->getDerivativeSourceId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }

		    $ret = GalleryCoreApi::deleteEntityById($preferred->getId());
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		} else {
		    $ret = $preferred->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockIds);
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}

		if (!empty($lockIds)) {
		    $ret = GalleryCoreApi::releaseLocks($lockIds);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		}
	    }

	    /* Set our status message */
	    switch ($operation) {
	    case 'rotate':
		$status = $module->translate('Rotated photo successfully');
		break;

	    case 'scale':
		$status = $module->translate('Scaled photo successfully');
		break;
	    }
	}

	return array(null, $error, $status, false);
    }

    /**
     * @see ItemEditPlugin::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	global $gallery;

	$ItemEditRotateAndScalePhoto = array();

	list ($ret, $ItemEditRotateAndScalePhoto['isAdmin']) =
	    GalleryCoreApi::isUserInSiteAdminGroup();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($form['formName'] != 'ItemEditRotateAndScalePhoto') {
	    /* First time around, reset the form */
	    $form['resize']['width'] = $form['resize']['height'] = '';
	    $form['preserveOriginal'] = 1;
	    $form['formName'] = 'ItemEditRotateAndScalePhoto';
	}

	if ($item->isLinked()) {
	    $ItemEditRotateAndScalePhoto['editPhoto']['isLinked'] = true;
	    $ItemEditRotateAndScalePhoto['editPhoto']['isLinkedTo'] = false;
	} else {
	    list ($ret, $linkedIds) = GalleryCoreApi::fetchEntitiesLinkedTo($item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    $ItemEditRotateAndScalePhoto['editPhoto']['isLinkedTo'] = !empty($linkedIds);
	    $ItemEditRotateAndScalePhoto['editPhoto']['isLinked'] = false;
	}

	/* Check to see if we have a preferred source */
	list ($ret, $results) =
	    GalleryCoreApi::fetchPreferredsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	$sourceMimeTypes = array($item->getMimeType());
	if (empty($results)) {
	    $ItemEditRotateAndScalePhoto['editPhoto']['hasPreferredSource'] = false;
	} else {
	    $preferred = $results[$item->getId()];
	    if (preg_match("/(rotate|resize|scale)\|/", $preferred->getDerivativeOperations())) {
		$ItemEditRotateAndScalePhoto['editPhoto']['hasPreferredSource'] = true;
	    } else {
		$ItemEditRotateAndScalePhoto['editPhoto']['hasPreferredSource'] = false;
	    }
	    $sourceMimeTypes[] = $preferred->getMimeType();
	}

	/* Figure out what options we can provide */
	list ($ret, $ItemEditRotateAndScalePhoto['editPhoto']['can']['rotate']) =
	    $this->_checkForOperation('rotate', $sourceMimeTypes);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	list ($ret, $ItemEditRotateAndScalePhoto['editPhoto']['can']['resize']) =
	    $this->_checkForOperation('scale', $sourceMimeTypes);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	$template->setVariable('ItemEditRotateAndScalePhoto', $ItemEditRotateAndScalePhoto);
	$template->setVariable('controller', 'core.ItemEditRotateAndScalePhoto');
	return array(null,
		     'modules/core/templates/ItemEditRotateAndScalePhoto.tpl', 'modules_core');
    }

    /**
     * @see ItemEditPlugin::isSupported
     */
    function isSupported($item, $thumbnail) {
	return (GalleryUtilities::isA($item, 'GalleryPhotoItem'));
    }

    /**
     * @see ItemEditPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Modify Photo'));
    }

    /**
     * Check to see if a given operation is available for any of a set of mime types
     *
     * @param string the operation (eg, 'rotate', or 'scale')
     * @param array mime types
     * @return array object GalleryStatus a status code
     *               bool true if any of the mime types are supported
     */
    function _checkForOperation($operation, $mimeTypes) {
	foreach (array_unique($mimeTypes) as $mimeType) {
	    list ($ret, $toolkit) = GalleryCoreApi::getToolkitByOperation($mimeType, $operation);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (isset($toolkit)) {
		break;
	    }
	}

	return array(null, isset($toolkit));
    }
}
?>
