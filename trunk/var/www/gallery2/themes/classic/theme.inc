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
 * @version $Revision: 1.22 $ $Date: 2006/03/22 03:52:37 $
 * @package Gallery
 * @author Bharat Mediratta <bharat@menalto.com>
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This implements the standard gallery theme
 *
 * @package GalleryTheme
 * @subpackage Theme
 */
class ClassicTheme extends GalleryTheme {

    /**
     * Constructor
     */
    function ClassicTheme() {
	global $gallery;

	$this->setId('classic');
	$this->setName($gallery->i18n('Classic'));
	$this->setDescription($gallery->i18n('Classic Gallery2 root album look and feel'));
	$this->setVersion('1.1.0');
	$this->setRequiredThemeApi(array(2, 2));
	$this->setRequiredCoreApi(array(7, 0));
	$this->setStandardSettings(
	    array('rows' => 10, 'columns' => 1,
		  'showImageOwner' => 0, 'showAlbumOwner' => 1,
		  'albumFrame' => '', 'itemFrame' => '', 'photoFrame' => '',
		  'showMicroThumbs' => 0,
		  'colorpack' => '',
		  'sidebarBlocks' => serialize(
		      array(
			  array('search.SearchBlock', array('showAdvancedLink' => true)),
			  array('core.ItemLinks', array('useDropdown' => false)),
			  array('core.PeerList', array()),
			  array('imageblock.ImageBlock', array()))),
		  'albumBlocks' => serialize(
		      array(array('comment.ViewComments', array()))),
		  'photoBlocks' => serialize(
		      array(
			  array('exif.ExifInfo', array()),
			  array('comment.ViewComments', array()))),
		  'showSubalbums' => 1, 'subalbumDepth' => 0, 'subalbumSort' => 0));
    }

    /**
     * @see GalleryTheme::getSettings()
     */
    function getSettings($itemId=null) {
	list ($ret, $settings, $params) = parent::getSettings($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Add in our custom settings */
	$settings[] = array('key' => 'showSubalbums',
			    'name' => $this->translate('Show subalbum links'),
			    'type' => 'checkbox',
			    'value' => $params['showSubalbums']);
	$settings[] = array('key' => 'subalbumDepth',
			    'name' => $this->translate('Depth of subalbums links (0=no limit)'),
			    'type' => 'text-field',
			    'typeParams' => array('size' => 2),
			    'value' => $params['subalbumDepth']);
	$settings[] = array('key' => 'subalbumSort',
			    'name' =>
	    $this->translate('Show subalbums by sort order of each album (affects performance)'),
			    'type' => 'checkbox',
			    'value' => $params['subalbumSort']);

	return array(null, $settings);
    }

    /**
     * @see GalleryTheme::validateSettings
     */
    function validateSettings($settings) {
	$error = parent::validateSettings($settings);
	if (!is_numeric($settings['subalbumDepth'])) {
	    $error['subalbumDepth'] = $this->translate('You must enter a number');
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

	/* Add in our extra stuff */
	$theme =& $template->getVariableByReference('theme');
	if ($params['showSubalbums']) {
	    if ($params['subalbumSort']) {
		$theme['tree'] = $theme['treeItems'] = array();
		$ret = $this->_buildTree($item, $theme['tree'], $theme['treeItems'],
		    $params['subalbumDepth'] > 0 ? $params['subalbumDepth'] : null,
		    $theme['actingUserId']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    } else {
		list ($ret, $tree) = GalleryCoreApi::fetchAlbumTree($item->getId(),
		    $params['subalbumDepth'] > 0 ? $params['subalbumDepth'] + 1 : null,
		    $theme['actingUserId']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$treeIds = $treeList = $treeItems = array();
		foreach ($theme['children'] as $child) {
		    $childId = $child['id'];
		    if (isset($tree[$childId])) {
			$treeList[$childId] = array();
			$this->_parseTree($tree[$childId], $treeList[$childId], $treeIds);
		    }
		}
		if (!empty($treeIds)) {
		    list ($ret, $treeItems) = GalleryCoreApi::loadEntitiesById($treeIds);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
		$theme['tree'] = $treeList;
		$theme['treeItems'] = array();
		foreach ($treeItems as $treeItem) {
		    $theme['treeItems'][$treeItem->getId()] = (array)$treeItem;
		}
	    }
	}

	/* Add our header and styles */
	return array(null, 'theme.tpl');
    }

    /**
     * @see GalleryTheme::showPhotoPage
     */
    function showPhotoPage(&$template, $item, $params) {
	$dataTypes = array('owner', 'parents', 'systemLinks', 'permissions',
			   'itemLinks', 'itemLinksDetailed', 'itemNavigator', 'imageViews');
	if (!empty($params['showMicroThumbs'])) {
	    $dataTypes[] = 'navThumbnails';
	}
	$ret = $this->loadCommonTemplateData($template, $item, $params, $dataTypes);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, 'theme.tpl');
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

    /**
     * Build template data for subalbum tree
     * @access private
     */
    function _parseTree($tree, &$treeList, &$treeIds, $depth=0) {
	foreach ($tree as $id => $list) {
	    $treeIds[] = $id;
	    $treeList[] = array('id' => $id, 'depth' => $depth);
	    if (!empty($list)) {
		$this->_parseTree($list, $treeList, $treeIds, $depth+1);
	    }
	}
    }

    /**
     * Build template data for subalbum tree (apply sort preference of each album)
     * @return object GalleryStatus a status code
     * @access private
     */
    function _buildTree($album, &$treeList, &$treeItems, $maxDepth, $userId,
			$depth=0, $branchId=0) {
	list ($ret, $subAlbumIds) =
	    GalleryCoreApi::fetchChildAlbumItemIds($album, null, null, $userId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	if (!empty($subAlbumIds)) {
	    list ($ret, $subAlbums) = GalleryCoreApi::loadEntitiesById($subAlbumIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    foreach ($subAlbums as $subAlbum) {
		$id = $subAlbum->getId();
		if (!$depth) {
		    $branchId = $id;
		} else {
		    $treeList[$branchId][] = array('id' => $id, 'depth' => $depth - 1);
		}
		$treeItems[$id] = (array)$subAlbum;
		if (!isset($maxDepth) || $depth < $maxDepth) {
		    $ret = $this->_buildTree($subAlbum, $treeList, $treeItems, $maxDepth, $userId,
					     $depth + 1, $branchId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }
	}
	return null;
    }
}
?>
