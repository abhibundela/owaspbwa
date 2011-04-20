<?php
/*
 * $RCSfile: SimpleCallback.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This view lets you make very simple callbacks to the framework to
 * get very specific data.  Eventually this will probably get refactored
 * into a much more sophisticated framework.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class SimpleCallbackView extends GalleryView {

    /**
     * @see GalleryView::isImmediate()
     */
    function isImmediate() {
	return true;
    }

    /**
     * @see GalleryView::isImmediate()
     */
    function renderImmediate($status, $error) {
	global $gallery;

	list ($command, $prefix) = GalleryUtilities::getRequestVariables('command', 'prefix');
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	if (!headers_sent()) {
	    header("Content-type: text/plain; charset=UTF-8");
	}

	switch($command) {
	case 'lookupUsername':
	    if ($gallery->getActiveUserId() != $anonymousUserId) {
		list ($ret, $usernames) = GalleryCoreApi::fetchUsernames(10, null, $prefix);
		if (!$ret) {
		    print implode("\n", $usernames);
		}
	    }
	    break;

	case 'lookupGroupname':
	    if ($gallery->getActiveUserId() != $anonymousUserId) {
		list ($ret, $groupNames) = GalleryCoreApi::fetchGroupNames(10, null, $prefix);
		if (!$ret) {
		    print implode("\n", $groupNames);
		}
	    }
	    break;

	case 'lookupDirectories':
	    list ($ret, $isSiteAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	    if (!$ret && $isSiteAdmin) {
		$dirs = implode("\n",
		    $this->_getAllImmediateSubdirs(GalleryUtilities::htmlEntityDecode($prefix)));
		GalleryUtilities::sanitizeInputValues($dirs, false);
		print $dirs;
	    }
	    break;

	case 'lookupFiles':
	    list ($ret, $isSiteAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	    if (!$ret && $isSiteAdmin) {
		$files = implode("\n",
		    $this->_getAllFiles(GalleryUtilities::htmlEntityDecode($prefix)));
		GalleryUtilities::sanitizeInputValues($files, false);
		print $files;
	    }
	    break;
	}

	return null;
    }

    function _getAllImmediateSubdirs($path) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	/* $path is UTF-8, we need it in the system charset for filesystem interactions */
	$path = GalleryCoreApi::convertFromUtf8($path);
	$dirList = array();
	if (!$platform->file_exists($path) || !$platform->is_dir($path)) {
	    return $dirList;
	}

	$slash = $platform->getDirectorySeparator();
	if ($dir = $platform->opendir($path)) {
	    while (($file = $platform->readdir($dir)) !== false) {
		if ($file == '.' || $file == '..') {
		    continue;
		}

		if ($path[strlen($path)-1] == $slash) {
		    $file = $path . $file;
		} else {
		    $file = sprintf('%s%s%s', $path, $slash, $file);
		}

		if ($platform->is_dir($file)) {
		    /* Filesystem charset -> UTF-8 conversion required */
		    $dirList[] = GalleryCoreApi::convertToUtf8($file);
		}
	    }
	    $platform->closedir($dir);
	}

	sort($dirList);
	return $dirList;
    }

    function _getAllFiles($path) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	/* $path is UTF-8, we need it in the system charset for filesystem interactions */
	$path = GalleryCoreApi::convertFromUtf8($path);
	
	$dirList = $fileList = array();
	if (!$platform->file_exists($path) || !$platform->is_dir($path)) {
	    return $dirList;
	}

	$slash = $platform->getDirectorySeparator();
	if ($dir = $platform->opendir($path)) {
	    while (($file = $platform->readdir($dir)) !== false) {
		if ($file == '.' || $file == '..') {
		    continue;
		}

		if ($path[strlen($path)-1] == $slash) {
		    $file = $path . $file;
		} else {
		    $file = sprintf('%s%s%s', $path, $slash, $file);
		}

		if ($platform->is_dir($file)) {
		    $dirList[] = GalleryCoreApi::convertToUtf8($file);
		} else if ($platform->is_file($file)) {
		    $fileList[] = GalleryCoreApi::convertToUtf8($file);
		}
	    }
	    $platform->closedir($dir);
	}

	$result = $dirList;
	if (empty($result)) {
	    $result = $fileList;
	} else {
	    $result = array_merge($result, $fileList);
	}
	return $result;
    }
}
?>
