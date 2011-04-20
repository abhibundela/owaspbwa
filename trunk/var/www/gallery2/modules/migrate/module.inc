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
 * @version $Revision: 1.45 $ $Date: 2006/03/22 03:52:32 $
 * @package Migrate
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Migrate Module
 *
 * This module provides support for Migrating the Gallery
 *
 * @package Migrate
 */
class MigrateModule extends GalleryModule /* and GalleryEventListener */ {
    function MigrateModule() {
	global $gallery;

	$this->setId('migrate');
	$this->setName($gallery->i18n('Migration'));
	$this->setDescription($gallery->i18n('Migrate your Gallery 1 albums to Gallery 2'));
	$this->setVersion('1.0.4');
	$this->setGroup('import', $gallery->i18n('Import'));
	$this->setCallbacks('registerEventListeners|getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::registerEventListeners()
     */
    function registerEventListeners() {
	GalleryCoreApi::registerEventListener('GalleryEntity::delete', new MigrateModule(), true);
    }

    /**
     * @see GalleryEventListener::handleEvent
     */
    function handleEvent($event) {
	$item = $event->getEntity();
	if (GalleryUtilities::isA($item, 'GalleryItem')) {
	    $ret = GalleryCoreApi::removeMapEntry('G1MigrateMap',
						  array('itemId' => $item->getId()));
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}
	return array(null, null);
    }

    /**
     * @see GalleryModule::getSiteAdminViews
     */
    function getSiteAdminViews() {
	global $gallery;
	return array(null,
		     array(array('name' => $this->translate('Gallery 1'),
				 'view' => 'migrate.SelectGallery')));
    }

    /**
     * @see GalleryModule::getRewriteRules
     */
    function getRewriteRules() {
	global $gallery;
	$urlGenerator =& $gallery->getUrlGenerator();
	$url = $urlGenerator->generateUrl(array('view' => 'core.SiteAdmin',
						'subView' => 'migrate.SelectGallery'));
	return array(
	    array('comment' => $this->translate('G1-&gt;G2 Mapping'),
		  'pattern' => '%path%',
		  'parser' => 'preGallery',
		  'keywords' => array('path' => array('pattern' => '([^?]+)',
						      'help' => $this->translate('G1 path'))),
		  'queryString' => array('controller' => 'migrate.Redirect'),
		  'locked' => 1,
		  'help' =>$this->translate(
		      array('text' => 'G1 URL redirect. Use only if G2 is installed where G1 used' .
			    ' to be and you have run a G1 import with the URL redirect option ' .
			    'selected. Otherwise follow instructions in %sMigrate Site Admin%s.',
			    'arg1' => '<a href="' . $url . '">', 'arg2' => '</a>'))
	    )
	);
    }
}
?>
