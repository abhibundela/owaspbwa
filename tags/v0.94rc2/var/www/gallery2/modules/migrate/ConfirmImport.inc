<?php
/*
 * $RCSfile: ConfirmImport.inc,v $
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
 * @version $Revision: 1.134 $ $Date: 2006/01/10 04:40:41 $
 * @package Migrate
 * @subpackage UserInterface
 * @author Jesse Mullan <jmullan@visi.com>
 */

/**
 * @package Migrate
 * @subpackage UserInterface
 */
class ConfirmImportController extends GalleryController {
    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	global $gallery;

	/* Verify that active user is an admin */
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$albumsPath = GalleryUtilities::getRequestVariables('albumsPath');

	/* build framework for error and status arrays */
	$error = array();
	$status = array();
	if (!isset($form['sourceEncoding'])) {
	    $form['sourceEncoding'] = 'UTF-8';
	}

	if (isset($form['action']['cancel'])) {
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'migrate.SelectGallery';
	} else if (isset($form['action']['import'])) {
	    $templateAdapter =& $gallery->getTemplateAdapter();
	    $templateAdapter->registerTrailerCallback(
		array($this, 'performImport'), array($form, $albumsPath));
	    $results['delegate']['view'] = 'core.ProgressBar';
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else if (empty($results['delegate'])) {
	    $results['delegate']['view'] = 'core.SiteAdmin';
	    $results['delegate']['subView'] = 'migrate.ConfirmImport';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }

    /**
     * Actually perform the import
     *
     * @param array the form variables
     * @param string the albums path
     * @return object GalleryStatus a status code
     */
    function performImport($form, $albumsPath) {
	global $gallery;
	$storage = $gallery->getStorage();
	$templateAdapter =& $gallery->getTemplateAdapter();

	$platform =& $gallery->getPlatform();
	$slash = $platform->getDirectorySeparator();

	$finishedAlbums = $albumPosition = $hiddenAlbums = array();

	$status = array('userImportFailure' => array(),
			'userImportSuccess' => array(),
			'albumImportFailure' => array(),
			'albumImportSuccess' => array(),
			'urlRedirect' => isset($form['urlRedirect']));

	list ($ret, $moduleStatusList) = GalleryCoreApi::fetchPluginStatus('module');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$importComments = !empty($moduleStatusList['comment']['active']);

	if (!empty($form['customFields'])) {
	    list ($ret, $customFieldInterface) =
		GalleryCoreApi::newFactoryInstance('CustomFieldInterface_1_0');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (!isset($customFieldInterface)) {
		return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
	    }
	    $skipCustomItemFields = array();
	    if (!empty($form['skipCustomItemFields'])) {
		foreach (array('title', 'summary', 'description') as $field) {
		    if ($form['set'][$field] == 'custom') {
			$skipCustomItemFields[] = $form['customfield'][$field];
		    }
		}
	    }
	}

	list ($ret, $hiddenInterface) = GalleryCoreApi::newFactoryInstance('HiddenInterface_1_0');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/*
	 * owner => core.all (which also gives comment.all)
	 *
	 * canRead => core.view,core.viewResizes
	 * canViewFullImages => core.viewSource
	 * canViewComments =>
	 * comment.view
	 * canAddComments => comment.add
	 * canChangeText => core.edit
	 * canAddTo => core.addDataItem
	 * canWrite => core.edit
	 * canDeleteFrom => core.delete
	 * canCreateSubAlbum => core.addAlbumItem
	 *
	 */

	$permissionTranslation = array('canRead' => array('core.view',
							  'core.viewResizes'
							  ),
				       'canViewFullImages' => array('core.viewSource'),
				       'canChangeText' => array('core.edit'),
				       'canAddTo' => array('core.addDataItem'),
				       'canWrite' => array('core.edit'),
				       'canDeleteFrom' => array('core.delete'),
				       'canCreateSubAlbum' => array('core.addAlbumItem')
				       );
	if ($importComments) {
	    $permissionTranslation['canAddComments'] = array('comment.add');
	    $permissionTranslation['canViewComments'] = array('comment.view');
	} else {
	    $permissionTranslation['canAddComments'] = array();
	    $permissionTranslation['canViewComments'] = array();
	}

	/* Get GroupIds for "everybody" and "all users" */
	list ($ret, $everybodyGroupId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.everybodyGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	list ($ret, $allUsersGroupId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.allUserGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	list ($ret, $adminUsersGroupId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.adminGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	list ($ret, $guestUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'migrate');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$importUsersMessage = $module->translate('Importing Users');
	$importPhotosMessage = $module->translate('Importing Photos');

	$sourceEncoding = $form['sourceEncoding'];

	list ($ret, $markupType) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'misc.markup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$itemsCreated = array();

	/* Get list of userids in advance */
	GalleryCoreApi::requireOnce('modules/migrate/classes/Gallery1DataParser.class');
	list ($ret, $albumList) = Gallery1DataParser::getAlbumList($albumsPath);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $uidMap) = Gallery1DataParser::getUserUids($albumsPath);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* If no users were selected, create an empty array to avoid later php warnings */
	if (!isset($form['migrateUser'])) {
	    $form['migrateUser'] = array();
	}

	/* go through the list of chosen users */
	$totalUsersToImport = count($form['migrateUser']);
	$numberOfUsersImported = 0;
	foreach (array_keys($form['migrateUser']) as $uid) {
	    $gallery->guaranteeTimeLimit(30);

	    /* convert from uid to username */
	    list ($ret, $fields) =
		Gallery1DataParser::getUserFieldsByUid($albumsPath, $uid);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $userName = GalleryCoreApi::convertToUtf8($fields['username'], $sourceEncoding);
	    if (empty($totalUsersToImport)) {
		$totalUsersToImport = 1;
	    }
	    $ret = $templateAdapter->updateProgressBar(
		$importUsersMessage, $userName, $numberOfUsersImported / $totalUsersToImport);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Go ahead and create the user instance */
	    list ($ret, $user) = GalleryCoreApi::newFactoryInstance('GalleryEntity', 'GalleryUser');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Verify that the user was created successfully */
	    if (!isset($user)) {
		return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
	    }

	    /*
	     * Turn the instance into a real boy/girl
	     * This is where the user is actually created, everything
	     * before this was simply building the framework.
	     */
	    $ret = $user->create($userName);
	    if ($ret) {
		/* ERROR_COLLISION is when you try to create something that already exists */
		if (!($ret->getErrorCode() & ERROR_COLLISION)) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		/* Other errors are apparently okay.  Note them and continue */
		$status['userImportFailure'][$userName] = 1;
	    } else {
		/*
		 * Apparently the creation was successful.  Now we can start
		 * setting the various metadata associated with the user
		 */
		$user->setEmail($fields['email']);
		$user->setHashedPassword($fields['password']);
		$fullName = GalleryCoreApi::convertToUtf8(
		    trim($fields['fullname']), $sourceEncoding);
		$fullName = (!empty($fullName)) ? $fullName : $userName;
		$user->setFullName($fullName);

		/*
		 * $fields['canCreateAlbums'] if they have that perm, give them
		 * "core.addAlbumItem" permission on the root album
		 */
		/* Save this user to the database */
		$ret = $user->save();
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		if (isset($fields['isAdmin']) && $fields['isAdmin']) {
		    /* Add her to the admin group */
		    $ret = GalleryCoreApi::addUserToGroup($user->getId(), $adminUsersGroupId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		/* Set a positive note for later.  Hooray! */
		$status['userImportSuccess'][$userName] = 1;
		$user = null;
	    }
	    $uid = null;
	    $numberOfUsersImported++;
	}

	$ret = $templateAdapter->updateProgressBar($importUsersMessage, '', 1);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Checkpoint to ensure users are saved to the DB in case of a later failure */
	$ret = $storage->checkPoint();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$templateAdapter->resetProgressBarStats();

	/*
	 * Import Any Selected Albums
	 * First initialize the $form['sourceAlbums'] array to avoid php warnings
	 */
	if (!isset($form['sourceAlbums'])) {
	    $form['sourceAlbums'] = array();
	}
	foreach ($form['sourceAlbums'] as $key => $value) {
	    /* See comment for same loop below in ConfirmImportView */
	    unset($form['sourceAlbums'][$key]);
	    $form['sourceAlbums'][urldecode($key)] = $value;
	}

	/*
	 * Check to see if the user selected a destination.  If not,
	 * default to the root album of the new gallery install.
	 */
	if (!isset($form['destinationAlbumID'])) {
	    list ($ret, $rootId) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $form['destinationAlbumID'] = $rootId;
	}
	/* Convert the form variable into a local temporary variable */
	$destinationAlbumID = $form['destinationAlbumID'];
	/* Go through the list of selected gallery1 albums to import */

	$totalItemsToImport = 0;
	$albumfields = $albumHighlight = array();
	foreach (array_keys ($form['sourceAlbums']) as $sourceAlbumName) {
	    /*
	     * attempt to load the metadata from the selected album into a
	     * local cache for later manipulation
	     */
	    if (!in_array($sourceAlbumName, array_keys($albumList))) {
		return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
	    }
	    list ($ret, $albumfields[$sourceAlbumName]) =
		Gallery1DataParser::loadAlbumFields($albumsPath . $sourceAlbumName);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $photos) = Gallery1DataParser::getPhotos($albumsPath . $sourceAlbumName);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $totalItemsToImport += 1 + count($photos);
	}

	/*
	 * Go through the array of albums' metadata one album at a time.
	 * We limit the number of passes through this list to n^2 times to avoid an infinite loop.
	 * This allows us to make sure that we add parent albums first by taking albums out of the
	 * list as they are handled.
	 */
	$i = $numberOfItemsImported = 0;
	while ($albumfields && $i < pow(count($form['sourceAlbums']), 2) && !connection_aborted()) {
	    $gallery->guaranteeTimeLimit(30);
	    $album = array_shift($albumfields);

	    /*
	     * if the album's parent is not in the list of sourcealbums,
	     * load it into the targeted G2 top album as set in $destinationAlbumID
	     */
	    $targetAlbumID = $newAlbumInstanceId = $isHiddenAlbum = false;
	    if (!in_array($album['parentAlbumName'], array_keys($form['sourceAlbums']))) {
		$targetAlbumID = $destinationAlbumID;
		if ($album['parentAlbumName'] != '.root') {
		    /* load parent album to check if this album is hidden */
		    list ($ret, $parentPhotos) =
			Gallery1DataParser::getPhotos($albumsPath . $album['parentAlbumName']);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    foreach ($parentPhotos as $parentAlbumItem) {
			if (isset($parentAlbumItem->isAlbumName) &&
				$parentAlbumItem->isAlbumName == $album['name']) {
			    $isHiddenAlbum =
				isset($parentAlbumItem->hidden) && $parentAlbumItem->hidden;
			    break;
			}
		    }
		}
	    } else if (in_array($album['parentAlbumName'], array_keys($finishedAlbums))){
		/* if the album's parent has already been imported, import it into the parent */
		$targetAlbumID = $finishedAlbums[$album['parentAlbumName']];
		$isHiddenAlbum = isset($hiddenAlbums[$album['name']]);
	    } else {
		$albumfields[$album['name']] = $album;
		$album = null;
	    }
	    if (!$targetAlbumID || !$album) {
		continue;
	    }

	    /* We definitely need notices before each album */
	    $ret = $templateAdapter->updateProgressBar($importPhotosMessage,
		    $album['name'], $numberOfItemsImported / $totalItemsToImport);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $parent) = GalleryCoreApi::loadEntitiesById($targetAlbumID);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $parentPath) = $parent->fetchPath();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $desiredname = $platform->legalizePathComponent($album['name']);
	    $k = 0;
	    $invalidName = true;
	    while ($invalidName) {
		list ($ret, $existingAlbumId) =
		    GalleryCoreApi::fetchChildIdByPathComponent($targetAlbumID, $desiredname);
		if ($ret) {
		    if (!$ret->getErrorCode() & ERROR_MISSING_OBJECT) {
			return $ret->wrap(__FILE__, __LINE__);
		    } else {
			$invalidName = false;
		    }
		} else {
		    $desiredname = $album['name'] . '_' . $k++;
		}
	    }

	    /* Make sure we have permission to edit the target item */
	    $ret = GalleryCoreApi::assertHasItemPermission($targetAlbumID, 'core.addAlbumItem');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Try to load targeted parent */
	    list ($ret, $targetAlbumObject) = GalleryCoreApi::loadEntitiesById($targetAlbumID);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Get a lock on said parent */
	    list ($ret, $importLockIds[]) =
		GalleryCoreApi::acquireReadLock($targetAlbumObject->getId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* If everything is good so far, we create a new instance to be our new album */
	    if (!empty($desiredname) && $platform->isLegalPathComponent($desiredname)) {
		list ($ret, $newAlbumInstance) =
		    GalleryCoreApi::newFactoryInstance('GalleryEntity', 'GalleryAlbumItem');
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
		if (!isset($newAlbumInstance)) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
		}
		/* this is where the album is actually created */
		$ret = $newAlbumInstance->create($targetAlbumID, $desiredname);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (isset($form['urlRedirect'])) {
		    $ret = $this->createG1ToG2Mapping($newAlbumInstance->getId(), $album['name']);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		$newAlbumInstanceId = $newAlbumInstance->getId();
		$sourceData = array('title' => null, 'summary' => null);
		/* load up the album with metadata from the old album */
		if (isset($album['title'])) {
		    $sourceData['title'] =
			$this->convertHtml($markupType, $album['title'], $sourceEncoding);
		    $newAlbumInstance->setTitle($sourceData['title']);
		}
		if (isset($album['summary'])) {
		    $newAlbumInstance->setDescription($this->convertHtml(
				$markupType, $album['summary'], $sourceEncoding));
		}
		$newAlbumInstance->setKeywords('');
		if (isset($album['description'])) {
		    $sourceData['summary'] = $this->convertHtml(
			    $markupType, $album['description'], $sourceEncoding);
		    $newAlbumInstance->setSummary($sourceData['summary']);
		}
		// $newAlbumInstance->settheme
		if (isset($album['last_mod_time']) && $album['last_mod_time']) {
		    if (isset($album['clicks_date']) && $album['clicks_date'] > 0 &&
			    $album['clicks_date'] < $album['last_mod_time']){
			$newAlbumInstance->setCreationTimestamp($album['clicks_date']);
			$newAlbumInstance->setOriginationTimestamp($album['clicks_date']);
		    } else {
			$newAlbumInstance->setCreationTimestamp($album['last_mod_time']);
			$newAlbumInstance->setOriginationTimestamp($album['last_mod_time']);
		    }
		}
		if (isset($album['clicks_date']) && $album['clicks_date'] > 0) {
		    $newAlbumInstance->setviewedSinceTimestamp($album['clicks_date']);
		}

		/* $newAlbumInstance->setorderBy */
		/* $newAlbumInstance->setorderDirection */

		/* Set album owner */
		if (isset($album['owner'])) {
		    $validUID = Gallery1DataParser::isValidUid($albumsPath, $album['owner']);
		    if ($validUID) {
			list ($ret, $albumUserFields) =
			    Gallery1DataParser::getUserFieldsByUid($albumsPath, $album['owner']);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			list ($ret, $owner) = GalleryCoreApi::fetchUserByUsername(
				GalleryCoreApi::convertToUtf8(
					$albumUserFields['username'], $sourceEncoding));
			if ($ret) {
			    if ($ret->getErrorCode() & ERROR_MISSING_OBJECT) {
				$owner = $gallery->getActiveUser();
			    } else {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			}
			$newAlbumInstance->setOwnerId($owner->getId());
		    }
		}
		/* Save the new album */
		$ret = $newAlbumInstance->save();
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
		$numberOfItemsImported++;

		$ret = $this->checkTruncation($newAlbumInstance, $sourceData);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/* Create custom fields */
		if (isset($customFieldInterface) && !empty($album['extra_fields'])) {
		    /* Skip these metadata type fields: */
		    $extraFields = array_diff($album['extra_fields'],
			    array('Upload Date', 'Capture Date', 'Dimensions', 'EXIF'),
			    $skipCustomItemFields);
		    if (!empty($extraFields)) {
			foreach ($extraFields as $i => $field) {
			    $extraFields[$i] = $this->convertHtml('html', $field, $sourceEncoding);
			}
			list ($ret) = $customFieldInterface->createCustomFields(
				$extraFields, $newAlbumInstance->getId());
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}

		$itemsCreated[] = $newAlbumInstance->getId();

		/* Set order weight */
		if (isset($albumPosition[$album['name']])) {
		    $weight = $albumPosition[$album['name']];
		} else {
		    list ($ret, $weight) = GalleryCoreApi::fetchExtremeChildWeight(
			    $targetAlbumID, HIGHER_WEIGHT);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $weight += 1000;
		}
		$ret = GalleryCoreApi::setItemOrderWeight($newAlbumInstance->getId(), $weight);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/* album permissions */
		$ret = GalleryCoreApi::removeItemPermissions($newAlbumInstance->getId());
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
		if ($adminUsersGroupId) {
		    $ret = GalleryCoreApi::addGroupPermission($newAlbumInstance->getId(),
			    $adminUsersGroupId, 'core.all', false);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		/* Set album owner permissions */
		$validUID = Gallery1DataParser::isValidUid($albumsPath, $album['owner']);
		if ($validUID) {
		    list ($ret, $albumUserFields) = Gallery1DataParser::getUserFieldsByUid(
			    $albumsPath, $album['owner']);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    list ($ret, $owner) = GalleryCoreApi::fetchUserByUsername(
			    GalleryCoreApi::convertToUtf8(
				$albumUserFields['username'], $sourceEncoding));
		    if ($ret) {
			if ($ret->getErrorCode() & ERROR_MISSING_OBJECT) {
			    $owner = $gallery->getActiveUser();
			} else {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    $ret = GalleryCoreApi::addUserPermission($newAlbumInstance->getId(),
			    $owner->getId(), 'core.all', true);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		if (isset($album['perms'])) {
		    foreach ($album['perms'] as $tmpperm => $tmpusers) {
			foreach ($permissionTranslation[$tmpperm] as $tmpTranslatedPerm) {
			    if ($isHiddenAlbum && $tmpTranslatedPerm == 'core.view') {
				/* Omit view permission for hidden albums */
				continue;
			    }
			    foreach (array_keys($tmpusers) as $tmpuser) {
				switch ($tmpuser) {
				case 'Logged In':
				    if ($allUsersGroupId) {
					$ret = GalleryCoreApi::addGroupPermission(
						$newAlbumInstance->getId(),
						$allUsersGroupId,
						$tmpTranslatedPerm,
						false);
					if ($ret) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
				    }
				    break;
				case 'everybody':
				    if ($everybodyGroupId) {
					$ret = GalleryCoreApi::addGroupPermission(
						$newAlbumInstance->getId(),
						$everybodyGroupId,
						$tmpTranslatedPerm,
						false);
					if ($ret) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
				    }
				    break;
				case 'Nobody':
				case 'admin':
				    break;
				default:
				    $validPermUID = Gallery1DataParser::isValidUid(
					    $albumsPath, $tmpuser);
				    if ($validPermUID) {
					list ($ret, $permsUserFields) =
					    Gallery1DataParser::getUserFieldsByUid(
						    $albumsPath, $tmpuser);
					if ($ret) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
					$permUsername = $permsUserFields['username'];
					list ($ret, $permUser) =
					    GalleryCoreApi::fetchUserByUsername(
						    GalleryCoreApi::convertToUtf8(
							$permUsername, $sourceEncoding));
					if ($ret &&
						!($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
					if ($permUser) {
					    $ret = GalleryCoreApi::addUserPermission(
						    $newAlbumInstance->getId(),
						    $permUser->getId(),
						    $tmpTranslatedPerm,
						    false);
					    if ($ret) {
						GalleryCoreApi::releaseLocks($importLockIds);
						return $ret->wrap(__FILE__, __LINE__);
					    }
					}
				    }
				}
			    }
			}
		    }
		}
		if ($isHiddenAlbum && isset($hiddenInterface)) {
		    $ret = $hiddenInterface->hideItem($newAlbumInstance);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		/* Click counts must be set after the item is first saved */
		if (isset($album['clicks'])) {
		    $ret = GalleryCoreApi::setItemViewCount($newAlbumInstanceId, $album['clicks']);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		/* Delete existing derivative preferences */
		$ret = GalleryCoreApi::removeDerivativePreferencesForItem($newAlbumInstanceId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/* Add the thumbnail size back in */
		if (isset($album['thumb_size'])) {
		    $ret = GalleryCoreApi::addDerivativePreference(
			    0, $newAlbumInstanceId, DERIVATIVE_TYPE_IMAGE_THUMBNAIL,
			    'thumbnail|' . $album['thumb_size']);
		}
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (isset($album['resize_size']) && $album['resize_size'] > 0) {
		    $ret = GalleryCoreApi::addDerivativePreference(
			    1, $newAlbumInstanceId, DERIVATIVE_TYPE_IMAGE_RESIZE,
			    'scale|' . $album['resize_size']);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		list ($ret, $themeId) = GalleryCoreApi::fetchThemeId($newAlbumInstance);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $settings) = $theme->getSettings($newAlbumInstanceId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		$ret = $theme->setParameter('rows', $album['rows'], $newAlbumInstanceId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		$ret = $theme->setParameter('columns', $album['cols'], $newAlbumInstanceId);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (isset($newAlbumInstanceId)) {
		    $finishedAlbums[$album['name']] = $newAlbumInstanceId;
		    $status['albumImportSuccess'][$album['name']] = $newAlbumInstanceId;
		} else {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	    /*
	     * we can let the parent album (and anything else that we might
	     * have tied up) be edited by others now
	     */
	    $ret = GalleryCoreApi::releaseLocks($importLockIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    } else {
		$importLockIds = array();
	    }

	    unset($photos);
	    list ($ret, $photos) = Gallery1DataParser::getPhotos($albumsPath . $album['name']);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (!isset($photos)) {
		$photos = array();
	    }

	    $albumHighlight[$newAlbumInstanceId] = false;
	    list ($ret, $item) = GalleryCoreApi::loadEntitiesById($newAlbumInstanceId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $importLockIds[]) = GalleryCoreApi::acquireReadLock($item->getId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $thumbnailTable) =
		GalleryCoreApi::fetchThumbnailsByItemIds(array($item->getId()));
	    if ($ret) {
		GalleryCoreApi::releaseLocks($importLockIds);
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $albumNeedsThumbnail = empty($thumbnailTable);

	    foreach ($photos as $j => $importAlbumItem) {
		$gallery->guaranteeTimeLimit(30);
		if (isset($importAlbumItem->isAlbumName) && $importAlbumItem->isAlbumName) {
		    if ($importAlbumItem->highlight == 1) {
			$albumHighlight[$newAlbumInstanceId] = $importAlbumItem->isAlbumName;
		    }
		    list ($ret, $weight) = GalleryCoreApi::fetchExtremeChildWeight(
			    $newAlbumInstanceId, HIGHER_WEIGHT);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $albumPosition[$importAlbumItem->isAlbumName] = $weight + 100 + $j;
		    if (isset($importAlbumItem->hidden) && $importAlbumItem->hidden) {
			$hiddenAlbums[$importAlbumItem->isAlbumName] = true;
		    }
		    $importAlbumItem = null;
		} else {
		    $dir = $albumsPath . $album['name'] . $slash;
		    $filename = $importAlbumItem->image->name . '.' . $importAlbumItem->image->type;
		    $filepath = $dir . $filename;

		    $ret = $templateAdapter->updateProgressBar(
			    $importPhotosMessage, $album['name'] . ' ' . $filename,
			    $numberOfItemsImported / $totalItemsToImport);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    list ($base, $extension) = GalleryUtilities::getFileNameComponents($filename);
		    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime($extension);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $convertedCaption = $this->convertHtml(
			    $markupType, $importAlbumItem->caption, $sourceEncoding);

		    $data = array('title' => '', 'summary' => '', 'description' => '');
		    foreach (array_keys($data) as $field) {
			switch ($form['set'][$field]) {
			case 'filename':
			    $data[$field] = $base;
			    break;
			case 'caption':
			    $data[$field] = $convertedCaption;
			    break;
			case 'custom':
			    $customField = $form['customfield'][$field];
			    if (isset($importAlbumItem->extraFields[$customField])) {
				$data[$field] = $this->convertHtml(
					$markupType, $importAlbumItem->extraFields[$customField],
					$sourceEncoding);
			    } else if ($field == 'description' &&
				    isset($form['set']['defaultDescription'])) {
				$data[$field] = $data['summary'];
			    }
			}
		    }

		    list ($ret, $newItem) = GalleryCoreApi::addItemToAlbum(
			    $filepath, $filename, $data['title'], $data['summary'],
			    $data['description'], $mimeType, $item->getId());
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $itemsCreated[] = $newItem->getId();
		    $numberOfItemsImported++;

		    $ret = $this->checkTruncation($newItem, $data);
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    if (isset($form['urlRedirect'])) {
			$ret = $this->createG1ToG2Mapping(
				$newItem->getId(), $album['name'], $importAlbumItem->image->name);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    $itemLockIds = array();
		    list ($ret, $itemLockIds[]) =
			GalleryCoreApi::acquireWriteLock($newItem->getId());
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $newItem->setKeywords(GalleryCoreApi::convertToUtf8(
				$importAlbumItem->keywords, $sourceEncoding));

		    if (isset($importAlbumItem->uploadDate)){
			$newItem->setCreationTimestamp($importAlbumItem->uploadDate);
		    }
		    if (isset($importAlbumItem->itemCaptureDate)){
			/* Before 1.4.5-cvs-b106 this was an associative array */
			if (is_array($importAlbumItem->itemCaptureDate)) {
			    $newItem->setOriginationTimestamp(
				    mktime($importAlbumItem->itemCaptureDate['hours'],
					$importAlbumItem->itemCaptureDate['minutes'],
					$importAlbumItem->itemCaptureDate['seconds'],
					$importAlbumItem->itemCaptureDate['mon'],
					$importAlbumItem->itemCaptureDate['mday'],
					$importAlbumItem->itemCaptureDate['year']));
			} else {
			    $newItem->setOriginationTimestamp($importAlbumItem->itemCaptureDate);
			}
		    } else if (isset($importAlbumItem->uploadDate)){
			$newItem->setOriginationTimestamp($importAlbumItem->uploadDate);
		    }
		    if (isset($album['clicks_date']) && $album['clicks_date']>0) {
			$newItem->setviewedSinceTimestamp($album['clicks_date']);
		    }
		    if (isset($importAlbumItem->clicks) && $importAlbumItem->clicks > 0) {
			$ret = GalleryCoreApi::setItemViewCount(
				$newItem->getId(), $importAlbumItem->clicks);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    if (isset($importAlbumItem->image->thumb_x) &&
			    isset($importAlbumItem->image->thumb_y) &&
			    isset($importAlbumItem->image->thumb_width) &&
			    isset($importAlbumItem->image->thumb_height) &&
			    $importAlbumItem->image->thumb_width > 0 &&
			    $importAlbumItem->image->thumb_height > 0){

			/* Load the thumbnail */
			$thumbnails = array();
			list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds(
				array($newItem->getId()));
			if ($ret) {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}

			if (!empty($thumbnails)) {
			    $thumbnail = $thumbnails[$newItem->getId()];

			    list ($ret, $lock) =
				GalleryCoreApi::acquireWriteLock($thumbnail->getId());
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }

			    list ($ret, $thumbnail) = $thumbnail->refresh();
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				GalleryCoreApi::releaseLocks($lock);
				return $ret->wrap(__FILE__, __LINE__);
			    }

			    $width = $importAlbumItem->image->raw_width;
			    $height = $importAlbumItem->image->raw_height;
			    $xPerc = GalleryUtilities::roundToString(
				    $importAlbumItem->image->thumb_x * 100 / $width, 3);
			    $yPerc = GalleryUtilities::roundToString(
				    $importAlbumItem->image->thumb_y * 100 / $height, 3);
			    $widthPerc = GalleryUtilities::roundToString(
				    $importAlbumItem->image->thumb_width * 100 / $width, 3);
			    $heightPerc = GalleryUtilities::roundToString(
				    $importAlbumItem->image->thumb_height * 100 / $height, 3);
			    list ($ret, $operations) =
				GalleryCoreApi::mergeDerivativeOperations(
					$thumbnail->getDerivativeOperations(),
					sprintf('crop|%s,%s,%s,%s',
					    $xPerc, $yPerc,
					    $widthPerc, $heightPerc),
					true);
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				GalleryCoreApi::releaseLocks($lock);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $thumbnail->setDerivativeOperations($operations);

			    $thumbnail->setWidth(0);
			    $thumbnail->setHeight(0);

			    $ret = $thumbnail->save();
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $ret = GalleryCoreApi::releaseLocks($lock);
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }

			    /*
			     * Changing the crop size causes our derivative dimensions to change,
			     * which affects all our dependent derivatives also.
			     */
			    $ret = GalleryCoreApi::invalidateDerivativeDimensionsBySourceIds(
				    array($thumbnail->getId()));
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			}
		    }

		    $validItemUID = $itemUID = false;
		    $defaultToAlbumUID = true;
		    if (isset($importAlbumItem->owner)) {
			if (!strcmp('everybody', $importAlbumItem->owner)
				|| !strcmp('nobody', $importAlbumItem->owner)) {
			    $defaultToAlbumUID = false;
			    $newItem->setownerId($guestUserId);
			} else if (strcmp('logged-in', $importAlbumItem->owner)) {
			    $validItemUID = Gallery1DataParser::isValidUid(
				    $albumsPath, $importAlbumItem->owner);
			    if ($validItemUID) {
				$defaultToAlbumUID = false;
				$itemUID = $importAlbumItem->owner;
			    }
			}
		    }
		    if ($defaultToAlbumUID) {
			$validItemUID =
			    Gallery1DataParser::isValidUid($albumsPath, $album['owner']);
			if ($validItemUID) {
			    $itemUID = $album['owner'];
			}
		    }
		    if ($validItemUID && $itemUID) {
			list ($ret, $albumItemUserFields) =
			    Gallery1DataParser::getUserFieldsByUid($albumsPath, $itemUID);
			if ($ret) {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}
			list ($ret, $itemOwner) = GalleryCoreApi::fetchUserByUsername(
				GalleryCoreApi::convertToUtf8(
				    $albumItemUserFields['username'], $sourceEncoding));
			if ($ret && !($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
			    GalleryCoreApi::releaseLocks($importLockIds);
			    return $ret->wrap(__FILE__, __LINE__);
			}
			if ($itemOwner) {
			    $newItem->setOwnerId($itemOwner->getId());
			}
		    }

		    $ret = $newItem->save();
		    if ($ret) {
			GalleryCoreApi::releaseLocks($importLockIds);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    /* Set custom field values */
		    if (isset($customFieldInterface) && !empty($importAlbumItem->extraFields)) {
			$extraFields = array();
			foreach ($importAlbumItem->extraFields as $key => $value) {
			    if (in_array($key, $skipCustomItemFields)) {
				continue;
			    }
			    $key = $this->convertHtml('html', $key, $sourceEncoding);
			    $extraFields[$key] = $this->convertHtml($markupType, $value,
				    $sourceEncoding);
			}
			if (!empty($extraFields)) {
			    $ret = $customFieldInterface->setCustomFieldValues(
				    $newItem->getId(), $extraFields);
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			}
		    }

		    if ($importComments && isset($importAlbumItem->comments) &&
			    count($importAlbumItem->comments) > 0) {
			foreach ($importAlbumItem->comments as $importCommentId => $importComment) {
			    if (strcmp($importComment->UID, 'everybody')) {
				$validCommentUid = Gallery1DataParser::isValidUid(
					$albumsPath, $album['owner']);
				if ($validCommentUid){
				    list ($ret, $commentG1User) =
					Gallery1DataParser::getUserFieldsByUid(
						$albumsPath, $album['owner']);
				    if ($ret) {
					GalleryCoreApi::releaseLocks($importLockIds);
					return $ret->wrap(__FILE__, __LINE__);
				    }
				    list ($ret, $commentG2User) =
					GalleryCoreApi::fetchUserByUsername(
						GalleryCoreApi::convertToUtf8(
						    $commentG1User['username'], $sourceEncoding));
				    if ($ret) {
					if (!($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					} else {
					    $commenterId = $guestUserId;
					}
				    } else {
					$commenterId = $commentG2User->getId();
				    }
				}
			    } else {
				$commenterId = $guestUserId;
			    }

			    /* Add the comment */
			    list ($ret, $comment) = GalleryCoreApi::newFactoryInstance(
				    'GalleryEntity', 'GalleryComment');
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }

			    if (!isset($comment)) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return GalleryCoreApi::error(
					ERROR_MISSING_OBJECT, __FILE__, __LINE__);
			    }

			    $ret = $comment->create($newItem->getId());
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }

			    $comment->setCommenterId($commenterId);
			    $comment->setHost(empty($importComment->IPNumber) ?
				    'unknown' : $importComment->IPNumber);
			    if ($commenterId == $guestUserId) {
				$comment->setSubject(
					GalleryCoreApi::convertToUtf8($importComment->name,
					    $sourceEncoding));
			    } else {
				$comment->setSubject('');
			    }
			    $comment->setComment(
				    ConfirmImportController::convertHtml(
					$markupType, $importComment->commentText,
					$sourceEncoding));
			    $comment->setDate($importComment->datePosted);

			    $ret = $comment->save();
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			}
		    }
		    $ret = GalleryCoreApi::releaseLocks($itemLockIds);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    } else {
			$itemLockIds = array();
		    }

		    if (isset($importAlbumItem->hidden) && $importAlbumItem->hidden) {
			if (isset($hiddenInterface)) {
			    $ret = $hiddenInterface->hideItem($newItem);
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			} else {
			    /* remove view permission for hidden item */
			    list ($ret, $itemPermissions) =
				GalleryCoreApi::fetchAllPermissionsForItem($newItem->getId());
			    if ($ret) {
				GalleryCoreApi::releaseLocks($importLockIds);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    foreach ($itemPermissions as $itemPermission) {
				if ($itemPermission['permission'] == 'core.view') {
				    if (isset($itemPermission['userId'])
					    && $itemPermission['userId']
					    != $newItem->getOwnerId()) {
					$ret = GalleryCoreApi::removeUserPermission(
						$newItem->getId(), $itemPermission['userId'],
						'core.view', false);
					if ($ret) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
				    } else if (isset($itemPermission['groupId'])
					    && $itemPermission['groupId'] != $adminUsersGroupId) {
					$ret = GalleryCoreApi::removeGroupPermission(
						$newItem->getId(), $itemPermission['groupId'],
						'core.view', false);
					if ($ret) {
					    GalleryCoreApi::releaseLocks($importLockIds);
					    return $ret->wrap(__FILE__, __LINE__);
					}
				    }
				}
			    }
			}
		    }

		    $status['addFromLocalServer'][] = array('fileName' => $filename,
			    'id' => $newItem->getId());
		    if ($albumNeedsThumbnail || $importAlbumItem->highlight == 1) {
			$toolkit = null;

			list ($ret, $toolkit, $outputMimeType) =
			    GalleryCoreApi::getToolkitByOperation(
				    $newItem->getMimeType(), 'thumbnail');
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			if (isset($toolkit)) {
			    list ($ret, $success) = GalleryCoreApi::setThumbnailFromItem(
				    $item->getId(), $newItem->getId());
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $albumNeedsThumbnail = false;
			}
		    }
		}
	    }
	    $ret = $templateAdapter->updateProgressBar($importPhotosMessage,
		    $album['name'], $numberOfItemsImported / $totalItemsToImport);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $album = null;
	    /* $i keeps track of how many passes we make over the data */
	    $i++;
	}

	/* We just did a lot of permission wrangling; optimize our access lists */
	$ret = GalleryCoreApi::compactAccessLists();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Checkpoint to ensure items are saved to the DB in case of a later failure */
	$ret = $storage->checkPoint();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	//$albumHighlight[$newAlbumInstanceId]=$importAlbumItem->isAlbumName;
	//$finishedAlbums[$album['name']] = $newAlbumInstanceId;
	foreach ($albumHighlight as $highlightRecipientId => $nameOfAlbumToHighlight) {
	    if (isset($finishedAlbums[$nameOfAlbumToHighlight]) &&
		$finishedAlbums[$nameOfAlbumToHighlight]) {
		list ($ret, $item) = GalleryCoreApi::loadEntitiesById($highlightRecipientId);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $importLockIds[]) = GalleryCoreApi::acquireReadLock($item->getId());
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		list ($ret, $success) = GalleryCoreApi::setThumbnailFromItem(
		    $item->getId(), $finishedAlbums[$nameOfAlbumToHighlight]);
		if ($ret) {
		    GalleryCoreApi::releaseLocks($importLockIds);
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	if (isset($importLockIds)) {
	    $ret = GalleryCoreApi::releaseLocks($importLockIds);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    } else {
		$importLockIds = array();
	    }
	}

	/* Checkpoint to ensure highlights are saved to the DB in case of a later failure */
	$ret = $storage->checkPoint();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$templateAdapter->resetProgressBarStats();

	if (!empty($form['generateThumbnails']) && !connection_aborted()) {
	    /*
	     * Get the thumbnails for all of the items we created, in 20 item
	     * increments so that we don't consume too much memory.
	     *
	     * Note: this destroys the $itemsCreated array!
	     */
	    $createThumbsMessage = $module->translate('Creating thumbnails');
	    $itemsCreatedCount = count($itemsCreated);
	    $thumbsCreated = 0;
	    while (!empty($itemsCreated)) {
		$chunk = array_splice($itemsCreated, 0, 20);
		list ($ret, $thumbTable) = GalleryCoreApi::fetchThumbnailsByItemIds($chunk);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		foreach ($thumbTable as $thumbnail) {
		    list ($ret, $newThumbnail, $wasRebuilt) =
			GalleryCoreApi::rebuildDerivativeCacheIfNotCurrent($thumbnail->getId());
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $thumbsCreated++;
		    $ret = $templateAdapter->updateProgressBar(
			$createThumbsMessage,
			$module->translate(array('text' => 'Thumbnail %d of %d',
						 'arg1' => $thumbsCreated,
						 'arg2' => $itemsCreatedCount)),
			$thumbsCreated / $itemsCreatedCount);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
		GalleryDataCache::reset();
	    }
	}

	/* Checkpoint to ensure thumbnails are saved to the DB in case of a later failure */
	$ret = $storage->checkPoint();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$session =& $gallery->getSession();

	$redirect['view'] = 'core.SiteAdmin';
	$redirect['subView'] = 'migrate.ImportResults';
	$redirect['statusId'] = $session->putStatus($status);

	$urlGenerator =& $gallery->getUrlGenerator();
	$templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));

	return null;
    }

    /**
     * Convert html according to given markup type
     * @param string markup type (none, bbcode, html)
     * @param string input string
     * @return string
     */
    function convertHtml($markupType, $item, $sourceEncoding='UTF-8') {
	$item = GalleryCoreApi::convertToUtf8($item, $sourceEncoding);
	switch ($markupType) {
	case 'bbcode':
	    $item = ConfirmImportController::convertHtmlToBbcode($item);
	    break;

	case 'html':
	    break;

	case 'none':
	default:
	    $item = strip_tags($item);
	}
	GalleryUtilities::sanitizeInputValues($item, false);
	return $item;
    }

    /**
     * Converts HTML to BBCode
     *
     * @param string $item item to convert
     * @return string
     */
    function convertHtmlToBbcode($item) {
	static $patterns, $match, $replace;
	if (!isset($patterns)) {
	    $patterns = array(
	      '#<br\s*(/?)>#i' => "\n",
	      '#<(/?)(b|strong)>#i' => '[$1b]',
	      '#<(/?)(i|em)>#i' => '[$1i]',
	      '#<(/?)ul>#i' => '[$1list]',
	      '#<li>#i' => '[*]',
	      '#</li>#i' => '',
	      '#<img[^>]*\s+src\s*=\s*["\']{0,1}([^\s"\'>]+)["\']{0,1}[^>]*>#is' => '[img]$1[/img]',
	      '#<a[^>]*\s+href\s*=\s*["\']{0,1}([^\s"\'>]+)["\']{0,1}[^>]*>\s*(.*?)\s*</a>#is' =>
										'[url=$1]$2[/url]',
	    );
	    $match = array_keys($patterns);
	    $replace = array_values($patterns);
	}

	$item = preg_replace($match, $replace, $item);
	return strip_tags($item);
    }

    /**
     * Create the G1->G2 map entries so that Gallery1 links
     * can be redirected to Gallery2 links.
     * @param int item id
     * @param string G1 album name
     * @param string (optional) G1 item name
     * @return object GalleryStatus a status code
     * @author Joseph Elwell <jelwell@yahoo.com>
     */
    function createG1ToG2Mapping($itemId, $g1AlbumName, $g1ItemName=null) {
	$ret = GalleryCoreApi::addMapEntry(
	    'G1MigrateMap',
	    array('itemId' => $itemId, 'g1album' => $g1AlbumName, 'g1item' => $g1ItemName) );
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	return null;
    }

    /**
     * Print message if any fields were truncated on imported item.
     */
    function checkTruncation(&$item, $sourceData) {
	global $gallery;
	static $titleMessage, $summaryMessage, $setMessage, $truncatedMessage;
	if (!isset($titleMessage)) {
	    list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'migrate');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $titleMessage = $module->translate('%sTitle%s was truncated for %sitem %d%s.');
	    $summaryMessage = $module->translate('%sSummary%s was truncated for %sitem %d%s.');
	    $setMessage = $module->translate('Set:');
	    $truncatedMessage = $module->translate('Truncated:');
	}

	$data = array($item->getTitle(), $sourceData['title'], $titleMessage,
		      $item->getSummary(), $sourceData['summary'], $summaryMessage);
	while (!empty($data)) {
	    list ($value, $source, $message) = array_splice($data, 0, 3);
	    if ($value != $source) {
		if (!isset($urlGenerator)) {
		    $urlGenerator =& $gallery->getUrlGenerator();
		}
		printf("<div class=\"gbBlock\">$message<br/>" .
		   "<b>$setMessage</b> %s<br/><b>$truncatedMessage</b> %s</div>",
		   '<b>', '</b>',
		   '<a href="' . $urlGenerator->generateUrl(
			array('view' => 'core.ItemAdmin', 'subView' => 'core.ItemEdit',
			      'itemId' => $item->getId(), 'editPlugin' => 'ItemEditItem')) . '">',
		   $item->getId(),
		   '</a>',
		   $value,
		   substr($source, strlen($value)));
	    }
	}

	return null;
    }
}


/**
 * @package Migrate
 * @subpackage UserInterface
 */
class ConfirmImportView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	$platform =& $gallery->getPlatform();
	$illegalAlbumNames = $existingAlbums = $titles = array();
	$albumValidOwner = $albumOwnerUserName = $albumOwnerUid = $albumfields = array();
	if (!isset($form['sourceAlbums'])) {
	    $form['sourceAlbums'] = array();
	}
	if (!isset($form['sourceEncoding'])) {
	    $form['sourceEncoding'] = 'ISO-8859-1';
	}
	$albumsPath = GalleryUtilities::getRequestVariables('albumsPath');
	$destinationAlbumID = $form['destinationAlbumID'];
	list ($ret, $uids) = Gallery1DataParser::getUserUids($albumsPath);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $albums) = Gallery1DataParser::getAlbumList($albumsPath);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $targetAlbum) = GalleryCoreApi::loadEntitiesById($destinationAlbumID);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	foreach ($form['sourceAlbums'] as $key => $value) {
	    /*
	     * We shouldn't really need to encode album names, but we may pass non UTF-8
	     * characters in the form data, so we urlencode these values to avoid mangling
	     * by some browsers.
	     */
	    $form['sourceAlbums'][$key] = urldecode($value);
	}
	foreach ($form['sourceAlbums'] as $sourceAlbumName) {
	    if (!in_array($sourceAlbumName, array_keys($albums))) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	    }
	    list ($ret, $albumfields[$sourceAlbumName]) =
		Gallery1DataParser::loadAlbumFields($albumsPath . $sourceAlbumName);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}
	$i = 0;
	while ($albumfields) {
	    $existingAlbumId = false;
	    $album = array_shift($albumfields);
	    $albumName = $album['name'];
	    if (!in_array($album['parentAlbumName'], $form['sourceAlbums'])) {
		list ($ret, $existingAlbumId) =
		    GalleryCoreApi::fetchChildIdByPathComponent($destinationAlbumID, $albumName);
	    }
	    if ($ret) {
		if (!$ret->getErrorCode() & ERROR_MISSING_OBJECT) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$existingAlbums[$albumName] = false;
	    } else {
		$existingAlbums[$albumName] = $existingAlbumId;
	    }
	    $titles[$albumName] =
		GalleryCoreApi::convertToUtf8($album['title'], $form['sourceEncoding']);

	    if (!$platform->isLegalPathComponent($albumName)){
		$illegalAlbumNames[$albumName] = $platform->legalizePathComponent($albumName);
	    } else {
		$illegalAlbumNames[$albumName] = false;
	    }

	    $validUid = Gallery1DataParser::isValidUid($albumsPath, $album['owner']);
	    if ($validUid) {
		list ($ret, $testUser) = Gallery1DataParser::getUserFieldsByUid($albumsPath,
									       $album['owner']);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$albumOwnerUserName[$albumName] = $testUser['username'];
		$albumOwnerUid[$albumName] = $album['owner'];
		list ($ret, $owner) = GalleryCoreApi::fetchUserByUsername(
		    GalleryCoreApi::convertToUtf8($testUser['username'], $form['sourceEncoding']));
		if ($ret && !($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if (isset($form['migrateUser'][$album['owner']])) {
		    $importingOwner = true;
		} else {
		    $importingOwner = false;
		}
		if ($owner || $importingOwner) {
		    $albumValidOwner[$albumName] = true;
		} else {
		    $albumValidOwner[$albumName] = false;
		}
	    } else {
		$albumValidOwner[$albumName] = true;
		$albumOwnerUserName[$albumName] = 'admin';
		$albumOwnerUid[$albumName] = null;
	    }
	    $i++;
	}
	list ($ret, $hiddenInterface) = GalleryCoreApi::newFactoryInstance('HiddenInterface_1_0');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$ConfirmImport = array();
	$ConfirmImport['uids'] = $uids;
	$ConfirmImport['albums'] = $albums;
	$ConfirmImport['albumsPath'] = $albumsPath;
	$ConfirmImport['existingAlbums'] = $existingAlbums;
	$ConfirmImport['illegalAlbumNames'] = $illegalAlbumNames;
	$ConfirmImport['destinationAlbumID'] = $destinationAlbumID;
	$ConfirmImport['albumValidOwner'] = $albumValidOwner;
	$ConfirmImport['albumOwnerUserName'] = $albumOwnerUserName;
	$ConfirmImport['albumOwnerUid'] = $albumOwnerUid;
	$ConfirmImport['targetAlbum'] = (array)$targetAlbum;
	$ConfirmImport['titles'] = $titles;
	$ConfirmImport['canHide'] = isset($hiddenInterface);
	$template->setVariable('controller', 'migrate.ConfirmImport');
	$template->setVariable('ConfirmImport', $ConfirmImport);
	return array(null,
		     array('body' => 'modules/migrate/templates/ConfirmImport.tpl'));
    }
}
?>
