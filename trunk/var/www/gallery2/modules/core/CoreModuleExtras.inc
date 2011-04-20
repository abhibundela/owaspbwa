<?php
/*
 * $RCSfile: CoreModuleExtras.inc,v $
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
 * @version $Revision: 1.165.2.2 $ $Date: 2006/04/05 22:19:54 $
 * @package GalleryCore
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Extra, rarely used core module code.  Most modules will not need to push
 * their extra code into a separate class, but the core module has a lot of
 * install code that is very rarely used so we tuck it out of the way.
 *
 * @package GalleryCore
 * @static
 */
class CoreModuleExtras {

    /**
     * @see GalleryModule::upgrade()
     * @param object GalleryModule the core module
     * @param string the current installed version
     * @static
     */
    function upgrade($module, $currentVersion, $statusMonitor) {
	global $gallery;
	$storage =& $gallery->getStorage();
	$platform =& $gallery->getPlatform();
	$gallery->debug('Entering CoreModuleExtras::upgrade');

	/*
	 * We store our version outside of the database so that we can upgrade
	 * even if the database is in an undependable state.
	 */
	$versions = $module->getInstalledVersions();
	$currentVersion = $versions['core'];
	if (!isset($currentVersion)) {
	    $gallery->debug('Current version not set');
	    /*
	     * This is either an initial install or an upgrade from version
	     * 0.8 (which didn't have the core versions.dat file).  Use a module
	     * parameter as our acid test.
	     *
	     * TODO: Get rid of this when we get to the final release.  It's
	     * only useful in the alpha -> beta transition.
	     */
	    list ($ret, $paramValue) = $module->getParameter('permissions.directory');
	    if (isset($paramValue)) {
		$currentVersion = '0.8';
	    } else {
		$currentVersion = '0';
	    }
	}
	if (substr($currentVersion, 0, 6) == '1.0.0.') {
	    /* Enable upgrade from any Gallery 2.0.x version */
	    $currentVersion = '1.0.0.x';
	}

	/**
	 * README: How to update the block below.
	 *
	 * If you add a new feature to the core module and revise the version, you should do the
	 * following.  Supposing the current version is 1.0.1 and you're adding 1.0.2. Go to the
	 * end of the switch and find the 'end of upgrade path' case.  Create a new case *above*
	 * that one with the old version number.  For our example you'd add: "case '1.0.1':" and
	 * then your code.  Do *not* put in a break statement. (Update _prepareConfigUpgrade too)
	 */
	$gallery->debug(sprintf('The current version is %s', $currentVersion));
	switch ($currentVersion) {
	case '0':
	    $gallery->debug('Install core module');
	    /*
	     * Checkpoint (commit configurStore transaction)
	     * Later in the installation code, we create the root album and therefore need
	     * locking. Locks are acquired with a non-transactional db connection. So before we
	     * can query the db with a second connection, the INSERT id into SequenceLock needs to
	     * be committed. Related g2 bug id: 1235284.
	     */
	    $ret = $storage->checkPoint();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.15);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    if (GalleryUtilities::isA($platform, 'WinNtPlatform')) {
		$flockType = 'database';
	    } else {
		$fileToLock = $platform->fopen(__FILE__, 'r');
		$wouldBlock = false;
		if ($platform->flock($fileToLock, LOCK_SH, $wouldBlock) || $wouldBlock) {
		    $flockType = 'flock';
		} else {
		    $flockType = 'database';
		}
		$platform->fclose($fileToLock);
	    }
	    $gallery->debug(sprintf('Locktype %s selected', $flockType));
	    /* Initial install.  Make sure all our module parameters are set. */
	    $gallery->debug('Set core module parameters');
	    GalleryCoreApi::requireOnce('modules/core/classes/GalleryTranslator.class');
	    foreach (array('permissions.directory' => '0755',
			   'permissions.file' => '0644',
			   'exec.expectedStatus' => '0',
			   'exec.beNice' => '0',
			   'default.orderBy' => 'orderWeight',
			   'default.orderDirection' => '1',
			   'default.theme' => 'matrix',
			   'default.language' => GalleryTranslator::getLanguageCodeFromRequest(),
			   'language.useBrowserPref' => '0',
			   'default.newAlbumsUseDefaults' => 'false',
			   'session.lifetime' => 21 * 86400, /* three weeks */
			   'session.inactivityTimeout' => 7 * 86400, /* one week */
			   'misc.markup' => 'bbcode',
			   'lock.system' => $flockType,
			   'format.date' => '%x',
			   'format.time' => '%X',
			   'format.datetime' => '%c',
			   'repository.updateTime' => '0',
			   'acceleration' => serialize(array('guest' => array('type' => 'none'),
							     'user' => array('type' => 'none'))),
			   'validation.level' => 'MEDIUM',
			   ) as $key => $value) {
		if (!isset($param[$key])) {
		    $ret = $module->setParameter($key, (string)$value);
		    if ($ret) {
			$gallery->debug(sprintf('Error: Failed to set core module parameter %s, ' .
					       'this is the error stack trace: %s', $key,
					       $ret->getAsText()));
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.2);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    /* Activate the Matrix theme */
	    $gallery->debug('Load Matrix theme');
	    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', 'matrix');
	    if ($ret) {
		$gallery->debug(sprintf('Error: Failed to load matrix theme, this is the error ' .
				'stack trace; %s', $ret->getAsText()));
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.25);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    $gallery->debug('InstallOrUpgrade Matrix theme');
	    $ret = $theme->installOrUpgrade();
	    if ($ret) {
		$gallery->debug(sprintf('Error: Failed to installOrUpgrade matrix theme, this is ' .
				'the error stack trace; %s', $ret->getAsText()));
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.3);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    $gallery->debug('Activate Matrix theme');
	    list ($ret, $ignored) = $theme->activate(false);
	    if ($ret) {
		$gallery->debug(sprintf('Error: Failed to activate matrix theme, this is ' .
				'the error stack trace; %s', $ret->getAsText()));
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.4);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    /*
	     * Register our permissions.  Since we're storing internationalized
	     * strings in the database, we have to give our internationalized
	     * string extractor a clue that these strings get translated.  So
	     * put a line like this translate('key') in for each description so
	     * that our extractor can find it.
	     */
	    $gallery->debug('Register core module permissions');
	    $permissions[] = array('all', $gallery->i18n('All access'),
				   GALLERY_PERMISSION_ALL_ACCESS, array());
	    $permissions[] = array('view', $gallery->i18n('[core] View item'), 0, array());
	    $permissions[] = array('viewResizes', $gallery->i18n('[core] View resized version(s)'),
				   0, array());
	    $permissions[] = array('viewSource', $gallery->i18n('[core] View original version'),
				   0, array());
	    $permissions[] = array('viewAll', $gallery->i18n('[core] View all versions'),
				   GALLERY_PERMISSION_COMPOSITE,
				   array('core.view', 'core.viewResizes', 'core.viewSource'));
	    $permissions[] = array('addAlbumItem', $gallery->i18n('[core] Add sub-album'),
				   0, array());
	    $permissions[] = array('addDataItem', $gallery->i18n('[core] Add sub-item'),
				   0, array());
	    $permissions[] = array('edit', $gallery->i18n('[core] Edit item'), 0, array());
	    $permissions[] = array('changePermissions',
				   $gallery->i18n('[core] Change item permissions'), 0, array());
	    $permissions[] = array('delete', $gallery->i18n('[core] Delete item'), 0, array());
	    foreach ($permissions as $p) {
		$ret = GalleryCoreApi::registerPermission(
		    $module->getId(), 'core.' . $p[0], $p[1], $p[2], $p[3]);
		if ($ret) {
		    $gallery->debug(sprintf('Error: Failed to register a permission, ' .
					    'this is the error stack trace: %s',
					    $ret->getAsText()));
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.5);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    foreach (array('_createAccessListCompacterLock',
			   '_createAllUsersGroup',
			   '_createSiteAdminsGroup',
			   '_createEverybodyGroup',
			   '_createAnonymousUser',
			   '_createAdminUser',
			   '_createRootAlbumItem') as $func) {

		$gallery->debug(sprintf('Call user func %s', $func));
		$ret = call_user_func(array('CoreModuleExtras', $func), $module);
		if ($ret) {
		    $gallery->debug(sprintf('Error: %s returned an error, ' .
					   'this is the error stack trace: %s', $func,
					   $ret->getAsText()));
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.6);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);

	    $gallery->debug('Initialize MIME types');
	    GalleryCoreApi::requireOnce(
		'modules/core/classes/helpers/GalleryMimeTypeHelper_advanced.class');
	    $ret = GalleryMimeTypeHelper_advanced::initializeMimeTypes();
	    if ($ret) {
		$gallery->debug(sprintf('Error: Failed to initialize MIME types, this is ' .
					'the error stack trace: %s', $ret->getAsText()));
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->debug('CoreModulesExtra::upgrade: successfully installed core');

	    $ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Installing the core module'), '', 0.7);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $gallery->guaranteeTimeLimit(180);
	    break;

	case '0.8':
	    $gallery->debug('Warning: Upgrading from version 0.8 (not supported)');
	case '0.8.1':
	case '0.8.2':
	    /*
	     * Update our framework module parameters to have a leading
	     * underscore so that we have our own separate namespace.
	     */
	    $query = '
	    UPDATE
	       [GalleryPluginParameterMap]
	    SET
	       [::parameterName] = ?
	    WHERE
	       [GalleryPluginParameterMap::parameterName] = ?
	       AND
	       [GalleryPluginParameterMap::pluginType] = \'module\'
	       AND
	       [GalleryPluginParameterMap::itemId] = 0
	    ';
	    $ret = $storage->execute($query, array('_version', 'version'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $storage->execute($query, array('_callbacks', 'callbacks'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Added a new parameter */
	    $ret = $module->setParameter('misc.login', 'both');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.3':
	case '0.8.4':
	case '0.8.5':
	    /* Added GalleryItem::originationTimestamp */
	    $ret = $storage->configureStore($module->getId(), array('GalleryItem:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Copy viewedSinceTimestamp to originationTimestamp as both default to time() */
	    $query = '
	    UPDATE
	      [GalleryItem]
	    SET
	      [::originationTimestamp] = [::viewedSinceTimestamp]
	    ';
	    $ret = $storage->execute($query, array());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.6':
	case '0.8.7':
	    $ret = $module->setParameter('default.newAlbumsUseDefaults', 'false');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.8':
	    /*
	     * This was not originally part of the 0.8.9 upgrade, but added much later.
	     * Upgrade code after this will need valid factory registrations so we can't
	     * wait until upgrade() completes to register during reactivate()..
	     */
	    $ret = CoreModuleExtras::performFactoryRegistrations($module);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.9':
	    /*
	     * Set all factory implementation weights to 5.  We'll re-register
	     * all core implementations with a weight of 4 so that they get
	     * precedence.
	     */
	    $query = 'UPDATE [GalleryFactoryMap] SET [::orderWeight] = 5';
	    $ret = $storage->execute($query, array());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.10':
	case '0.8.11':
	case '0.8.12':
	    $ret = $module->setParameter('lock.system', 'flock');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.13':
	    /* We used to add layout versioning here.  Now that's been moved to the 0.9.29 block */

	case '0.8.14':
	    /* Added Entity::onLoadHandlers; default all values to null */
	    $ret = $storage->configureStore($module->getId(), array('GalleryEntity:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.15':
	    /* Removed GalleryItemPropertiesMap */

	case '0.8.16':
	    /* Schema updates: GalleryPluginMap, GalleryPluginParameterMap, GalleryGroup */
	    $ret = $storage->configureStore($module->getId(),
		array('GalleryPluginMap:1.0', 'GalleryPluginParameterMap:1.0', 'GalleryGroup:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.8.17':
	    /* Beta 1! */

	case '0.9.0':
	    $ret = $module->removeParameter('misc.useShortUrls');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.1':
	    /* Set g2 version to 2.0-beta-1+ */

	case '0.9.2':
	    /* Changed the data cache format */

	case '0.9.3':
	    /* CSS refactor across entire app */

	case '0.9.4':
	    $gallery->guaranteeTimeLimit(30);
	    GalleryCoreApi::requireOnce(
		'modules/core/classes/helpers/GalleryMimeTypeHelper_advanced.class');
	    $ret = GalleryMimeTypeHelper_advanced::initializeMimeTypes();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.5':
	    $gallery->guaranteeTimeLimit(30);
	    $ret = CoreModuleExtras::_createAccessListCompacterLock($module);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /*
	     * Choose an item that has permission rows.  Find all other items with the same
	     * exact permissions.  Create a new ACL, assign all those items to the ACL, delete
	     * those rows from the permissions table.  Repeat.
	     */
	    $totalRowsQuery = '
	    SELECT
	      COUNT(DISTINCT [GalleryPermissionMap::itemId])
	    FROM
	      [GalleryPermissionMap]
	    ';

	    $findItemIdQuery = '
	    SELECT
	      [GalleryPermissionMap::itemId], COUNT(*) AS C
	    FROM
	      [GalleryPermissionMap]
	    GROUP BY
	      [GalleryPermissionMap::itemId]
	    ORDER BY
	      C DESC
	    ';

	    $permissionRowCountQuery = '
	    SELECT
	      COUNT(*)
	    FROM
	      [GalleryPermissionMap]
	    WHERE
	      [GalleryPermissionMap::itemId] = ?
	    ';

	    /* Updated this query for core 1.0.11 to write to userOrGroupId column */
	    $createAclQuery = '
	    INSERT INTO
	      [GalleryAccessMap] ([::accessListId], [::userOrGroupId], [::permission])
	    SELECT
	      ?,
	      [GalleryPermissionMap::userId] + [GalleryPermissionMap::groupId],
	      [GalleryPermissionMap::permission]
	    FROM
	      [GalleryPermissionMap]
	    WHERE
	      [GalleryPermissionMap::itemId] = ?
	    ';

	    $findPossibleDupesQuery = '
	    SELECT
	      [GalleryPermissionMap=2::itemId], COUNT(*)
	    FROM
	      [GalleryPermissionMap=1], [GalleryPermissionMap=2]
	    WHERE
	      [GalleryPermissionMap=1::itemId] = ?
	      AND
	      [GalleryPermissionMap=1::userId] = [GalleryPermissionMap=2::userId]
	      AND
	      [GalleryPermissionMap=1::groupId] = [GalleryPermissionMap=2::groupId]
	      AND
	      [GalleryPermissionMap=1::permission] = [GalleryPermissionMap=2::permission]
	    GROUP BY
	      [GalleryPermissionMap=2::itemId]
	    HAVING
	      COUNT(*) = ?
	    ';

	    $refineDupesQuery = '
	    SELECT
	      [GalleryPermissionMap::itemId], COUNT(*)
	    FROM
	      [GalleryPermissionMap]
	    WHERE
	      [GalleryPermissionMap::itemId] IN (%s)
	    GROUP BY
	      [GalleryPermissionMap::itemId]
	    HAVING
	      COUNT(*) = ?
	    ';

	    $assignAclQuery = '
	    INSERT INTO
	      [GalleryAccessSubscriberMap] ([::itemId], [::accessListId])
	    SELECT DISTINCT
	      [GalleryPermissionMap::itemId], ?
	    FROM
	      [GalleryPermissionMap]
	    WHERE
	      [GalleryPermissionMap::itemId] IN (%s)
	    ';

	    $deleteOldPermsQuery = '
	    DELETE FROM
	      [GalleryPermissionMap]
	    WHERE
	      [GalleryPermissionMap::itemId] IN (%s)
	    ';

	    /* Determine how many items we are going to process for our status message */
	    list ($ret, $results) =
		$gallery->search($totalRowsQuery, array(), array('limit' => array('count' => 1)));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if ($results->resultCount() == 0) {
		break;
	    }
	    $result = $results->nextResult();
	    $totalPermissionItems = $result[0];

	    $itemsProcessed = 0;
	    if ($totalPermissionItems > 0) {
		$ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Upgrading permissions'),
		    null,
		    $itemsProcessed / $totalPermissionItems);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    while ($totalPermissionItems > 0 && true) {
		$gallery->guaranteeTimeLimit(60);

		/* Find the next item in the permissions table */
		list ($ret, $results) = $storage->search($findItemIdQuery);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		if ($results->resultCount() == 0) {
		    break;
		}
		$result = $results->nextResult();
		list ($targetItemId, $permissionRowCount) = array((int)$result[0], (int)$result[1]);

		/* Create a new ACL */
		list ($ret, $newAclId) = $storage->getUniqueId();
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		$ret = $storage->execute($createAclQuery, array($newAclId, $targetItemId));
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/*
		 * Find all items that share the same permissions as the target.  I haven't
		 * figured out a good way to do aggregation without using temporary tables,
		 * which I'd like to avoid for portability.  So, figure out how many rows
		 * have at least as many matching permissions as our target item.  These
		 * are potentially dupes.  We'll refine them later on.
		 */
		list ($ret, $results) = $gallery->search(
		    $findPossibleDupesQuery, array($targetItemId, $permissionRowCount));
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		$possibleDupeIds = array();
		while ($result = $results->nextResult()) {
		    $possibleDupeIds[] = (int)$result[0];
		}

		/*
		 * Process these queries in chunks since we may have thousands of items with the
		 * same permissions and we don't want to give the database a heart attack.
		 */
		$chunkSize = 200;
		while (!empty($possibleDupeIds)) {
		    $chunk = array_splice($possibleDupeIds, 0, $chunkSize);
		    $count = count($chunk);

		    /*
		     * Refine our dupes by eliminating ones that don't have exactly the same
		     * number of permission rows as our target.  Our target item is included
		     * in the dupes, so this will always return at least 1 row.
		     */
		    $markers = GalleryUtilities::makeMarkers($count);
		    $query = sprintf($refineDupesQuery, $markers);
		    list ($ret, $results) = $gallery->search(
			$query, array_merge($chunk, array($permissionRowCount)));
		    $possibleDupeIds = array();

		    $dupeIds = array();
		    while ($result = $results->nextResult()) {
			$dupeIds[] = (int)$result[0];
		    }

		    if (empty($dupeIds)) {
			/* No actual dupes?  Try the next chunk */
			continue;
		    }

		    $count = count($dupeIds);
		    $markers = GalleryUtilities::makeMarkers($count);

		    /* Set all the dupe items in this chunk to use the new ACL */
		    $query = sprintf($assignAclQuery, $markers);
		    $ret = $storage->execute($query, array_merge(array($newAclId), $dupeIds));
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    /* Remove all the permission rows for the migrated items */
		    $query = sprintf($deleteOldPermsQuery, $markers);
		    $ret = $storage->execute($query, $dupeIds);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    $itemsProcessed += $count;

		    $ret = $statusMonitor->renderStatusMessage(
			$module->translate(array(
			    'text' => 'Upgrading permissions (%d items complete, %d remaining)',
			    'arg1' => $itemsProcessed,
			    'arg2' => $totalPermissionItems - $itemsProcessed)),
			'',
			$itemsProcessed / $totalPermissionItems);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }

	    if ($totalPermissionItems > 0) {
		$ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Deleting old permission tables'),
		    '',
		    $itemsProcessed / $totalPermissionItems);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    /* Removed GalleryPermissionMap */

	case '0.9.6':
	    /* Added GalleryMaintenance table */

	case '0.9.7':
	    /*
	     * Change GalleryMaintenance::details column to be a serialized array.  The old data
	     * is transient so just delete it. Added FlushTemplatesTask, FlushDatabaseCacheTask
	     */
	    $gallery->guaranteeTimeLimit(30);
	    $ret = GalleryCoreApi::removeAllMapEntries('GalleryMaintenanceMap');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.8':
	    /*
	     * Create 'plugins' and 'plugins_data' directories in g2data.  Remove trailing slash
	     * for config paths using substr so file_exists can detect either file or dir.
	     * Update: in core 1.0.6 the data.gallery.plugins dir moved under gallery2 basedir,
	     * not in g2data anymore; we may not have permission to create a dir here.
	     * So code below is now updated to not require those mkdirs to succeed.
	     */
	    $gallery->guaranteeTimeLimit(30);
	    foreach (array(substr($gallery->getConfig('data.gallery.plugins'), 0, -1) => false,
			   $gallery->getConfig('data.gallery.plugins') . 'modules' => false,
			   $gallery->getConfig('data.gallery.plugins') . 'layouts' => false,
			   substr($gallery->getConfig('data.gallery.plugins_data'), 0, -1) => true,
			   $gallery->getConfig('data.gallery.plugins_data') . 'modules' => true,
			   $gallery->getConfig('data.gallery.plugins_data') . 'layouts' => true)
		    as $dir => $isRequired) {
		if ($platform->file_exists($dir)) {
		    if ($platform->is_dir($dir)) {
			/* No need to do anything.  Except maybe we could check permissions here. */
		    } else {
			/* There's a file there.  There shouldn't be.  Move it out of the way */
			if (!@$platform->rename($newDir, "$newDir.old") ||
				!@$platform->mkdir($dir)) {
			    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
				"$dir already exists; unable to replace it");
			}
		    }
		} else {
		    if (!@$platform->mkdir($dir) && $isRequired) {
			return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
						    "Unable to create $dir");
		    }
		}
	    }

	case '0.9.9':
	    /* Beta 2 release! */

	case '0.9.10':
	    /* Added BuildDerivativesTask */

	case '0.9.11':
	    /* Added GalleryRecoverPasswordMap */

	case '0.9.12':
	    /* Added ResetViewCountsTask */
	case '0.9.13':
	    /* Added SystemInfoTask */
	case '0.9.14':
	    /* Added SetOriginationTimestampTask */

	case '0.9.15':
	    /*
	     * Changed 'All Users' to 'Registered Users'
	     * Don't change if the user modified the name already!
	     * Don't change if there is already a group with the new name
	     */
	    list ($ret, $group) =
		GalleryCoreApi::fetchGroupByGroupName($module->translate('Registered Users'));
	    if ($ret) {
		if ($ret->getErrorCode() & ERROR_MISSING_OBJECT) {
		    /* Ok, we can change the group name */

		    list ($ret, $allUserGroupId) = $module->getParameter('id.allUserGroup');
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($allUserGroupId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    list ($ret, $group) = GalleryCoreApi::loadEntitiesById($allUserGroupId);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $allUserGroupName = $group->getGroupName();
		    /* We used to entitize data in db; expect that from orignal group name: */
		    $originalGroupName = GalleryUtilities::utf8ToUnicodeEntities(
			$module->translate('All Users'));
		    if (!strcmp($allUserGroupName, $originalGroupName)) {
			$group->setGroupName($module->translate('Registered Users'));
			$ret = $group->save();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$ret = GalleryCoreApi::releaseLocks($lockId);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		} else {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    } /* else a group with that name already exists, nothing to do */

	case '0.9.16':
	    /* Beta 3 release! */

	case '0.9.17':
	    /* Split uploadLocalServer.dirs list into one parameter per entry */
	    list ($ret, $dirList) = $module->getParameter('uploadLocalServer.dirs');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    if (!empty($dirList)) {
		$dirList = explode(',', $dirList);
		for ($i = 1; $i <= count($dirList); $i++) {
		    $ret = $module->setParameter('uploadLocalServer.dir.' . $i, $dirList[$i - 1]);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }
	    $ret = $module->removeParameter('uploadLocalServer.dirs');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.18':
	    /* Add image/x-photo-cd mime type */
	    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime('pcd');
	    if (!$ret && $mimeType == 'application/unknown') {
		$ret = GalleryCoreApi::addMimeType('pcd', 'image/x-photo-cd', false);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.19':
	    /* New multisite system and support for config.php upgrades */
	case '0.9.20':
	    /* Change view/controller separator: core:ShowItem -> core.ShowItem */
	case '0.9.21':
	    /* Session cookie change, requires new config.php variable */
	case '0.9.22':
	    /* GalleryModule::getItemLinks API change (GalleryModule API bumped to 0.13) */

	case '0.9.23':
	    /* Session cookie change, revert the last change and try something new */
	    foreach (array('cookie.path', 'cookie.domain') as $parameterName) {
		$ret = $module->setParameter($parameterName, '');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.24':
	    /* Add image/jpeg-cmyk mime type */
	    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime('jpgcmyk');
	    if (!$ret && $mimeType == 'application/unknown') {
		$ret = GalleryCoreApi::addMimeType('jpgcmyk', 'image/jpeg-cmyk', false);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	case '0.9.25':
	    /* And image/tiff-cmyk mime type */
	    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime('tifcmyk');
	    if (!$ret && $mimeType == 'application/unknown') {
		$ret = GalleryCoreApi::addMimeType('tifcmyk', 'image/tiff-cmyk', false);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	case '0.9.26':
	    /* Added GalleryDerivative::isBroken; default all values to null */
	    $ret = $storage->configureStore($module->getId(), array('GalleryDerivative:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.27':
	    /* Mark old broken derivatives as such with our new isBroken flag */
	    /*
	     * This is the filesize and the crc32 checksum of the broken derivative placeholder
	     * image that we used in beta 3 and earlier versions. We may have replaced this image
	     * by the time this upgrade code is run. Thus we hardcode filesize(oldImage) and
	     * crc32(oldImageData) here.
	     */
	    $referenceSize = 1589;
	    /* CRC is a good measure to compare files (not to detect malicous attacks though) */
	    $referenceCrc = 888290220;

	    /*
	     * 1. Get a list of all derivatives that are not already marked as isBroken
	     *    (We can't count on derivativeSize being correct, so check all derivatives)
	     * Update: upgrade from pre-beta-1 will fail to load RandomHighlightDerivativeImage
	     *         so restrict this query to only GalleryDerivativeImage
	     */
	    $gallery->guaranteeTimeLimit(60);
	    $query = 'SELECT [GalleryDerivative::id]
		      FROM [GalleryDerivative], [GalleryEntity]
		      WHERE [GalleryDerivative::isBroken] IS NULL
		      AND [GalleryDerivative::id] = [GalleryEntity::id]
		      AND [GalleryEntity::entityType] = \'GalleryDerviativeImage\'';
	    list ($ret, $searchResults) = $gallery->search($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Check the derivatives that match the search criteria */
	    if ($searchResults->resultCount() > 0) {
		$derivativeIds = array();
		while ($result = $searchResults->nextResult()) {
		    $derivativeIds[] = $result[0];
		}
		$totalDerivatives = sizeof($derivativeIds);

		/*
		 * The following process is very expensive.
		 * We have to deal with a potentially huge (10^6) amount of derivatives.
		 * To not exceed the memory limit we do everything in batches.
		 * To not exceed the PHP execution time limit and to not exceed the apache timeout
		 * we add a progress bar and manipulate the PHP execution time limit periodically.
		 */
		$gallery->guaranteeTimeLimit(60);

		/* Show a progress bar */
		$ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Detecting broken derivatives'), '', 0);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/*
		 * The outer loop is for each derivativeId and we upgrade a progress bar every
		 * $progressStepSize ids. We don't load entity by entity, but in batches of
		 * $loadBatchSize. And we don't save the items that were detected as broken
		 * derivatives one by one, but also in batches of $saveBatchSize, i.e. we acquire
		 * and release the locks in this batch size, but still have to save entity by
		 * entity because G2 has no mass entity save like loadEntitiesById().
		 */
		$derivatives = array();
		$progressStepSize = min(500, intval($totalDerivatives / 10));
		$loadBatchSize = 1000;
		$saveBatchSize = 1000;
		$itemsProcessed = 0;
		$brokenDerivatives = array();
		do {
		    /* 2. Load the entities in batches */
		    if (empty($derivatives) && !empty($derivativeIds)) {
			/* Prevent PHP timeout */
			$gallery->guaranteeTimeLimit(60);
			/* Prevent apache timeout */
			$ret = $statusMonitor->renderStatusMessage(
			    $module->translate(
				array('text' => 'Detecting broken derivatives, loading ' .
				      '(%d derivatives checked, %d remaining)',
				      'arg1' => $itemsProcessed,
				      'arg2' => sizeof($derivativeIds))),
			    '',  $itemsProcessed / $totalDerivatives);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$currentDerivativeIds = array_splice($derivativeIds, 0, $loadBatchSize);
			list ($ret, $derivatives) =
			    GalleryCoreApi::loadEntitiesById($currentDerivativeIds);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }

		    /* Detect if the derivative is broken */
		    if (!empty($derivatives)) {
			$itemsProcessed++;
			$gallery->guaranteeTimeLimit(30);
			$derivative = array_pop($derivatives);

			/*
			 * Show the progress ..., but not for each derivative, this would slow down
			 * the process considerably
			 */
			if ($itemsProcessed % $progressStepSize == 0 ||
				$itemsProcessed == $totalDerivatives) {
			    $ret = $statusMonitor->renderStatusMessage(
				$module->translate(
				    array('text' => 'Detecting broken derivatives ' .
					  '(%d derivatives checked, %d remaining)',
					  'arg1' => $itemsProcessed,
					  'arg2' => $totalDerivatives - $itemsProcessed)),
				'', $itemsProcessed / $totalDerivatives);
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $gallery->guaranteeTimeLimit(30);
			}

			/*
			 * 3. Filter out derivatives that don't return true for isCacheCurrent
			 *    (= don't have a cache file yet = would be rebuilt anyway)
			 */
			list ($ret, $current) = $derivative->isCacheCurrent();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			if (!$current) {
			    continue;
			}

			/*
			 * 4. Filter out derivatives that don't have the same file size as the
			 *    broken image placeholder
			 */
			list($ret, $path) = $derivative->fetchPath();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			if (($size = $platform->filesize($path)) === false) {
			    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__,
							__LINE__);
			}
			if ($size != $referenceSize) {
			    continue;
			}

			/* 5. Binary compare the derivative file with the placeholder file */
			if (($data = $platform->file($path)) === false) {
			    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__,
							__LINE__);
			}
			$data = implode('', $data);
			if ($referenceCrc == crc32($data)) {
			    /* Add the derivative to the list of broken ones */
			    $brokenDerivatives[$derivative->getId()] = $derivative;
			}
		    }

		    /* 6. Mark the detected broken derivative as such and save it in the DB */
		    if (sizeof($brokenDerivatives) == $saveBatchSize ||
			    (!empty($brokenDerivatives) && empty($derivativeIds))) {
			$gallery->guaranteeTimeLimit(30);
			$saveProgressStepSize = min(200, intval(sizeof($brokenDerivatives) / 10));

			$ret = $statusMonitor->renderStatusMessage(
			    $module->translate(
				array('text' => 'Detecting broken derivatives, ' .
				      'saving ' .
				      '(%d derivatives checked, %d remaining)',
				      'arg1' => $itemsProcessed,
				      'arg2' => $totalDerivatives - $itemsProcessed)),
			    '', $itemsProcessed / $totalDerivatives);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			list ($ret, $lockId) =
			    GalleryCoreApi::acquireWriteLock(array_keys($brokenDerivatives));
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$itemsSaved = 0;
			foreach ($brokenDerivatives as $brokenDerivative) {
			    $itemsSaved++;
			    if ($itemsSaved % $saveProgressStepSize == 0) {
				$ret = $statusMonitor->renderStatusMessage(
				    $module->translate(
					array('text' => 'Detecting broken derivatives, saving ' .
					      'item %d of %d ' .
					      '(%d derivatives complete, %d remaining)',
					      'arg1' => $itemsSaved,
					      'arg2' => sizeof($brokenDerivatives),
					      'arg3' => $itemsProcessed,
					      'arg4' => $totalDerivatives - $itemsProcessed)),
				    '', $itemsProcessed / $totalDerivatives);
				if ($ret) {
				    GalleryCoreApi::releaseLocks($lockId);
				    return $ret->wrap(__FILE__, __LINE__);
				}
				$gallery->guaranteeTimeLimit(30);
			    }

			    $brokenDerivative->setIsBroken(true);
			    $ret = $brokenDerivative->save(true, false);
			    if ($ret) {
				GalleryCoreApi::releaseLocks($lockId);
				return $ret->wrap(__FILE__, __LINE__);
			    }
			}
			$brokenDerivatives = array();

			$ret = GalleryCoreApi::releaseLocks($lockId);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		    /*
		     * Continue if there are either unloaded ids, unchecked derivatives or
		     * unsaved derivatives
		     */
		} while (!empty($derivativeIds) || !empty($brokenDerivatives) ||
			 !empty($derivatives));
	    }

	case '0.9.28':
	    /* Changed Module Api onLoad($entity, $duringUpgrade) definition */

	case '0.9.29':
	    /* Ginormous layout and theme consolidation refactor */
	    $ret = $storage->configureStore($module->getId(),
					    array('GalleryPluginParameterMap:1.1'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $query = '
	    UPDATE
	      [GalleryPluginParameterMap]
	    SET
	      [::pluginType] = \'theme\'
	    WHERE
	      [GalleryPluginParameterMap::pluginType] = \'layout\'
	    ';
	    $ret = $storage->execute($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* After this refactor we only support the matrix theme */
	    $query = '
	    UPDATE
	      [GalleryAlbumItem]
	    SET
	      [::theme] = \'matrix\'
	    ';
	    $ret = $storage->execute($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $query = '
	    UPDATE
	      [GalleryPluginMap]
	    SET
	      [::pluginType] = \'theme\'
	    WHERE
	      [GalleryPluginMap::pluginType] = \'layout\'
	    ';
	    $ret = $storage->execute($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /*
	     * Rename g2data 'layouts' directories to be 'themes', or create them
	     * if they don't already exist (they should exist, though).
	     */
	    foreach (array($gallery->getConfig('data.gallery.plugins'),
			   $gallery->getConfig('data.gallery.plugins_data')) as $base) {
		if ($platform->file_exists("$base/themes")) {
		    if ($platform->file_exists("$base/layouts")) {
			$platform->recursiveRmDir("$base/layouts");
		    }
		} else if (file_exists($base)) {
		    if ($platform->file_exists("$base/layouts")) {
			$platform->rename("$base/layouts", "$base/themes");
		    } else {
			$platform->mkdir("$base/themes");
		    }
		}
	    }

	    /* Removed parameters */
	    foreach (array('language.selector', 'misc.login') as $paramName) {
		$ret = $module->removeParameter($paramName);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    /*
	     * If we're coming from 0.8.13 or earlier, then our themes don't have version
	     * information, so take care of that here by calling installOrUpgrade() on the
	     * currently active themes to let them update their bookkeeping.  Reactivate them too
	     * for good measure.
	     */
	    if (version_compare($currentVersion, '0.8.13', '<=')) {
		list ($ret, $themes) = GalleryCoreApi::fetchPluginStatus('theme');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		foreach ($themes as $themeId => $themeStatus) {
		    $gallery->guaranteeTimeLimit(30);
		    if (!empty($themeStatus['active'])) {
			list($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
			if ($ret &&
			    !($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH)) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			$ret = $theme->installOrUpgrade();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}

			list ($ret, $ignored) = $theme->activate(false);
			if ($ret &&
			    !($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH)) {
			    /*
			     * Theme getSettings may try to load ImageFrame interface, but
			     * ImageFrame may need to be upgraded.. ignore version mismatch here.
			     */
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }

	case '0.9.30':
	    /* Removed layout column from AlbumItem; matrix is only theme for now: set default */
	    $ret = $storage->configureStore($module->getId(), array('GalleryAlbumItem:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $ret = $module->setParameter('default.theme', 'matrix');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $ret = $module->removeParameter('default.layout');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $query = '
	    UPDATE
	      [GalleryAlbumItem]
	    SET
	      [::theme] = NULL
	    ';
	    $ret = $storage->execute($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.31':
	    /* Beta 4! */
	case '0.9.32':
	    /* Minor core api change */
	case '0.9.33':
	    /* Release Candidate 1! */

	case '0.9.34':
	    /* Add date/time formats */
	    foreach (array('format.date' => '%x', 'format.time' => '%X', 'format.datetime' => '%c')
		     as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.35':
	    /* Release Candidate 2! */

	case '0.9.36':
	    /*
	     * Fixed GalleryUtilities::getPseudoFileName for derivatives.
	     * Delete fast-download files that may have cached incorrect filenames.
	     */
	    $slash = $platform->getDirectorySeparator();
	    $baseDir = $gallery->getConfig('data.gallery.cache') . 'derivative' . $slash;
	    for ($i = 0; $i < 10; $i++) {
		$gallery->guaranteeTimeLimit(60);
		$ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Clearing fast-download cache'), '', $i / 10);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		for ($j = 0; $j < 10; $j++) {
		    $dir = $baseDir . $i . $slash . $j . $slash;
		    if ($dh = @$platform->opendir($dir)) {
			while (($file = $platform->readdir($dh)) !== false) {
			    if (substr($file, -9) == '-fast.inc') {
				@$platform->unlink($dir . $file);
			    }
			}
			$platform->closedir($dh);
		    }
		}
	    }
	    $ret = $statusMonitor->renderStatusMessage(
		$module->translate('Clearing fast-download cache'), '', 1);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.37':
	    /* 2.0 Release! */

	case '1.0.0':
	case '1.0.0.x':
	    /* Schema only upgrade */
	    $ret = $storage->configureStore($module->getId(),
					    array('GalleryPluginParameterMap:1.2'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.1':
	    /* And image/wmf mime type */
	    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime('wmf');
	    if (!$ret && $mimeType == 'application/unknown') {
		$ret = GalleryCoreApi::addMimeType('wmf', 'image/wmf', false);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '1.0.2':
	    /* Security fix */

	case '1.0.3':
	    /* Consolidated .sql files into schema.tpl */

	case '1.0.4':
	    /* Added maintenance mode */

	case '1.0.5':
	    /* Remove plugins directory from g2data. */
	    $pluginDirectory = $gallery->getConfig('data.gallery.base') . 'plugins';
	    $pluginDirectories = array($pluginDirectory . '/modules',
				       $pluginDirectory . '/themes',
				       $pluginDirectory);

	    foreach ($pluginDirectories as $pluginDirectory) {
		if (@$platform->file_exists($pluginDirectory)) {
		    /* We're not interested in whether it succeeded or not. */
		    @$platform->recursiveRmDir($pluginDirectory);
		}
	    }

	case '1.0.6':
	    /* Add PluginPackageMap table */

	case '1.0.7':
	    $ret = $module->setParameter('exec.beNice', '0');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.8':
	case '1.0.9':
	    /* Security fix in zipcart */

	case '1.0.10':
	    /* Rename unnamed pre-beta-3 index to named index */
	    if ($storage->getType() == 'mysql') {
		$gallery->debug('Rename unnamed pre-beta-3 index to named index (ignore errors)');
		$query = sprintf('
		ALTER TABLE %sAccessMap
		DROP INDEX %saccessListId_2,
		ADD INDEX %sAccessMap_83732(%saccessListId);',
		$storage->_tablePrefix, $storage->_columnPrefix, $storage->_tablePrefix,
		$storage->_columnPrefix);
		/* Ignore error, since there's nothing to do for most installations */
		$storage->execute($query);
	    }

	    /*
	     * Combine AccessMap userId/groupId into single userOrGroupId,
	     * and remove unused GALLERY_PERMISSION_ITEM_ADMIN permission flag.
	     * Also increase size of GalleryUser::email column.
	     */
	    $ret = $storage->configureStore($module->getId(),
					    array('GalleryAccessMap:1.0', 'GalleryUser:1.0'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* If coming from 0.9.5 or earlier then GalleryAccessMap already has userOrGroupId */
	    if (version_compare($currentVersion, '0.9.5', '>')) {
		$query = '
		UPDATE
		  [GalleryAccessMap]
		SET
		  [::userOrGroupId] = [::userId] + [::groupId]
		';
		$ret = $storage->execute($query, array());
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    $ret = $storage->configureStore($module->getId(), array('GalleryAccessMap:1.1'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $flagModifier) =
		$storage->getFunctionSql('BITAND', array('[::flags]', '?'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $query = '
	    UPDATE
	      [GalleryPermissionSetMap]
	    SET
	      [::flags] = ' . $flagModifier . '
	    ';
	    $ret = $storage->execute($query, array(3));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.11':
	    /* Several previous upgrades used 'modules' instead of 'module' with plugin params */
	    list ($ret, $coreParams) = $module->fetchParameters();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    foreach (array('misc.useShortUrls', 'language.selector') as $key) {
		if (isset($coreParams[$key])) {
		    $ret = $module->removeParameter('misc.useShortUrls');
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }
	    foreach (array('cookie.path' => '', 'cookie.domain' => '',
			   'exec.beNice' => '0', 'repository.updateTime' => '0')
		     as $key => $value) {
		if (!isset($coreParams[$key])) {
		    $ret = $module->setParameter($key, $value);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }
	    $ret = GalleryCoreApi::removeMapEntry(
		'GalleryPluginParameterMap', array('pluginType' => 'modules'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.12':
	    /* Add param 'language.useBrowserPref' */
	    list ($ret, $langCode) = $module->getParameter('default.language');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $useBrowserPref = '0';
	    if (empty($langCode)) {
		$useBrowserPref = '1';
		$ret = $module->setParameter('default.language', 'en_US');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	    $ret = $module->setParameter('language.useBrowserPref', $useBrowserPref);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.13':
	    /* Add config parameter: 'baseUri'*/
	case '1.0.14':
	    /* GalleryCoreApi 7.0 and GalleryModule 3.0 */
	case '1.0.15':
	    /*
	     * Add fast-download for GalleryDataItems too. Fast-download files are now in
	     * cache/entity/. Delete the old files in cache/derivative/.
	     */
	    $gallery->guaranteeTimeLimit(60);
	    $query = 'SELECT [GalleryDerivativeImage::id]
		      FROM [GalleryDerivativeImage]';
	    list ($ret, $searchResults) = $gallery->search($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if ($searchResults->resultCount() > 0) {
		$derivativeIds = array();
		while ($result = $searchResults->nextResult()) {
		    $derivativeIds[] = $result[0];
		}
		$totalDerivatives = count($derivativeIds);
		$base = $gallery->getConfig('data.gallery.cache');
		$gallery->guaranteeTimeLimit(60);

		/* Show a progress bar */
		$ret = $statusMonitor->renderStatusMessage(
		    $module->translate('Deleting old fast-download cache'), '', 0);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		$stepSize = min(100, intval($totalDerivatives / 10));
		for ($i = 0; $i < $totalDerivatives; $i++) {
		    /* Delete the file if it exists */
		    list ($first, $second) = GalleryDataCache::getCacheTuple($derivativeIds[$i]);
		    $fastDownloadFilePath = sprintf('%derivative/%s/%s/%d-fast.inc',
			    $base, $first, $second, $derivativeIds[$i]);
		    if ($platform->file_exists($fastDownloadFilePath)) {
			$platform->unlink($fastDownloadFilePath);
		    }

		    /* Update the progress bar / prevent timouts */
		    if ($i % $stepSize == 0 || $i == ($totalDerivatives - 1)) {
			$gallery->guaranteeTimeLimit(60);
			$ret = $statusMonitor->renderStatusMessage(
			    $module->translate('Deleting old fast-download cache'),
			    '',  ($i+1) / $totalDerivatives);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }
	case '1.0.16':
	    /* Added 'not-null' to Entities.inc and Map.inc */
	    $storageExtras =& $storage->_getExtras();
	    $storageExtras->_clearEntityAndMapCache();
	case '1.0.17':
	    /* And image/tga mime type */
	    list ($ret, $mimeType) = GalleryCoreApi::convertExtensionToMime('tga');
	    if (!$ret && $mimeType == 'application/unknown') {
		$ret = GalleryCoreApi::addMimeType('tga', 'image/tga', false);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '1.0.18':
	    /* Add index to GalleryEntity::linkId */
	    $ret = $storage->configureStore($module->getId(), array('GalleryEntity:1.1'));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.19':
	    /* Add page level caching and the GalleryCache map */
	    $acceleration = serialize(array('guest' => array('type' => 'none'),
					    'user' => array('type' => 'none')));
	    $ret = GalleryCoreApi::setPluginParameter(
		'module', 'core', 'acceleration', $acceleration);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.20':
	    /* Add configurable captcha security level */
	    $ret = GalleryCoreApi::setPluginParameter('module', 'core', 'captcha.level', 'MEDIUM');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.21':
	    /* GallerySession change: Store sessions in the database and no longer on disk */
	    $sessionsDir = $gallery->getConfig('data.gallery.base') . 'sessions' .
		$platform->getDirectorySeparator();
	    $stepSize = 100;
	    $count = 0;
	    $iterationSize = 5000;
	    $iteration = 1;
	    /* Show a progress bar while removing the files  */
	    $ret = $statusMonitor->renderStatusMessage(
		$module->translate(array('text' => 'Deleting old session files (iteration %d)',
					 'arg1' => $iteration)),
		'', 0);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $dir = $platform->opendir($sessionsDir, 'r');
	    if (!$dir) {
		return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
					     "Can't access session dir");
	    }
	    $gallery->guaranteeTimeLimit(60);
	    while (($filename = $platform->readdir($dir)) !== false) {
		if ($filename == '.' || $filename == '..') {
		    continue;
		}
		$count++;
		$platform->unlink($sessionsDir . $filename);

		/* Update the progress bar / prevent timouts */
		if ($count % $stepSize == 0) {
		    $gallery->guaranteeTimeLimit(60);
		    $ret = $statusMonitor->renderStatusMessage(
			$module->translate(
			    array('text' => 'Deleting old session files (iteration %d)',
				  'arg1' => $iteration)),
			'', $count / $iterationSize);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		if ($count > $iterationSize) {
		    $iteration++;
		    $count = 0;
		}
	    }
	    $platform->closedir($dir);
	    $platform->rmdir($sessionsDir);
	    $ret = $statusMonitor->renderStatusMessage(
		$module->translate(array('text' => 'Deleting old session files (iteration %d)',
					 'arg1' => $iteration)),
		'', 1);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.22':
	    /* Rename unnamed pre-beta-3 index to named index */
	    $gallery->guaranteeTimeLimit(120);
	    if ($storage->getType() == 'mysql') {
		$gallery->debug('Rename unnamed pre-beta-3 index to named index (ignore errors)');
		$indexChanges = array();
		$indexChanges[] = array('AccessMap', 'permission',
					'AccessMap_18058', array('permission'));
		$indexChanges[] = array('AccessSubscriberMap', 'accessListId',
					'AccessSubscriberMap_83732', array('accessListId'));
		$indexChanges[] = array('ChildEntity', 'parentId',
					'ChildEntity_52718', array('parentId'));
		$indexChanges[] = array('Derivative', 'derivativeSourceId',
					'Derivative_85338', array('derivativeSourceId'));
		$indexChanges[] = array('Derivative', 'derivativeOrder',
					'Derivative_25243', array('derivativeOrder'));
		$indexChanges[] = array('Derivative', 'derivativeType',
					'Derivative_97216', array('derivativeType'));
		$indexChanges[] = array('DerivativePrefsMap', 'itemId',
					'DerivativePrefsMap_75985', array('itemId'));
		$indexChanges[] = array('Entity', 'creationTimestamp',
					'Entity_76255', array('creationTimestamp'));
		$indexChanges[] = array('Entity', 'isLinkable',
					'Entity_35978', array('isLinkable'));
		$indexChanges[] = array('Entity', 'modificationTimestamp',
					'Entity_63025', array('modificationTimestamp'));
		$indexChanges[] = array('Entity', 'serialNumber',
					'Entity_60702', array('serialNumber'));
		$indexChanges[] = array('FileSystemEntity ', 'pathComponent',
					'FileSystemEntity_3406', array('pathComponent'));
		$indexChanges[] = array('Item', 'keywords', 'Item_99070', array('keywords'));
		$indexChanges[] = array('Item', 'ownerId', 'Item_21573', array('ownerId'));
		$indexChanges[] = array('Item', 'summary', 'Item_54147', array('summary'));
		$indexChanges[] = array('Item', 'title', 'Item_90059', array('title'));
		$indexChanges[] = array('ItemAttributesMap', 'parentSequence',
					'ItemAttributesMap_95270', array('parentSequence'));
		$indexChanges[] = array('MaintenanceMap', 'taskId',
					'MaintenanceMap_21687', array('taskId'));
		$indexChanges[] = array('PluginParameterMap', 'pluginType_2',
					'PluginParameterMap_12808',
					array('pluginType', 'pluginId', 'itemId'));
		$indexChanges[] = array('PluginParameterMap', 'pluginType_3',
					'PluginParameterMap_80596', array('pluginType'));
		$indexChanges[] = array('TkOperatnMimeTypeMap', 'operationName',
					'TkOperatnMimeTypeMap_2014', array('operationName'));
		$indexChanges[] = array('TkOperatnMimeTypeMap', 'mimeType',
					'TkOperatnMimeTypeMap_79463', array('mimeType'));
		$indexChanges[] = array('TkOperatnParameterMap', 'operationName',
					'TkOperatnParameterMap_2014', array('operationName'));
		$indexChanges[] = array('TkPropertyMimeTypeMap', 'propertyName',
					'TkPropertyMimeTypeMap_52881', array('propertyName'));
		$indexChanges[] = array('TkPropertyMimeTypeMap', 'mimeType',
					'TkPropertyMimeTypeMap_79463', array('mimeType'));
		$indexChanges[] = array('UserGroupMap', 'userId',
					'UserGroupMap_69068', array('userId'));
		$indexChanges[] = array('UserGroupMap', 'groupId',
					'UserGroupMap_89328', array('groupId'));
		$indexChanges[] = array('Lock', 'lockId',
					'Lock_11039', array('lockId'));
		foreach ($indexChanges as $change) {
		    $indexColumns = implode('`, `' . $storage->_columnPrefix, $change[3]);
		    $indexColumns = $storage->_columnPrefix . $indexColumns;
		    $query = sprintf('
		    ALTER TABLE `%s%s`
		    DROP INDEX `%s%s`,
		    ADD INDEX `%s%s`(`%s`);',
			$storage->_tablePrefix, $change[0], $storage->_columnPrefix, $change[1],
			$storage->_tablePrefix, $change[2], $indexColumns);
		    /* Ignore error, since there's nothing to do for most installations */
		    $storage->execute($query);
		}
		$gallery->debug('Finished renaming unnamed pre-beta-3 indices to named indices');
	    }

	    /* Commit transactions before we execute a query that we expect to fail */
	    $ret = $storage->checkPoint();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /*
	     * Also add a single column index on AccessMap.accessListId since it was forgotten in
	     * the initial upgrade code. Ignore errors since some installations already have it.
	     */
	    $gallery->debug('Adding an index to the AccessMap table, ignore errors');
	    $storage->configureStore($module->getId(), array('GalleryAccessMap:1.2'));

	    /* Postgres will abort the transaction if the index exists, so checkpoint here. */
	    $ret = $storage->checkpoint();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $gallery->debug('Finished adding an index to the AccessMap table');
	    /*
	     * Make sure the schema update is stored, can't use updateMapEntry because Schema is
	     * not in Maps.xml
	     */
	    $query = sprintf('
	    UPDATE %sSchema
	    SET %smajor=1, %sminor=3
	    WHERE %sname=\'AccessMap\' AND %smajor=1 AND %sminor=2',
			     $storage->_tablePrefix, $storage->_columnPrefix,
			     $storage->_columnPrefix, $storage->_columnPrefix,
			     $storage->_columnPrefix, $storage->_columnPrefix);
	    $ret = $storage->execute($query);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.23':
	    /* Rename GalleryCache to GalleryCacheMap, and make the value column TEXT(LARGE) */

	case '1.0.24':
	    /* Add CoreCaptchaAdminOption, rename level parameter */
	    $gallery->guaranteeTimeLimit(60);
	    list ($ret, $level) = $module->getParameter('captcha.level');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $ret = $module->setParameter('validation.level', $level);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $ret = $module->removeParameter('captcha.level');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.25':
	case '1.0.26':
	    /* 2.1 Release Candidate 1! */

	    /* Change character set encoding to utf 8 for MySQL if necessary */
	    if ($storage->getType() == 'mysql') {
		$version = mysql_get_server_info();
		/* MySQL < 4.1.0 does not support UTF8 */
		if ($version && version_compare($version, '4.1.0', '>=')) {
		    /* Check if the database uses UTF8 already */
		    list ($ret, $results) =
			$storage->search('SHOW CREATE DATABASE `' . $storage->_database . '`');
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $row = $results->nextResult();
		    $result = $row[1];
		    if (!$result || !preg_match('/utf8/i', $result)) {
			/* Convert all existing tables to UTF8 */
			$ret = $statusMonitor->renderStatusMessage(
				$module->translate('Converting MySQL data to UTF8'), null, 0);
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			$gallery->guaranteeTimeLimit(120);
			$storageExtras =& $storage->_getExtras();
			list ($ret, $tableVersions) = $storageExtras->_loadTableVersions();
			if ($ret) {
			    return $ret->wrap(__FILE__, __LINE__);
			}
			$types = array('varchar'  => 'varbinary',
				       'text'     => 'blob',
				       'longtext' => 'longblob');
			$i = 0;
			foreach ($tableVersions as $tableName => $unused) {
			    $i++;
			    $tableName = $storage->_tablePrefix . $tableName;
			    /* First the table itself */
			    $query = "ALTER TABLE `$tableName` DEFAULT CHARACTER SET utf8";
			    $ret = $storage->execute($query);
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    /*
			     * Then all character / string columns
			     * See: http://dev.mysql.com/doc/refman/4.1/en/charset-conversion.html
			     *      and http://drupal.org/node/40515
			     * 1. Detect current column attributes
			     * 2. Convert text column to binary column
			     * 3. Convert them to character/text columns with UTF8 charset
			     */
			    /* 1. Detect current column attributes */
			    $query = "SHOW FULL COLUMNS FROM `$tableName`";
			    $originalFetchMode = $storage->_db->SetFetchMode(ADODB_FETCH_ASSOC);
			    list ($ret, $results) = $storage->search($query);
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $storage->_db->SetFetchMode($originalFetchMode);
			    $changeToBinary = $changeToUtf8 = array();
			    while ($column = $results->nextResult()) {
				list($type) = explode('(', $column['Type']);
				if (!isset($types[$type])) {
				    continue;
				}
				$change =
				    'CHANGE `' . $column['Field'] . '` `' . $column['Field'] . '` ';
				$binaryType = preg_replace('/'. $type .'/i', $types[$type],
							   $column['Type']);
				$attributes = ' ';
				if ($column['Default'] == 'NULL') {
				    $attributes .= 'DEFAULT NULL ';
				} else if (!empty($column['Default'])) {
				    $attributes .= 'DEFAULT ' . $column['Default'] . ' ';
				}
				$attributes .= $column['Null'] == 'YES' ? 'NULL' : 'NOT NULL';
				$changeToBinary[] = $change . $binaryType . $attributes;
				$changeToUtf8[] =
				    $change . $column['Type'] . ' CHARACTER SET utf8' . $attributes;
			    }
			    if (count($changeToBinary)) {
				$query =
				    "ALTER TABLE `$tableName` " . implode(', ', $changeToBinary);
				$ret = $storage->Execute($query);
				if ($ret) {
				    return $ret->wrap(__FILE__, __LINE__);
				}
				$query = "ALTER TABLE `$tableName` " . implode(', ', $changeToUtf8);
				$ret = $storage->Execute($query);
				if ($ret) {
				    return $ret->wrap(__FILE__, __LINE__);
				}
			    }

			    $ret = $statusMonitor->renderStatusMessage(
				$module->translate('Converting MySQL data to UTF8'),
				null, $i / count($tableVersions));
			    if ($ret) {
				return $ret->wrap(__FILE__, __LINE__);
			    }
			    $gallery->guaranteeTimeLimit(120);
			} /* end for each table */
		    } /* end if database character set not utf8 */
		} /* end if MySql version > 4.1.0 */
	    } /* end if MySQL */

	    /* Clear the cache data since we changed the blob encoding */
	    $gallery->guaranteeTimeLimit(60);
	    $ret = GalleryCoreApi::removeAllMapEntries('GalleryCacheMap');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.27':
	case '1.0.28':
	    /* Change in page cache key format */
	case '1.0.29':
	    /* Support for transactional locking */

	case '1.0.30':
	    /* Pull dangerous mime types */
	    $ret = GalleryCoreApi::removeMimeType(
		array('mimeType' => array('text/html', 'application/xhtml+xml', 'text/xml')));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '1.0.31':
	    list ($ret, $params) = GalleryCoreApi::fetchAllPluginParameters('module', 'core');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    foreach (array('session.lifetime' => array(25 * 365 * 86400, 21 * 86400),
			   'session.inactivityTimeout' => array(14 * 86400, 7 * 86400)) as
		     $key => $oldAndNew) {
		if ($params[$key] == $oldAndNew[0]) {
		    $ret = $module->setParameter($key, $oldAndNew[1]);
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}
	    }

	case '1.0.32':
	    /* 2.1 Release Candidate 2! */
	case '1.0.33':
	    /* Security fix in installer/upgrader - RC-2a */
	case '1.0.34':
	    /* 2.1 Release! */
	case '1.1.0':
	    /* 2.1.1 Bugfix Release */

	case 'end of upgrade path':
	    /*
	     * Leave this bogus case at the end of the legitimate case statements so that we
	     * always properly terminate our upgrade path with a break.
	     */
	    break;

	default:
	    $gallery->debug('Error: Unknown module version');
	    return GalleryCoreApi::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
					sprintf('Unknown module version %s', $currentVersion));
	}

	$gallery->debug('Write new version to versions file');
	$versionFile = $gallery->getConfig('data.gallery.base') . 'versions.dat';
	$versionDatError =  0;
	if ($fd = $platform->fopen($versionFile, 'wb')) {
	    $data = sprintf("%s\n%s",
			    $module->getVersion(),
			    $module->getGalleryVersion());
	    if ($platform->fwrite($fd, $data) != strlen($data)) {
		$versionDatError = 1;
	    }
	    $platform->fclose($fd);
	} else {
	    $versionDatError = 1;
	}

	if ($versionDatError) {
	    $gallery->debug('Error: Can\'t write to versions file');
	    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
					'Can\'t write to the versions file');
	}

	return null;
    }

    /**
     * Determine what changes to config.php are required for this upgrade.
     * @param string current core version
     * @return array of array('remove' => array of string regexp removals,
     *                        'add' => array of string additions)
     * @access private
     * @static
     */
    function _prepareConfigUpgrade($currentVersion) {
	global $gallery;
	$configChanges = array();

	if (substr($currentVersion, 0, 6) == '1.0.0.') {
	    $currentVersion = '1.0.0.x';
	}

	/**
	 * README: How to update the block below.
	 *
	 * If you add a new feature to the core module and revise the version, you should do the
	 * following.  Supposing the current version is 1.0.1 and you're adding 1.0.2. Go to the
	 * end of the switch and find the 'end of upgrade path' case.  Create a new case *above*
	 * that one with the old version number.  For our example you'd add: "case '1.0.1':" and
	 * then your code.  Do *not* put in a break statement. (Update upgrade function too)
	 */
	switch ($currentVersion) {
	case '0.8.4':
	case '0.8.5':
	case '0.8.6':
	case '0.8.7':
	case '0.8.8':
	case '0.8.9':
	case '0.8.10':
	case '0.8.11':
	case '0.8.12':
	case '0.8.13':
	case '0.8.14':
	case '0.8.15':
	case '0.8.16':
	case '0.8.17':
	case '0.9.0':
	case '0.9.1':
	case '0.9.2':
	case '0.9.3':
	case '0.9.4':
	case '0.9.5':
	case '0.9.6':
	case '0.9.7':
	case '0.9.8':
	case '0.9.9':
	case '0.9.10':
	case '0.9.11':
	case '0.9.12':
	case '0.9.13':
	case '0.9.14':
	case '0.9.15':
	case '0.9.16':
	case '0.9.17':
	case '0.9.18':
	case '0.9.19':
	    $add = array();
	    if (!isset($gallery->_config['allowSessionAccess'])) {
		/*
		 * This item was added to config.php before config.php upgrades were supported.
		 * Add it only if not already present.
		 */
		$add[] =
'/*
 * Allow a particular IP address to access the session (it still must know the
 * session id) even though it doesn\'t match the address/user agent that created
 * the session.  Put the address of validator.w3.org (\'128.30.52.13\') here to allow
 * validation of non-public Gallery pages from the links at the bottom of the page.
 */
$gallery->setConfig(\'allowSessionAccess\', false);
';
	    }

	    $add[] =
'/*
 * URL of Gallery codebase; required only for multisite install.
 */
$gallery->setConfig(\'galleryBaseUrl\', \'\');
';

	    $configChanges[] = array(
		'remove' => array('{/\*[^/]*\*/\s*\$gallery->setConfig\(\'galleryId\',.*?;\s*}s'),
		'add' => $add, 'edit' => array());

	case '0.9.20':
	case '0.9.21':
	    $add = array();

	    /* Generate cookieId */
	    list($usec, $sec) = explode(" ", microtime());
	    /* Note: srand() is required for php versions < 4.2  */
	    srand(100000 * ((float)$usec + (float)$sec));
	    $cookieId = substr(md5(rand()), 0, 6);

	    $add[] =
'
/*
 * Set the name for G2 session cookies. The name of the session cookie is
 * a concatenation of \'GALLERYSID_\' and cookieId, which is randomly generated
 * at Gallery installation time. You can change cookieId at any time, but if
 * you change it be aware of two things:
 * 1. Users have to login again after the change. They lose their old session.
 * 2. If multiple G2 installs are running on the same domain (in different paths or
 *    different subdomains) choose cookieId such that it is different for all G2
 *    installs on the same domain.
 */
$gallery->setConfig(\'cookieId\', \'' . $cookieId . '\');
';
	    $configChanges[] = array('remove' => array(), 'add' => $add, 'edit' => array());

	case '0.9.22':
	case '0.9.23':
	    /* Session cookie change, revert the last change and try something new */
	    $configChanges[] = array(
		'remove' => array('{/\*[^/]*\*/\s*\$gallery->setConfig\(\'cookieId\',.*?;\s*}s'),
		'add' => array(), 'edit' => array());

	case '0.9.24':
	case '0.9.25':
	case '0.9.26':
	case '0.9.27':
	case '0.9.28':
	case '0.9.29':
	case '0.9.30':
	case '0.9.31':
	case '0.9.32':
	case '0.9.33':
	case '0.9.34':
	case '0.9.35':
	case '0.9.36':
	case '0.9.37':
	case '1.0.0':
	case '1.0.0.x':
	case '1.0.1':
	case '1.0.2':
	case '1.0.3':

	case '1.0.4':
	    $configChanges[] = array('remove' => array(), 'edit' => array(), 'add' => array(
'
/*
 * Maintenance mode.  You can disable access to the site for anyone but
 * site administrators by setting this this flag.  Set value below to:
 *  true (without quotes) - to use a basic notification page; themed
 *    view with admin login link when codebase is up to date, but a
 *    plain unstyled page when codebase has been updated but upgrader
 *    has not yet been run.
 *  url (with quotes) - provide a url where requests are redirected in
 *    either case described above.  Example: \'/maintenance.html\'
 */
$gallery->setConfig(\'mode.maintenance\', false);
'));

	case '1.0.5':
	case '1.0.6':
	case '1.0.7':
	case '1.0.8':
	case '1.0.9':
	case '1.0.10':
	case '1.0.11':
	case '1.0.12':
	case '1.0.13':
	    /* Add config parameter: 'baseUri'*/
	    $add[] =
'
/*
 * This setting can be used to override Gallery\'s auto-detection of the domain-name,
 * protocol (http/https), URL path, and of the file & query string.
 * Most users can leave this empty. If the server is misconfigured or for very special
 * setups, this setting can be quite handy.
 * Examples (the positions of the slashes (\'/\') are important):
 *   override the path: $gallery->setConfig(\'baseUri\', \'/another/path/\');
 *   override the host + path: $gallery->setConfig(\'baseUri\', \'example.com/gallery2/\');
 *   override the protocol + host + path + file:
 *           $gallery->setConfig(\'baseUri\', \'https://example.com:8080/gallery2/index.php\');
 */
$gallery->setConfig(\'baseUri\', \'\');';

	    $configChanges[] = array('remove' => array(), 'add' => $add, 'edit' => array());

	case '1.0.14':
	case '1.0.15':
	    /*
	     * Normalize the config path 'data.gallery.base' (add a trailing slash if necessary)
	     * Escape the backslashes and quotes two times since we feed preg_replace with it
	     */
	    $tmp = strtr($gallery->getConfig('data.gallery.base'),
			 array('\\' => '\\\\\\\\', "'" => "\\\\'"));
	    $edit['regexp'] = '{\$gallery->setConfig\(\'data\.gallery\.base\',.*?;}s';
	    $edit['replacement'] = '$gallery->setConfig(\'data.gallery.base\', \'' . $tmp . '\');';
	    $configChanges[] = array('remove' => array(), 'add' => array(), 'edit' => array($edit));
	case '1.0.16':
	case '1.0.17':
	case '1.0.18':
	case '1.0.19':
	case '1.0.20':
	case '1.0.21':
	case '1.0.22':
	case '1.0.23':
	case '1.0.24':
	case '1.0.25':
	case '1.0.26':
	case '1.0.27':
	case '1.0.28':
	case '1.0.29':
	case '1.0.30':
	case '1.0.31':
	case '1.0.32':
	case '1.0.33':
	case '1.0.34':
	case '1.1.0':

	case 'end of upgrade path':
	    /*
	     * Leave this bogus case at the end of the legitimate case statements so that we
	     * always properly terminate our upgrade path with a break.
	     */
	    break;

	default:
	    $gallery->debug("Unknown module version $currentVersion in prepareConfigUpgrade()");
	}

	return $configChanges;
    }

    /**
     * Check if any changes to config.php are required for this upgrade.
     * @param string current core version
     * @return boolean true if change is required
     * @static
     */
    function isConfigUpgradeRequired($currentVersion) {
	$configChanges = CoreModuleExtras::_prepareConfigUpgrade($currentVersion);
	return !empty($configChanges);
    }

    /**
     * Perform upgrade of config.php file.
     * @param string current core version
     * @return object GalleryStatus a status code
     * @static
     */
    function performConfigUpgrade($currentVersion) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	$configFilePath = GALLERY_CONFIG_DIR . '/config.php';
	$configContents = implode('', $platform->file($configFilePath));
	if (empty($configContents) || strlen($configContents) < 100) {
	    return GalleryCoreApi::error(ERROR_MISSING_VALUE, __FILE__, __LINE__,
					'Unable to read current config.php contents');
	}

	$configChanges = CoreModuleExtras::_prepareConfigUpgrade($currentVersion);
	foreach ($configChanges as $change) {
	    foreach ($change['remove'] as $regexp) {
		$configContents = preg_replace($regexp, '', $configContents);
	    }
	    foreach ($change['edit'] as $edit) {
		$configContents =
		    preg_replace($edit['regexp'], $edit['replacement'], $configContents);
	    }
	    foreach ($change['add'] as $content) {
		$configContents = preg_replace('{\?>\s*\z}', $content . "\n?>\n", $configContents);
	    }
	}

	if (!$out = $platform->fopen($configFilePath, 'w')) {
	    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
					'Unable to write to config.php');
	}
	if ($platform->fwrite($out, $configContents) < strlen($configContents)) {
	    return GalleryCoreApi::error(ERROR_PLATFORM_FAILURE, __FILE__, __LINE__,
					'Unable to write config.php contents');
	}
	$platform->fclose($out);

	return null;
    }

    /**
     * Create the initial All Users group
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createAllUsersGroup($module) {
	global $gallery;

	list ($ret, $id) = $module->getParameter('id.allUserGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (!empty($id)) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryGroup.class');
	$group = new GalleryGroup();

	$groupName = $module->translate('Registered Users');
	$ret = $group->create($groupName, GROUP_ALL_USERS);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $group->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $module->setParameter('id.allUserGroup', $group->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * Create the Site Admins group
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createSiteAdminsGroup($module) {
	global $gallery;

	list ($ret, $id) = $module->getParameter('id.adminGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (!empty($id)) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryGroup.class');
	$group = new GalleryGroup();

	$groupName = $module->translate('Site Admins');
	$ret = $group->create($groupName, GROUP_SITE_ADMINS);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $group->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $module->setParameter('id.adminGroup', $group->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * Create the Site Admins group
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createEverybodyGroup($module) {
	global $gallery;

	list ($ret, $id) = $module->getParameter('id.everybodyGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (!empty($id)) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryGroup.class');
	$group = new GalleryGroup();

	$groupName = $module->translate('Everybody');
	$ret = $group->create($groupName, GROUP_EVERYBODY);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $group->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $module->setParameter('id.everybodyGroup', $group->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * Create the initial Anonymous User
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createAnonymousUser($module) {
	global $gallery;

	list ($ret, $id) = $module->getParameter('id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (!empty($id)) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryUser.class');
	$user = new GalleryUser();

	$userName = 'guest';
	$fullName = $module->translate('Guest');
	$ret = $user->create($userName);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$user->setFullName($fullName);
	$user->changePassword('');

	$ret = $user->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Remove the anonymous user from the "all users" group */
	list ($ret, $allUserGroupId) = $module->getParameter('id.allUserGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	GalleryCoreApi::removeUserFromGroup($user->getId(), $allUserGroupId);

	$ret = $module->setParameter('id.anonymousUser', $user->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * Create the initial admin user
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createAdminUser($module) {
	global $gallery;

	/* Don't create if there is already a user in the admin group */
	list ($ret, $adminGroupId) = $module->getParameter('id.adminGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $results) = GalleryCoreApi::fetchUsersForGroup($adminGroupId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (sizeof($results) > 0) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryUser.class');
	$user = new GalleryUser();

	/*
	 * Get the admin name and data from the installer and default to 'admin' if it's
	 * not available for some reason
	 */
	$userName = $gallery->getConfig('setup.admin.userName');
	$userName = !strlen($userName) ? 'admin' : $userName;
	$email = $gallery->getConfig('setup.admin.email');
	$fullName = $gallery->getConfig('setup.admin.fullName');
	$ret = $user->create($userName);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$user->changePassword($gallery->getConfig('setup.password'));
	$user->setFullName($fullName);
	$user->setEmail($email);

	$ret = $user->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Add her to the admin group */
	$ret = GalleryCoreApi::addUserToGroup($user->getId(), $adminGroupId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/*
	 * The rest of the bootstrap code won't work so well unless we're
	 * logged in, so log in as the admin user now.
	 */
	$gallery->setActiveUser($user);

	return null;
    }

    /**
     * Create the root album item
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createRootAlbumItem($module) {
	global $gallery;

	/* Do we already have a root? */
	list ($ret, $rootAlbumId) = $module->getParameter('id.rootAlbum');
	if ($rootAlbumId) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryAlbumItem.class');
	$album = new GalleryAlbumItem();

	$ret = $album->createRoot();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$title = $module->translate('Gallery');
	$description = $module->translate('This is the main page of your Gallery');
	$album->setTitle($title);
	$album->setDescription($description);

	$ret = $album->save();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Give everybody some permissions */
	list ($ret, $groupId) = $module->getParameter('id.everybodyGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::addGroupPermission($album->getId(), $groupId, 'core.viewAll');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Grant admin users everything */
	list ($ret, $groupId) = $module->getParameter('id.adminGroup');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::addGroupPermission($album->getId(), $groupId, 'core.all');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $module->setParameter('id.rootAlbum', $album->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * Create the access list compactor lock entity
     *
     * @param object GalleryModule the core module
     * @return object GalleryStatus a status code
     * @static
     */
    function _createAccessListCompacterLock($module) {
	global $gallery;

	/* Do we already have a root? */
	list ($ret, $compacterLockId) = $module->getParameter('id.accessListCompacterLock');
	if ($compacterLockId) {
	    return null;
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryEntity.class');
	$lock = new GalleryEntity();
	$lock->create();
	$ret = $lock->save(false);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = $module->setParameter('id.accessListCompacterLock', $lock->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations
     */
    function performFactoryRegistrations($module) {
	/* Register all of our factory implementations. */
	$regs[] = array('GalleryEntity', 'GalleryEntity', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryChildEntity', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryAlbumItem', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryUser', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryGroup', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryDerivative', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryDerivativeImage', 'class', null);
	$regs[] = array('GalleryDerivative', 'GalleryDerivativeImage', 'class', array('*'));
	$regs[] = array('GalleryEntity', 'GalleryMovieItem', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryAnimationItem', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryPhotoItem', 'class', null);
	$regs[] = array('GalleryEntity', 'GalleryUnknownItem', 'class', null);
	$regs[] = array('GalleryItem', 'GalleryPhotoItem', 'class', array('image/*'));
	$regs[] = array('GalleryItem', 'GalleryMovieItem', 'class',
			array('video/x-msvideo', 'video/quicktime', 'video/mpeg',
			      'video/x-ms-asf', 'video/x-ms-wmv'));
	$regs[] = array('GalleryItem', 'GalleryAnimationItem', 'class',
			array('application/x-director', 'application/x-shockwave-flash'));
	$regs[] = array('GalleryItem', 'GalleryUnknownItem', 'class', array('*'));
	$regs[] = array('GallerySearchInterface_1_0', 'GalleryCoreSearch', 'class', null);
	$regs[] = array('ItemEditPlugin', 'ItemEditItem', 'inc', null, 1);
	$regs[] = array('ItemEditPlugin', 'ItemEditAnimation', 'inc', null, 2);
	$regs[] = array('ItemEditPlugin', 'ItemEditMovie', 'inc', null, 2);
	$regs[] = array('ItemEditPlugin', 'ItemEditAlbum', 'inc', null, 2);
	$regs[] = array('ItemEditPlugin', 'ItemEditTheme', 'inc', null, 3);
	$regs[] = array('ItemEditPlugin', 'ItemEditPhoto', 'inc', null, 2);
	$regs[] = array('ItemEditPlugin', 'ItemEditRotateAndScalePhoto', 'inc', null, 3);
	$regs[] = array('ItemEditPlugin', 'ItemEditPhotoThumbnail', 'inc', null, 4);
	$regs[] = array('ItemAddPlugin', 'ItemAddFromBrowser', 'inc', null, 2);
	$regs[] = array('ItemAddPlugin', 'ItemAddFromServer', 'inc', null, 3);
	$regs[] = array('ItemAddPlugin', 'ItemAddFromWeb', 'inc', null, 4);
	$regs[] = array('ItemAddOption', 'CreateThumbnailOption', 'inc', null);
	$regs[] = array('MaintenanceTask', 'OptimizeDatabaseTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'FlushTemplatesTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'FlushDatabaseCacheTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'BuildDerivativesTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'ResetViewCountsTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'SystemInfoTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'SetOriginationTimestampTask', 'class', null);
	$regs[] = array('MaintenanceTask', 'DeleteSessionsTask', 'class', null);
	$regs[] = array('CaptchaAdminOption', 'CoreCaptchaAdminOption', 'class', null);

	/*
	 * Unlike other modules, the core module doesn't get deactivated so its
	 * factory registrations may still be around from before.  Unregister
	 * them now before reregistering them all.
	 */
	/* Unregister all factory implementations */
	$ret = GalleryCoreApi::unregisterFactoryImplementationsByModuleId($module->getId());
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	foreach ($regs as $entry) {
	    $ret = GalleryCoreApi::registerFactoryImplementation(
		$entry[0], $entry[1], $entry[1],
		$entry[2] == 'class' ?
		  sprintf('modules/core/classes/%s.class', $entry[1]) :
		  sprintf('modules/core/%s.inc', $entry[1]),
		'core', $entry[3], isset($entry[4]) ? (string)$entry[4] : '4');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}

	return null;
    }
}
?>
