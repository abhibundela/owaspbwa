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
 * @version $Revision: 1.5 $ $Date: 2006/03/22 03:52:30 $
 * @package Fotokasten
 * @author Ioana Ciocanescu <service@fotokasten.de>
 */

/**
 * Print photos on fotokasten.de
 *
 * @package Fotokasten
 */
class FotokastenModule extends GalleryModule {

    function FotokastenModule() {
	global $gallery;

	$this->setId('fotokasten');
	$this->setName($gallery->i18n('Fotokasten'));
	$this->setDescription($gallery->i18n('Fotokasten Photo Printing Module'));
	$this->setVersion('1.0.4');
	$this->setGroup('commerce', $this->translate('Commerce'));
	$this->setCallbacks('getSiteAdminViews|getItemLinks');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	global $gallery;

	list ($ret, $params) =
	    GalleryCoreApi::fetchAllPluginParameters('module', 'fotokasten');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	foreach (array('affiliateId' => '1927',
		       'affiliateIdPass' => 'f12a65d90445f95b90e5fd30c75ee74e')
		as $key => $value) {
	    if (!isset($params[$key])) {
		$ret = $this->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	if (!isset($currentVersion)) {
	    list ($ret, $coreParams) = GalleryCoreApi::fetchAllPluginParameters('module', 'core');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Register our permission */
	    $ret = GalleryCoreApi::registerPermission($this->getId(), 'fotokasten.print',
						      $gallery->i18n('[fotokasten] Print'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    /* Give everybody print permission by default */
	    $ret = GalleryCoreApi::addGroupPermission(
		$coreParams['id.rootAlbum'], $coreParams['id.everybodyGroup'],
		'fotokasten.print', true);
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
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'CartPluginInterface_1_0', 'FotokastenCartPlugin', 'fotokasten',
	    'modules/fotokasten/classes/FotokastenCartPlugin.class', 'fotokasten', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::getSiteAdminViews()
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('Fotokasten'),
				 'view' => 'fotokasten.FotokastenSiteAdmin')));
    }

    /**
     * @see GalleryModule::getItemLinks()
     */
    function getItemLinks($items, $wantsDetailedLinks, $permissions) {
	$links = array();

	foreach ($items as $item) {
	    $itemId = $item->getId();
	    if (isset($wantsDetailedLinks[$itemId]) &&
		    isset($permissions[$itemId]['fotokasten.print']) &&
		    GalleryUtilities::isA($item, 'GalleryPhotoItem')) {
		$links[$itemId][] =
		    array('text' => $this->translate('Print on Fotokasten.de'),
			  'params' => array('controller' => 'fotokasten.PrintPhotos',
					    'itemId' => $itemId, 'return' => 1));
	    }
	}

	return array(null, $links);
    }
}
?>
