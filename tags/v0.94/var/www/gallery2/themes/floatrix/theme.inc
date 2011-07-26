<?php
/*
 * $RCSfile: theme.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/03/22 03:52:37 $
 * @package Gallery
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This implements the standard gallery theme
 *
 * @package GalleryTheme
 * @subpackage Theme
 */
class FloatrixTheme extends GalleryTheme {

    /**
     * Constructor
     */
    function FloatrixTheme() {
	global $gallery;

	$this->setId('floatrix');
	$this->setName($gallery->i18n('Floatrix'));
	$this->setDescription($gallery->i18n('Variable column theme based on Matrix'));
	$this->setVersion('1.0.2');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredThemeApi(array(2, 3));
	$this->setStandardSettings(
	    array('perPage' => 12,
		  'columnWidth' => 200, 'rowHeight' => 300,
		  'showImageOwner' => 0, 'showAlbumOwner' => 1,
		  'albumFrame' => '', 'itemFrame' => '', 'photoFrame' => '',
		  'colorpack' => '',
		  'showMicroThumbs' => 0,
		  'sidebarBlocks' => serialize(array(
			array('core.ItemLinks', array('useDropdown' => false)),
			array('core.PeerList', array()),
			array('imageblock.ImageBlock', array()))),
		  'albumBlocks' => serialize(array(
			array('comment.ViewComments', array()))),
		  'photoBlocks' => serialize(array(
			array('exif.ExifInfo', array()),
			array('comment.ViewComments', array())))));
    }

    /**
     * @see GalleryTheme::validateSettings
     */
    function validateSettings($settings) {
	$error = parent::validateSettings($settings);
	if (empty($settings['columnWidth'])) {
	    $error['columnWidth'] = $this->translate('You must enter a number');
	}
	if (empty($settings['rowHeight'])) {
	    $error['rowHeight'] = $this->translate('You must enter a number');
	}
	return $error;
    }

    /**
     * @see GalleryTheme::showAlbumPage
     */
    function showAlbumPage(&$template, $item, $params, $childIds) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params,
	    array('owner', 'viewCount', 'childCount', 'descendentCount', 'parents',
	      'systemLinks', 'itemLinks', 'itemSummaries', 'permissions',
	      'thumbnails', 'pageNavigator', 'jumpRange'),
	     $childIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$ret = $this->preloadBlock($template, 'search.SearchBlock');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$this->_splitItemLinks($template);
	return array(null, 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showPhotoPage
     */
    function showPhotoPage(&$template, $item, $params) {
	$dataTypes = array('owner', 'parents', 'systemLinks', 'itemLinks', 'permissions',
			   'itemLinksDetailed', 'itemNavigator', 'imageViews');
	if (!empty($params['showMicroThumbs'])) {
	    $dataTypes[] = 'navThumbnails';
	}
	$ret = $this->loadCommonTemplateData($template, $item, $params, $dataTypes);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$ret = $this->preloadBlock($template, 'search.SearchBlock');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$this->_splitItemLinks($template);
	return array(null, 'theme.tpl');
    }

    /**
     * Split item links into user and admin actions
     */
    function _splitItemLinks(&$template) {
	$theme =& $template->getVariableByReference('theme');
	$userViews = array(
		'slideshow.Slideshow' => true,
		'slideshowapplet.SlideshowApplet' => true,
		'comment.AddComment' => true,
		'comment.ShowAllComments' => true,
		'rss.SimpleRender' => true,
		);
	$theme['userLinks'] = array();
	foreach ($theme['itemLinks'] as $i => $link) {
	    if (isset($link['params']['view']) && isset($userViews[$link['params']['view']])) {
		$theme['userLinks'][] = $link;
		unset($theme['itemLinks'][$i]);
	    }
	}
    }

    /**
     * @see GalleryTheme::showModulePage
     */
    function showModulePage(&$template, $item, $params, $templateFile) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showAdminPage
     */
    function showAdminPage(&$template, $item, $params, $templateFile) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showErrorPage
     */
    function showErrorPage(&$template) {
	return array(null, 'error.tpl');
    }

    /**
     * @see GalleryTheme::showProgressBarPage
     */
    function showProgressBarPage(&$template, $item, $params) {
	$ret = $this->loadCommonTemplateData(
	    $template, $item, $params, array('parents', 'systemLinks'));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, 'theme.tpl');
    }
}
?>
