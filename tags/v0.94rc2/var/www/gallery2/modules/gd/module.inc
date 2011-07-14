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
 * @version $Revision: 1.38 $ $Date: 2006/03/22 03:52:31 $
 * @package Gd
 * @author Ernesto Baschny <ernst@baschny.de>
 */

/**
 * Gd Graphics Module
 *
 * This module provides the Gd graphics toolkit for Gallery
 *
 * @package Gd
 */
class GdModule extends GalleryModule {

    function GdModule() {
	global $gallery;

	$this->setId('gd');
	$this->setName($gallery->i18n('Gd'));
	$this->setDescription($gallery->i18n('Gd Graphics Toolkit'));
	$this->setVersion('1.0.3');
	$this->setGroup('toolkits', $gallery->i18n('Graphics Toolkits'));
	$this->setCallbacks('getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	if (!isset($currentVersion)) {
	    /* Initial install */
	    $ret = $this->setParameter('jpegQuality', '75');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}

	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	/* Register our graphics class with the factory */
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'GalleryToolkit', 'GdToolkit', 'Gd',
	    'modules/gd/classes/GdToolkit.class', 'gd', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::activate
     */
    function activate($postActivationEvent=true) {
	/* Load any classes we require */
	GalleryCoreApi::requireOnce('modules/gd/classes/GdToolkitHelper.class');

	/* Find out what operations and properties we have available to us */
	list ($ret, $results) =
	    GdToolkitHelper::getOperationsAndProperties();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $priority) = GalleryCoreApi::getToolkitPriorityById('Gd');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if (!$priority) {
	    list ($ret, $priority) = GalleryCoreApi::getMaximumManagedToolkitPriority();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $priority++;
	}

	foreach ($results['operations'] as $operation => $info) {
	    $outputMimeType = isset($info['outputMimeType']) ? $info['outputMimeType'] : null;
	    $ret = GalleryCoreApi::registerToolkitOperation('Gd',
							    $info['mimeTypes'],
							    $operation,
							    $info['params'],
							    $info['description'],
							    $outputMimeType, $priority);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	foreach ($results['properties'] as $property => $info) {
	    $ret = GalleryCoreApi::registerToolkitProperty('Gd',
							   $info['mimeTypes'],
							   $property,
							   $info['type'],
							   $info['description']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	list ($ret, $redirect) = parent::activate($postActivationEvent);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $redirect);
    }

    /**
     * @see GalleryModule::deactivate
     */
    function deactivate($postDeactivationEvent=true) {
	/*
	 * Unregister all of our properties and operations.  Do this before the parent deactivates
	 * so that any event handlers triggered by the deactivation will see the world as it will
	 * be after the deactivation is done.
	 */
	$ret = GalleryCoreApi::unregisterToolkit('Gd');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $redirect) = parent::deactivate($postDeactivationEvent);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $redirect);
    }

    /**
     * @see GalleryModule::needsConfiguration
     */
    function needsConfiguration() {
	/* This module requires all fields to be filled out before it can be activated. */
	list ($ret, $value) = $this->getParameter('jpegQuality');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (empty($value)) {
	    return array(null, true);
	}

	/*
	 * It also will stay requiring configuration if we don't have a
	 * GD version with which we can work with.
	 */
	GalleryCoreApi::requireOnce('modules/gd/classes/GdToolkitHelper.class');

	list($ret, $isSupported) = GdToolkitHelper::isGdVersionSupported();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!$isSupported) {
	    return array(null, true);
	}

	return array(null, false);
    }

    /**
     * @see GalleryModule::autoConfigure
     */
    function autoConfigure() {
	list ($ret, $needsConfiguration) = $this->needsConfiguration();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), false);
	}

	return array(null, !$needsConfiguration);
    }

    /**
     * @see GalleryModule::getSiteAdminViews
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('Gd'),
				 'view' => 'gd.AdminGd')));
    }

    /**
     * @see GalleryModule::getConfigurationView
     */
    function getConfigurationView() {
	return 'gd.AdminGd';
    }
}
?>
