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
 * @version $Revision: 1.38 $ $Date: 2006/03/22 03:52:35 $
 * @package Remote
 * @author Pierre-Luc Paour <paour@users.sourceforge.net>
 */

class RemoteModule extends GalleryModule {

    function RemoteModule() {
	global $gallery;
	$this->setId('remote');
	$this->setName($gallery->i18n('Remote'));
	$this->setDescription($gallery->i18n('Implementation for the remote control protocol'));
	$this->setVersion('1.0.6');
	$this->setGroup('import', $gallery->i18n('Import'));
	$this->setCallbacks('getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	list ($ret, $params) = GalleryCoreApi::fetchAllPluginParameters('module', 'remote');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	foreach (array('showItemAdd' => 1) as $key => $value) {
	    if (!isset($params[$key])) {
		$ret = $this->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	/* Register our item add plugins */
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'ItemAddPlugin', 'ItemAddGalleryRemote', 'ItemAddGalleryRemote',
	    'modules/remote/ItemAddGalleryRemote.inc', 'remote', null);
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
		     array(array('name' => $this->translate('Remote'),
				 'view' => 'remote.RemoteSiteAdmin')));
    }
}
?>
