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
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/**
 * @version $Revision: 1.4.2.1 $ $Date: 2006/04/05 00:19:57 $
 * @package Picasa
 * @author Waldemar Schlackow <gallery@schlackow.de>
 */
class PicasaModule extends GalleryModule {

    function PicasaModule() {
	global $gallery;
	$this->setId('picasa');
	$this->setName($gallery->i18n('Picasa'));
	$this->setDescription($gallery->i18n('Import for Picasa 2 XML-Exports'));
	$this->setVersion('1.0.3');
	$this->setGroup('import', $this->translate('Import'));
	$this->setCallbacks('getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation(
    	    'ItemAddPlugin', 'ItemAddFromPicasa', 'ItemAddFromPicasa',
    	    'modules/picasa/ItemAddFromPicasa.inc', 'picasa', null);
    	if ($ret) {
    	    return $ret->wrap(__FILE__, __LINE__);
    	}
    	return null;
    }


    /**
     * @see GalleryModule::getSiteAdminViews
     */
    function getSiteAdminViews() {
	return array(null, array(array('name' => $this->translate('Picasa 2'),
				       'view' => 'picasa.SelectPicasaExportPath')));
    }
}
?>
