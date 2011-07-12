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
 * Module to allow publishing from XP directly to Gallery.
 *
 * This module allows the user to register Gallery with Windows XP as a web service
 * allowing photos to be directly uploaded from XP.  To use the service, the user
 * has to download a registry file and install it.  Once installed, the user can use
 * the Publish option.
 *
 * @version $Revision: 1.18 $ $Date: 2006/03/22 03:52:34 $
 * @package PublishXp
 * @author Timothy Webb <tiwebb@cisco.com>
 */
class PublishXpModule extends GalleryModule {
    /**
     * Constructs the PublishXpModule that is registered with Gallery.
     */
    function PublishXpModule() {
	global $gallery;
	$this->setId('publishxp');
	$this->setName($gallery->i18n('Publish XP'));
	$this->setDescription($gallery->i18n('Implementation for direct publishing from XP'));
	$this->setVersion('1.0.4');
	$this->setGroup('import', $gallery->i18n('Import'));
	$this->setCallbacks('');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * Registers the item add operation with Gallery.
     *
     * @see GalleryModule::performFactoryRegistrations
     */
    function performFactoryRegistrations() {
	GalleryCoreApi::registerFactoryImplementation(
	    'ItemAddPlugin', 'ItemAddPublishXp', 'ItemAddPublishXp',
	    'modules/publishxp/ItemAddPublishXp.inc', 'publishxp', null);

	return null;
    }
}
?>
