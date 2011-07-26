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
 * @version $Revision: 1.38 $ $Date: 2006/03/22 03:52:36 $
 * @package Slideshow
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * The implementation of the Slideshow module
 *
 * @package Slideshow
 */
class SlideshowModule extends GalleryModule {

    function SlideshowModule() {
	global $gallery;
	$this->setId('slideshow');
	$this->setName($gallery->i18n('Slideshow'));
	$this->setDescription($gallery->i18n('Slideshow'));
	$this->setVersion('1.0.3');
	$this->setGroup('display', $gallery->i18n('Display'));
	$this->setCallbacks('getItemLinks');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'SlideshowInterface_1_0', 'SlideshowImpl', 'Slideshow',
	    'modules/slideshow/classes/SlideshowImpl.class', 'slideshow', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::getItemLinks()
     */
    function getItemLinks($items, $wantsDetailedLinks, $permissions) {
	$links = array();
	foreach ($items as $item) {
	    $itemId = $item->getId();
	    if (isset($wantsDetailedLinks[$itemId])) {
		$links[$itemId][] =
		    array('text' => $this->translate('View Slideshow'),
			  'params' => array(
			      'view' => 'slideshow.Slideshow',
			      'itemId' => $itemId));
	    }
	}
	return array(null, $links);
    }

    /**
     * @see GalleryModule::getRewriteRules
     */
    function getRewriteRules() {
	$rules = array();
	
	$rule = array();
	$rule['comment'] = $this->translate('View Slideshow');
	$rule['match'] = array('view' => 'slideshow.Slideshow');
	$rule['onLoad'] = array('rewrite', 'RewriteSimpleHelper', 'loadItemIdFromPath');
	$rule['pattern'] = 'v/%path%/slideshow.html';
        $rule['keywords'] = array(
            'path' => array(
                'pattern' => '([^?]+)',
		'function' => array('rewrite', 'RewriteSimpleHelper', 'parsePath'),
                'help' => $this->translate('Path to an item (eg, /album/image.jpg.html)')));
	$rules[] = $rule;

	return $rules;
    }
}
?>
