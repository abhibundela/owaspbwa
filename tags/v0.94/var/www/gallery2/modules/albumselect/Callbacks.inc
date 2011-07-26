<?php
/*
 * $RCSfile: Callbacks.inc,v $
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
 * @version $Revision: 1.15 $ $Date: 2006/02/10 01:11:25 $
 * @package AlbumSelect
 * @author Alan Harder <alan.harder@sun.com>
 */
class AlbumSelectCallbacks {

    function callback($callbackParams, &$smarty, $callback, $userId) {
	global $gallery;
	static $idCount = '';
	switch($callback) {
	case 'LoadAlbumData':
	    if (isset($callbackParams['albumTree']) && $callbackParams['albumTree']) {
		/* Set unique name for javascript object */
		$albumTreeName = 'albumTree' . $idCount++;
	    }
	    list ($ret, $params) =
		GalleryCoreApi::fetchAllPluginParameters('module', 'albumselect');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Try to load it from the cache */
	    list ($ret, $languageCode) = $gallery->getActiveLanguageCode();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $cachePathInfo = array('type' => 'module-data',
				   'module' => 'albumselect',
				   'itemId' => $userId . '_' . $languageCode .
				   '_' . substr(md5(serialize($callbackParams)), 0, 5));
	    list ($treeList, $titles, $titlesForJs) = 
	    				GalleryDataCache::getFromDisk($cachePathInfo);
	    if (!isset($titles) || !isset($treeList) || !isset($titlesForJs)) {
		if (isset($callbackParams['stripTitles'])) {
		    GalleryCoreApi::requireOnce('lib/smarty_plugins/modifier.markup.php');
		}
		if (isset($callbackParams['truncateTitles'])) {
		    GalleryCoreApi::requireOnce(
			'lib/smarty_plugins/modifier.entitytruncate.php');
		}
		list ($ret, $rootAlbumId) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $rootAlbum) = GalleryCoreApi::loadEntitiesById($rootAlbumId);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		$titles = array('root' => preg_replace('/\r\n/', ' ', $rootAlbum->getTitle()));
		if (isset($callbackParams['stripTitles'])) {
		    $titles['root'] = smarty_modifier_markup($titles['root'], 'strip');
		}
		if (isset($callbackParams['truncateTitles'])) {
		    $titles['root'] = smarty_modifier_entitytruncate(
			$titles['root'], $callbackParams['truncateTitles']);
		}
		$treeList = array();

		if ($params['sort'] != 'album') {
		    list ($ret, $tree) = GalleryCoreApi::fetchAlbumTree(null, null, $userId);
		    if ($ret) {
			if ($ret->getErrorCode() & ERROR_PERMISSION_DENIED) {
			    $tree = null;
			} else {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    if (empty($tree)) {
			return null;
		    }

		    list ($ret, $items) = GalleryCoreApi::loadEntitiesById(
			GalleryUtilities::arrayKeysRecursive($tree));
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    foreach ($items as $item) {
			$title = $item->getTitle() ? $item->getTitle() : $item->getPathComponent();
			$title = preg_replace('/\r\n/', ' ', $title);
			if (isset($callbackParams['stripTitles'])) {
			    $title = smarty_modifier_markup($title, 'strip');
			}
			if (isset($callbackParams['truncateTitles'])) {
			    $title = smarty_modifier_entitytruncate(
				$title, $callbackParams['truncateTitles']);
			}
			$titles[$item->getId()] = $title;
		    }

		    $sorter = ($params['sort'] == 'title') ?
			new AlbumSelectTreeSorter($titles) : null;
		    $nodeId = 0;
		    $this->_parseTree($tree, $treeList, $sorter, $nodeId,
				      0, 0, $callbackParams, $titles);
		} else {
		    list ($ret, $canView) =
			GalleryCoreApi::hasItemPermission($rootAlbumId, 'core.view', $userId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    if ($canView) {
			$ret = $this->_buildTree($rootAlbum, $treeList, $titles, $nodeId,
						 $userId, 0, 0, $callbackParams);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    if (empty($treeList)) {
			return null;
		    }
		}
		
		/* Need to double escape for javascript (double-quote delimiter, backslash, ..) */
		GalleryCoreApi::requireOnce('lib/smarty/plugins/modifier.escape.php');
		$titlesForJs = array();
		foreach ($titles as $id => $title) {
		    $titlesForJs[$id] = smarty_modifier_escape($title, 'javascript');
		}
		
		GalleryDataCache::putToDisk($cachePathInfo,
					    $dataToCache = 
					       array($treeList, $titles, $titlesForJs));
	    }

	    $block =& $smarty->_tpl_vars['block'];
	    $type = isset($callbackParams['albumTree']) ? 'albumTree' : 'albumSelect';
	    $block['albumselect']['LoadAlbumData'][$type] = array(
		'tree' => $treeList,
		'titles' => $titles,
		'titlesForJs' => $titlesForJs,
		'params' => $params);
	    if (isset($albumTreeName)) {
		$block['albumselect']['LoadAlbumData'][$type]['albumTreeName'] = $albumTreeName;
	    }

	    return null;
	}

	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }

    /**
     * Build template data for subalbum tree (manual and title sorting)
     * @access private
     */
    function _parseTree($tree, &$treeList, &$sorter, &$nodeId, $parentNode, $depth,
			&$callbackParams, &$titles) {
	if (isset($sorter)) {
	    uksort($tree, array($sorter, 'sort'));
	}
	foreach ($tree as $id => $list) {
	    $treeList[] = array('id' => $id, 'nodeId' => ++$nodeId,
				'parentNode' => $parentNode, 'depth' => $depth);
	    if (isset($callbackParams['createTextTree'])) {
		$titles[$id] = str_repeat('-- ', $depth) . $titles[$id];
	    }
	    if (!empty($list)) {
		$this->_parseTree($list, $treeList, $sorter, $nodeId, $nodeId, $depth + 1,
				  $callbackParams, $titles);
	    }
	}
    }

    /**
     * Build template data for subalbum tree (apply sort preference of each album)
     * @return object GalleryStatus a status code
     * @access private
     */
    function _buildTree($album, &$treeList, &$titles, &$nodeId, $userId,
			$parentNode, $depth, &$callbackParams) {
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
		$treeList[] = array('id' => $subAlbum->getId(), 'nodeId' => ++$nodeId,
				    'parentNode' => $parentNode, 'depth' => $depth);
		$title = $subAlbum->getTitle() ? $subAlbum->getTitle()
					       : $subAlbum->getPathComponent();
		$title = preg_replace('/\r\n/', ' ', $title);
		if (isset($callbackParams['stripTitles'])) {
		    $title = smarty_modifier_markup($title, 'strip');
		}
		if (isset($callbackParams['truncateTitles'])) {
		    $title = smarty_modifier_entitytruncate($title,
							    $callbackParams['truncateTitles']);
		}
		if (isset($callbackParams['createTextTree'])) {
		    $title = str_repeat('--' , $depth) . $title;
		}
		$titles[$subAlbum->getId()] = $title;
		$ret = $this->_buildTree($subAlbum, $treeList, $titles,
					 $nodeId, $userId, $nodeId, $depth + 1,
					 $callbackParams);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}
	return null;
    }
}

/**
 * Sort albums on a specific field
 */
class AlbumSelectTreeSorter {
    var $_titles;
    function AlbumSelectTreeSorter(&$titles) {
	$this->_titles =& $titles;
    }
    function sort($a, $b) {
	return strcasecmp($this->_titles[$a], $this->_titles[$b]);
    }
}
?>
