<?php
/*
 * $RCSfile: ItemAddFromServer.inc,v $
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
 * @version $Revision: 1.44 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the addition of an item as a children to
 * another item.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemAddFromServer extends ItemAddPlugin {

    /**
     * @see ItemAddPlugin::handleRequest
     */
    function handleRequest($form, &$item) {
	global $gallery;
	$this->_platform =& $gallery->getPlatform();
	$this->_dirSep = $this->_platform->getDirectorySeparator();

	$status = array();
	$error = array();

	if (isset($form['action']['addFromLocalServer'])
		&& (!empty($form['localServerFiles']) || !empty($form['localServerDirectories']))) {
	    /* Add the selected items */
	    $textFields = array(
		'title' => (isset($form['set']['title'])) ? 1 : 0,
		'summary' => (isset($form['set']['summary'])) ? 1 : 0,
		'description' => (isset($form['set']['description'])) ? 1 : 0);

	    /*
	     * See loadTemplate: the input is in UTF-8,
	     * the filesystem needs the path in the system charset
	     */
	    $dir = GalleryCoreApi::convertFromUtf8($form['localServerPath']);
	    $dir = GalleryUtilities::htmlEntityDecode($dir);

	    /* We need the localServerDirList only in the system charset */
	    list ($ret, $unused, $localServerDirList) = $this->fetchLocalServerDirList();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    if (!GalleryUtilities::isPathInList($dir, $localServerDirList)) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__),
			     null, null);
	    }
	    if ($dir{strlen($dir)-1} != $this->_dirSep) {
		$dir .= $this->_dirSep;
	    }

	    list ($ret, $lockId) = GalleryCoreApi::acquireReadLock($item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }

	    if (!empty($form['localServerFiles'])) {
		foreach ($form['localServerFiles'] as $fileKey => $data) {
		    if (!isset($data['selected'])) {
			continue;
		    }
		    $useSymlink = isset($data['useSymlink']);
		    $file = $dir . urldecode($fileKey);
		    if (!GalleryUtilities::isPathInList(dirname($file), $localServerDirList)) {
			/* Ensure malformed input like fileKey = ../foo doesn't work */
			continue;
		    }

		    $ret = $this->addItem($item->getId(), $file, $useSymlink,
					  $textFields, $status, $error);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		}
	    }

	    if (!empty($form['localServerDirectories'])) {
		$this->_localServerDirList = $localServerDirList;

		/* Make sure we have permission to add subalbums */
		$ret = GalleryCoreApi::assertHasItemPermission($item->getId(), 'core.addAlbumItem');
		if ($ret) {
		    GalleryCoreApi::releaseLocks($lockId);
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}

		foreach ($form['localServerDirectories'] as $directory => $data) {
		    /* Skip current or parent directory entries */
		    if (!isset($data['selected']) || $directory == "." || $directory == "..") {
			continue;
		    }
		    $useSymlink = isset($data['useSymlink']);

		    list ($ret, $albumId) = $this->addDirectory($item->getId(),
			$dir . urldecode($directory), $useSymlink, $textFields, $status, $error);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    if ($albumId) {
			$ret = $this->processDirectory($albumId, $dir . urldecode($directory),
			    $useSymlink, $textFields, $status, $error);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($lockId);
			    return array($ret->wrap(__FILE__, __LINE__), null, null);
			}
		    }
		}
	    }

	    $ret = GalleryCoreApi::releaseLocks($lockId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}
	
	return array(null, $error, $status);
    }

    function addItem($parentId, $file, $useSymlink, $textFields, &$status, &$error) {
	$fileName = basename($file);
	list ($base, $extension) = GalleryUtilities::getFileNameComponents($fileName);
	list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$base = GalleryCoreApi::convertToUtf8($base);
	GalleryUtilities::sanitizeInputValues($base);

	$title = ($textFields['title']) ? $base : '';
	$summary = ($textFields['summary']) ? $base : '';
	$description = ($textFields['description']) ? $base : '';

	list ($ret, $newItem) = GalleryCoreApi::addItemToAlbum($file,
							       $fileName,
							       $title,
							       $summary,
							       $description,
							       $mimeType,
							       $parentId,
							       $useSymlink);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$status['addedFiles'][] = array('fileName' => GalleryCoreApi::convertToUtf8($fileName),
					'id' => $newItem->getId(),
					'warnings' => array());

	return null;
    }

    /** @return array object GalleryStatus, mixed id of new album or false */
    function addDirectory($parentId, $path, $useSymlink, $textFields, &$status, &$error) {
	global $gallery;

	if (!GalleryUtilities::isPathInList($path, $this->_localServerDirList)
		|| GalleryUtilities::isPathInList($path,
				     array($gallery->getConfig('data.gallery.albums')))) {
	    /*
	     * Silently skip directory if a symlink jumped us outside the approved
	     * directory list or we are trying to add from our own g2data albums.
	     * Also protects against malformed form input like directory = ../foo
	     */
	    return array(null, false);
	}

	/* Create new Album */
	$dirName = basename($path);
	$dirNameUtf8 = GalleryCoreApi::convertToUtf8($dirName);
	$dirTitle = $textFields['title'] ? $dirNameUtf8 : null;
	$dirSummary = $textFields['summary'] ? $dirNameUtf8 : null;
	$dirDescription = $textFields['description'] ? $dirNameUtf8 : null;
	list ($ret, $album) = GalleryCoreApi::createAlbum($parentId, $dirName, $dirTitle,
							  $dirSummary, $dirDescription, "");
	if ($ret) {
	    if ($ret->getErrorCode() & ERROR_COLLISION) {
		$error[] = 'form[error][pathComponent][collision]';
		return array(null, false);
	    } else {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	if (!isset($album)) {
	    return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__), null);
	}

	$ret = GalleryCoreApi::addUserPermission($album->getId(), $album->getOwnerId(),
						 'core.all', false);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $album->getId());
    }

    function processDirectory($albumId, $path, $useSymlink, $textFields, &$status, &$error) {
	global $gallery;

	list ($ret, $lockId) = GalleryCoreApi::acquireReadLock($albumId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Look into the directory, add anything we find */
	$dirList = $albumList = array();
	$handle = $this->_platform->opendir($path);
	while (($fileName = $this->_platform->readdir($handle)) !== false) {
	    if ($fileName == '.' || $fileName == '..' || $fileName == 'CVS') {
		continue;
	    }
	    $dirList[] = $fileName;
	}
	$this->_platform->closedir($handle);
	sort($dirList);

	foreach ($dirList as $fileName) {
	    $gallery->guaranteeTimeLimit(30);
	    $filePath = $path . $this->_dirSep . $fileName;
	    if ($this->_platform->is_readable($filePath)) {
		if ($this->_platform->is_dir($filePath)) {
		    list ($ret, $newAlbumId) = $this->addDirectory($albumId, $filePath,
			$useSymlink, $textFields, $status, $error);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    if ($newAlbumId) {
			$albumList[$newAlbumId] = $filePath;
		    }
		} else {
		    $ret = $this->addItem($albumId, $filePath, $useSymlink,
					  $textFields, $status, $error);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($lockId);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
		if (!empty($error)) {
		    /* Bail out if an error has occurred */
		    break;
		}
	    }
	}

	$ret = GalleryCoreApi::releaseLocks($lockId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	foreach ($albumList as $newAlbumId => $filePath) {
	    $ret = $this->processDirectory($newAlbumId, $filePath,
					   $useSymlink, $textFields, $status, $error);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (!empty($error)) {
		/* Bail out if an error has occurred */
		break;
	    }
	}

	return null;
    }

    /*
     * @return array (object GalleryStatus a status code,
     *                array of UTF-8 strings localServerDirList,
     *                array of system charset strings localServerDirList)
     */
    function fetchLocalServerDirList() {
	list ($ret, $param) = GalleryCoreApi::fetchAllPluginParameters('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	$localServerDirList = array();
	$localServerDirListInSystemCharset = array();
	for ($i = 1; !empty($param['uploadLocalServer.dir.' . $i]); $i++) {
	    $localServerDirList[] = $param['uploadLocalServer.dir.' . $i];
	    $localServerDirListInSystemCharset[] =
		GalleryCoreApi::convertFromUtf8($param['uploadLocalServer.dir.' . $i]);
	}
	return array(null, $localServerDirList,
		     $localServerDirListInSystemCharset);
    }

    /**
     * @see ItemAdd:loadTemplate
     */
    function loadTemplate(&$template, &$form, $item) {
	global $gallery;

	if ($form['formName'] != 'ItemAddFromServer') {
	    /* First time around, load the form with item data */
	    $form['localServerPath'] = '';
	    $form['formName'] = 'ItemAddFromServer';
	}

	list ($ret, $localServerDirList, $localServerDirListInSystemCharset) =
	    $this->fetchLocalServerDirList();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Look up the platform type */
	$platform =& $gallery->getPlatform();
	$slash = $platform->getDirectorySeparator();
  
	if (isset($form['action']['startOver'])) {
	    $form['localServerFiles'] = array();
	} else if (isset($form['action']['findFilesFromLocalServer'])) {
	    /* If we're uploading from the local server, get a file list now */
	    $localServerPath = trim($form['localServerPath']);
	    /*
	     * All input is UTF-8, whether it comes from the browser or from the database.
	     * When we use realpath, is_dir, opendir, readdir, .., we need the path in the system
	     * charset and not in UTF-8.
	     * Therefore, we use UTF-8 for everything (output, db input) but the filesystem
	     * interactions.
	     * $form['localServerPath'] is UTF-8, $localServerPath is in the system charset
	     */
	    $localServerPath = GalleryCoreApi::convertFromUtf8($localServerPath);
	    $localServerPath = GalleryUtilities::htmlEntityDecode($localServerPath);
	    $localServerPath = $platform->realpath($localServerPath);
	    $form['localServerPath'] = GalleryCoreApi::convertToUtf8($localServerPath);
	    
	    /* Validate the path */
	    $form['localServerFiles'] = array();
	    if (empty($localServerPath)) {
		$form['error']['localServerPath']['missing'] = 1;
	    } else if (!$platform->file_exists($localServerPath) ||
		       !$platform->is_readable($localServerPath)) {
		$form['error']['localServerPath']['invalid'] = 1;
	    } else if (!GalleryUtilities::isPathInList($localServerPath,
						       $localServerDirListInSystemCharset)) {
		$form['error']['localServerPath']['illegal'] = 1;
	    } else {
		$gallery->guaranteeTimeLimit(30);
		$path = $localServerPath;
		if ($platform->is_dir($path)) {
		    $handle = $platform->opendir($path);
		    $mimeTypeItemMap = array();

		    /* Add path to the recent path list */
		    $session =& $gallery->getSession();
		    $recentPaths =
			$session->get('core.view.ItemAdd.ItemAddFromServer.recentPaths');
		    /* Use the UTF-8 string, recent paths is only for the output */
		    $recentPaths[$form['localServerPath']] = 1;
		    $session->put('core.view.ItemAdd.ItemAddFromServer.recentPaths', $recentPaths);

		    while (($fileName = $platform->readdir($handle)) !== false) {
			if ($fileName == '.') {
			    continue;
			}
			$filePath = $path . $slash . $fileName;
			
			if ($platform->is_readable($filePath)) {
			    if ($platform->is_dir($filePath)) {
				$filePath = $platform->realpath($filePath);
				$legal =
				    GalleryUtilities::isPathInList($filePath,
							      $localServerDirListInSystemCharset);
				if (!$legal && $fileName == '..') {
				    continue;
				}
				$form['localServerFiles'][] =
				    array('type' => 'directory',
					  'fileName' => GalleryCoreApi::convertToUtf8($fileName),
					  'filePath' => GalleryCoreApi::convertToUtf8($filePath),
					  'fileKey' => urlencode($fileName),
					  'legal' => $legal,
					  'unknown' => false);
			    } else {
				list ($ret, $mimeType) = GalleryCoreApi::getMimeType($fileName);
				if ($ret) {
				    return array($ret->wrap(__FILE__, __LINE__), null, null);
				}
				if (!isset($mimeTypeItemMap[$mimeType])) {
				    list ($ret, $mimeTypeItem) =
					GalleryCoreApi::newItemByMimeType($mimeType);
				    if ($ret) {
					return array($ret->wrap(__FILE__, __LINE__), null, null);
				    }
				    /* Get localized and english names: */
				    $mimeTypeItem->setMimeType($mimeType);
				    $mimeTypeItemMap[$mimeType] =
					array_merge($mimeTypeItem->itemTypeName(),
						    $mimeTypeItem->itemTypeName(false));
				}

				$form['localServerFiles'][] =
				    array('type' => 'file',
					  'fileName' => GalleryCoreApi::convertToUtf8($fileName),
					  'stat' => $platform->stat($filePath),
					  'itemType' => $mimeTypeItemMap[$mimeType][0],
					  'unknown' => $mimeTypeItemMap[$mimeType][3] == 'unknown',
					  'fileKey' => urlencode($fileName));
			    }
			}
		    }
		    $platform->closedir($handle);

		} else if ($platform->is_file($path) && !$platform->is_link($path)) {
		    list ($ret, $mimeType) = GalleryCoreApi::getMimeType($path);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    list ($ret, $mimeTypeItem) = GalleryCoreApi::newItemByMimeType($mimeType);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null);
		    }
		    $mimeTypeItem->setMimeType($mimeType);
		    $mimeTypeItemName = array_merge($mimeTypeItem->itemTypeName(),
						    $mimeTypeItem->itemTypeName(false));

		    $fileName = basename($path);
		    $form['localServerFiles'][] =
			array('type' => 'file',
			      'fileName' => GalleryCoreApi::convertToUtf8($fileName),
			      'stat' => $platform->stat($path),
			      'itemType' => $mimeTypeItemName[0],
			      'unknown' => $mimeTypeItemName[3] == 'unknown',
			      'fileKey' => urlencode($fileName));

		    /* Change localServerPath from file path to parent dir */
		    $form['localServerPath'] = GalleryCoreApi::convertToUtf8(dirname($path));
		}
	    }
	}

	$ItemAddFromServer = array('pathSeparator' => $slash,
				   'localServerDirList' => $localServerDirList,
				   'localServerFileCount' =>
				     isset($localServerFileCount) ? $localServerFileCount : null);

	if (!empty($form['localServerFiles'])) {
	    usort($form['localServerFiles'], array($this, '_sortFiles'));
	    $accumulator = '';
	    foreach ($platform->splitPath($form['localServerPath']) as $element) {
		$accumulator .= $element;
		$ItemAddFromServer['pathElements'][] = array(
		    'name' => $element, 'path' => $accumulator,
		    'legal' => GalleryUtilities::isPathInList(
			GalleryCoreApi::convertFromUtf8($accumulator),
			$localServerDirListInSystemCharset));
		if (count($ItemAddFromServer['pathElements']) > 1) {
		    $accumulator .= $slash;
		}
	    }
	}

	$session =& $gallery->getSession();
	$recentPaths = $session->get('core.view.ItemAdd.ItemAddFromServer.recentPaths');
	if (!isset($recentPaths)) {
	    $recentPaths = array();
	}
	$ItemAddFromServer['recentPaths'] = array_keys($recentPaths);

	/*
	 * Set the ItemAdmin form's encoding type specially since legal paths may contain special
	 * characters like ampersands (&)
	 */
	if ($template->hasVariable('ItemAdmin')) {
	    $ItemAdmin =& $template->getVariableByReference('ItemAdmin');
	    $ItemAdmin['enctype'] = 'multipart/form-data';
	} else {
	    $ItemAdmin['enctype'] = 'multipart/form-data';
	    $template->setVariable('ItemAdmin', $ItemAdmin);
	}

	if (!isset($form['set'])) {
	    $form['set'] = array('title' => 1, 'summary' => 0, 'description' => 0);
	}

	/* Do we want to allow symlinks? */
	$ItemAddFromServer['showSymlink'] = $platform->isSymlinkSupported();

	$template->setVariable('ItemAddFromServer', $ItemAddFromServer);

	return array(null,
		     'modules/core/templates/ItemAddFromServer.tpl',
		     'modules_core');
    }

    /**
     * @see ItemAddPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('From Local Server'));
    }

    /**
     * Sort directory listing.. directory, known types, unknowns.
     * @access private
     */
    function _sortFiles($a, $b) {
	if (($a['type'] == 'directory' || $b['type'] == 'directory') && $a['type'] != $b['type']) {
	    return ($a['type'] == 'directory') ? -1 : 1;
	}
	if (($a['unknown'] || $b['unknown']) && $a['unknown'] != $b['unknown']) {
	    return $a['unknown'] ? 1 : -1;
	}
	return strcasecmp($a['fileName'], $b['fileName']);
    }
}
?>
