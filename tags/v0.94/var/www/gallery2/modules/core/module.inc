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
 * @version $Revision: 1.263.2.1 $ $Date: 2006/04/05 22:19:54 $
 * @package GalleryCore
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * The implementation of the core-module
 *
 * @package GalleryCore
 */
class CoreModule extends GalleryModule {

    function CoreModule() {
	global $gallery;

	$this->setId('core');
	$this->setName($gallery->i18n('Core'));
	$this->setGalleryVersion('2.1.1');

	/* Don't forget to update CoreModuleExtras::upgrade and _prepareConfigUpgrade too! */
	$this->setVersion('1.1.0.1');

	$this->setDescription($gallery->i18n('Gallery 2 Core Module'));
	$this->setGroup('gallery', $gallery->i18n('Gallery'));
	$this->setCallbacks('registerEventListeners|' .
			    'getItemLinks|getSystemLinks|' .
			    'getSiteAdminViews|getUserAdminViews|getItemAdminViews');
	$this->setRequiredCoreApi(array(7, 2));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::registerEventListeners();
     */
    function registerEventListeners() {
	GalleryCoreApi::requireOnce('modules/core/classes/helpers/GalleryItemHelper_medium.class');
	$handler = new GalleryItemHelper_medium();
	GalleryCoreApi::registerEventListener('Gallery::ViewableTreeChange', $handler);
	GalleryCoreApi::registerEventListener('Gallery::RemovePermission', $handler);
	GalleryCoreApi::registerEventListener('GalleryEntity::save', $handler);
	GalleryCoreApi::registerEventListener('GalleryEntity::delete', $handler);
    }

    /**
     * @see GalleryModule::getItemLinks
     */
    function getItemLinks($items, $wantsDetailedLinks, $permissions) {
	list ($ret, $rootId) = GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Fetch child counts */
	$itemIds = array();
	foreach ($items as $item) {
	    $itemIds[] = $item->getId();
	}

	/*
	 * We're not doing this as the acting user id, but that's ok because we're not displaying
	 * the result; we're only using it as a gating factor for whether or not we show a link,
	 * and that'll also be gated by the permissions we receive which *will* be for the acting
	 * user.
	 */
	list ($ret, $childCounts) = GalleryCoreApi::fetchChildCounts($itemIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Fetch thumbnail ids */
	list ($ret, $thumbTable) = GalleryCoreApi::fetchThumbnailsByItemIds($itemIds);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$links = array();
	foreach ($items as $item) {
	    $itemId = $item->getId();
	    $isRoot = ($itemId == $rootId);
	    $isAlbum = $item->getCanContainChildren();

	    /* Permissions for its parent */
	    list ($ret, $parentPermissions) = GalleryCoreApi::getPermissions($item->getParentId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (isset($wantsDetailedLinks[$itemId]) && $isAlbum &&
		    isset($permissions[$itemId]['core.addDataItem'])) {
		$links[$itemId][] =
		    array('text' => $this->_translate('Add Items'),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemAdd',
					    'itemId' => $itemId,
					    'return' => 1));
	    }

	    $itemTypeNames = array_merge($item->itemTypeName(), $item->itemTypeName(false));
	    if (isset($permissions[$itemId]['core.edit'])) {
		/* Specific translations: _('Edit Album') _('Edit Photo') _('Edit Movie') */
		$links[$itemId][] =
		    array('text' => $this->_translate(
			      array('text' => 'Edit %s', 'arg1' => $itemTypeNames[0]),
			      $itemTypeNames[2]),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemEdit',
					    'itemId' => $itemId,
					    'return' => 1));
	    }

	    if (isset($wantsDetailedLinks[$itemId]) &&
		    isset($permissions[$itemId]['core.viewSource']) &&
		    GalleryCapabilities::can('link')) {
		if ($item->getIsLinkable()) {
		    if ($isAlbum) {
			list ($ret, $count) = GalleryCoreApi::fetchItemIdCount(
			    'GalleryAlbumItem', 'core.addAlbumItem');
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    } else {
			list ($ret, $count) = GalleryCoreApi::fetchItemIdCount(
			    'GalleryAlbumItem', 'core.addDataItem');
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    }

		    if ($count > 0) {
			$links[$itemId][] =
			    array('text' => $this->_translate('Create Link'),
				  'params' => array('view' => 'core.ItemAdmin',
						    'subView' => 'core.ItemCreateLink',
						    'itemId' => $item->getParentId(),
						    'selectedId' => $itemId,
						    'return' => 1));
		    }
		}
	    }

	    if (isset($wantsDetailedLinks[$itemId]) &&
		    $isAlbum && isset($permissions[$itemId]['core.addAlbumItem'])) {
		$links[$itemId][] =
		    array('text' => $this->_translate('Add Album'),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemAddAlbum',
					    'itemId' => $itemId,
					    'return' => 1));
	    }

	    if (isset($permissions[$itemId]['core.edit'])) {
		$links[$itemId][] =
		    array('text' => isset($permissions[$itemId]['core.changePermissions'])
				? $this->_translate('Edit Permissions')
				: $this->_translate('View Permissions'),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemPermissions',
					    'itemId' => $itemId,
					    'return' => 1));
	    }

	    if (!$isRoot && isset($permissions[$itemId]['core.delete'])) {
		/* Specific translations: _('Delete Album') _('Delete Photo') _('Delete Movie') */
		$links[$itemId][] =
		    array('text' => $this->_translate(
			      array('text' => 'Delete %s', 'arg1' => $itemTypeNames[0]),
			      $itemTypeNames[2]),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemDelete',
					    'itemId' => $item->getParentId(),
					    'selectedId' => $itemId,
					    'return' => 1));
	    }

	    if (!$isRoot && isset($permissions[$itemId]['core.delete'])) {
		/* Specific translations: _('Move Album') _('Move Photo') _('Move Movie') */
		$links[$itemId][] =
		    array('text' => $this->_translate(
			      array('text' => 'Move %s', 'arg1' => $itemTypeNames[0]),
			      $itemTypeNames[2]),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemMove',
					    'itemId' => $item->getParentId(),
					    'selectedId' => $itemId,
					    'return' => 1));
	    }

	    if (isset($wantsDetailedLinks[$itemId]) &&
		    $isAlbum &&
		    isset($permissions[$itemId]['core.edit']) &&
		    !empty($childCounts[$itemId]) &&
		    $childCounts[$itemId] > 0) {
		$link = array('text' => $this->_translate('Edit Captions'),
			      'params' => array('view' => 'core.ItemAdmin',
						'subView' => 'core.ItemEditCaptions',
						'itemId' => $itemId,
						'return' => 1));
		list ($thisItemId, $thisPage) =
		    GalleryUtilities::getRequestVariables('itemId', 'page');
		if (!empty($thisItemId) && !empty($thisPage) && $thisItemId == $item->getId()) {
		    $link['params']['albumPage'] = $thisPage;
		}
		$links[$itemId][] = $link;
	    }

	    if (!$isRoot && isset($thumbTable[$itemId]) &&
		    isset($parentPermissions['core.edit'])) {
		$links[$itemId][] =
		    array('text' => $this->_translate('Make Highlight'),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemMakeHighlight',
					    'itemId' => $itemId,
					    'return' => 1));
	    }

	    if ($isAlbum && isset($permissions[$itemId]['core.edit']) &&
		    !empty($childCounts[$itemId]) &&
		    $childCounts[$itemId] > 1) {
		$links[$itemId][] =
		    array('text' => $this->_translate('Reorder Items'),
			  'params' => array('view' => 'core.ItemAdmin',
					    'subView' => 'core.ItemReorder',
					    'itemId' => $itemId,
					    'return' => 1));
	    }
	}

	return array(null, $links);
    }

    /**
     * @see GalleryModule::getSystemLinks
     */
    function getSystemLinks() {
	global $gallery;

	list ($ret, $param) = GalleryCoreApi::fetchAllPluginParameters('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$links = array();
	list ($ret, $isAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($isAdmin) {
	    $links['SiteAdmin'] = array('text' => $this->translate('Site Admin'),
					'params' => array('view' => 'core.SiteAdmin',
							  'return' => 1));
	}

	if (GalleryCapabilities::can('login')) {
	    if ($gallery->getActiveUserId() == $param['id.anonymousUser']) {
		$links['Login'] = array('text' => $this->translate('Login'),
					'params' => array('view' => 'core.UserAdmin',
							  'subView' => 'core.UserLogin',
							  'return' => 1));
	    } else {
		$links['YourAccount'] = array('text' => $this->translate('Your Account'),
					      'params' => array('view' => 'core.UserAdmin',
								'subView' => 'core.UserPreferences',
								'return' => 1));
		$links['Logout'] = array('text' => $this->translate('Logout'),
					 'params' => array('controller' => 'core.Logout',
							   'return' => 1));
	    }
	}

	return array(null, $links);
    }

    /**
     * @see GalleryModule::getSiteAdminViews();
     */
    function getSiteAdminViews() {
	$data = array(array('name' => $this->translate('General'),
			    'view' => 'core.AdminCore'),
		      array('name' => $this->translate('Modules'),
			    'view' => 'core.AdminModules'),
		      /* TODO: Add back Repository link when downloadable plugins is ready:
		      array('name' => $this->translate('Repository'),
			    'view' => 'core.AdminRepository'), */
		      array('name' => $this->translate('Themes'),
			    'view' => 'core.AdminThemes',
			    'group' => 'display',
			    'groupLabel' => $this->translate('Display')),
		      array('name' => $this->translate('Users'),
			    'view' => 'core.AdminUsers'),
		      array('name' => $this->translate('Groups'),
			    'view' => 'core.AdminGroups'),
		      array('name' => $this->translate('Maintenance'),
			    'view' => 'core.AdminMaintenance'),
		      array('name' => $this->translate('Performance'),
			    'view' => 'core.AdminPerformance'),
		      );
	list ($ret, $list) = GalleryCoreApi::getRedundantToolkitPriorities();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!empty($list)) {
	    $data[] = array('name' => $this->translate('Toolkit Priority'),
			    'view' => 'core.AdminToolkitPriority',
			    'group' => 'toolkits',
			    'groupLabel' => $this->translate('Graphics Toolkits'));
	}
	return array(null, $data);
    }

    /**
     * @see GalleryModule::getUserAdminViews();
     */
    function getUserAdminViews($user) {
	$views = array();

	if (GalleryCapabilities::can('login')) {
	    list ($ret, $anonymousUserId) = $this->getParameter('id.anonymousUser');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($user->getId() != $anonymousUserId) {
		$views[] = array('name' => $this->translate('Account Settings'),
				 'view' => 'core.UserPreferences');
		$views[] = array('name' => $this->translate('Change Password'),
				 'view' => 'core.UserChangePassword');
	    } else {
		$views[] = array('name' => $this->translate('Login'),
				 'view' => 'core.UserLogin');
	    }
	}

	return array(null, $views);
    }

    /**
     * @see GalleryModule::getItemAdminViews();
     */
    function getItemAdminViews($item) {
	$views = array();
	list ($ret, $permissions) = GalleryCoreApi::getPermissions($item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$isAlbum = $item->getCanContainChildren();
	if ($isAlbum) {
	    list ($ret, $childCounts) = GalleryCoreApi::fetchChildCounts(array($item->getId()));
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $childCount = empty($childCounts[$item->getId()]) ? 0 : $childCounts[$item->getId()];
	} else {
	    $childCount = 0;
	}

	/* Fetch thumbnail ids */
	list ($ret, $thumbTable) =
	    GalleryCoreApi::fetchThumbnailsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$hasThumb = !empty($thumbTable[$item->getId()]);

	$parentId = $item->getParentId();
	list ($ret, $parentPermissions) = GalleryCoreApi::getPermissions($parentId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $rootId) = GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$itemTypeNames = array_merge($item->itemTypeName(), $item->itemTypeName(false));
	if (isset($permissions['core.edit'])) {
	    if ($isAlbum && $childCount > 1) {
		$views[] = array('name' => $this->_translate('Reorder Items'),
				 'view' => 'core.ItemReorder');
	    }
	    if ($isAlbum && $childCount > 0) {
		$views[] = array('name' => $this->_translate('Edit Captions'),
				 'view' => 'core.ItemEditCaptions');
	    }

	    /* Edit view for all item types */
	    $views[] = array('name' => $this->_translate(
				  array('text' => 'Edit %s', 'arg1' => $itemTypeNames[0]),
				  $itemTypeNames[2]),
			     'view' => 'core.ItemEdit');
	    $views[] = array('name' => isset($permissions['core.changePermissions'])
				 ? $this->_translate('Edit Permissions')
				 : $this->_translate('View Permissions'),
			     'view' => 'core.ItemPermissions');
	}

	if (!empty($parentId) && $hasThumb && isset($parentPermissions['core.edit'])) {
	    $views[] = array('name' => $this->_translate('Make Highlight'),
			     'view' => 'core.ItemMakeHighlight');
	}

	if (isset($permissions['core.viewSource']) && GalleryCapabilities::can('link')) {
	    if ($item->getIsLinkable()) {
		if ($isAlbum) {
		    list ($ret, $count) =
			GalleryCoreApi::fetchItemIdCount('GalleryAlbumItem', 'core.addAlbumItem');
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		} else {
		    list ($ret, $count) =
			GalleryCoreApi::fetchItemIdCount('GalleryAlbumItem', 'core.addDataItem');
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}

		if ($count > 0) {
		    /* Specific translations: _('Link Album') _('Link Photo') _('Link Movie') */
		    $views[] = array('name' => $this->_translate(
					array('text' => 'Link %s', 'arg1' => $itemTypeNames[0]),
					$itemTypeNames[2]),
				     'view' => 'core.ItemCreateLinkSingle');
		}
	    }
	}

	if (isset($permissions['core.delete'])) {
	    if ($item->getId() != $rootId) {
		$views[] = array('name' => $this->_translate(
				   array('text' => 'Delete %s', 'arg1' => $itemTypeNames[0]),
				   $itemTypeNames[2]),
				 'view' => 'core.ItemDeleteSingle');

		$views[] = array('name' => $this->_translate(
				   array('text' => 'Move %s', 'arg1' => $itemTypeNames[0]),
				   $itemTypeNames[2]),
				 'view' => 'core.ItemMoveSingle');
	    }
	}

	if (isset($permissions['core.addDataItem']) && $isAlbum) {
	    $views[] = array('name' => $this->_translate('Add Items'),
			     'view' => 'core.ItemAdd');
	}

	if (isset($permissions['core.addAlbumItem']) && $isAlbum) {
	    $views[] = array('name' => $this->_translate('Add Album'),
			     'view' => 'core.ItemAddAlbum');
	}

	return array(null, $views);
    }

    /**
     * @see GalleryModule::install
     *
     * We pushed all this code into its own file since we need it very rarely so it doesn't
     * make sense to load it every single time.
     */
    function upgrade($currentVersion, $statusMonitor) {
	GalleryCoreApi::requireOnce('modules/core/CoreModuleExtras.inc');
	$ret = CoreModuleExtras::upgrade($this, $currentVersion, $statusMonitor);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	return null;
    }

    /**
     * Set the current version of Gallery
     */
    function setGalleryVersion($version) {
	$this->_galleryVersion = $version;
    }

    /**
     * Get the current version of Gallery
     */
    function getGalleryVersion() {
	return $this->_galleryVersion;
    }

    /**
     * Get the version of the core module and of Gallery itself.  We store this
     * on disk to avoid having to load up the database (which can be problematic
     * if we're doing an upgrade and don't want to count a specific database
     * schema.
     *
     * @return array 'core' => core module version, 'gallery' => gallery version
     * @static
     */
    function getInstalledVersions() {
	global $gallery;
	static $versions;

	if (!isset($versions)) {
	    $platform =& $gallery->getPlatform();
	    $versionFile = $gallery->getConfig('data.gallery.base') . 'versions.dat';

	    $moduleVersion = null;
	    $galleryVersion = null;
	    if ($platform->file_exists($versionFile)) {
		$versionArray = $platform->file($versionFile);
		if (count($versionArray) >= 2) {
		    $versions['core'] = rtrim($versionArray[0]);
		    $versions['gallery'] = rtrim($versionArray[1]);
		}
	    }
	}

	return $versions;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	GalleryCoreApi::requireOnce('modules/core/CoreModuleExtras.inc');
	$ret = CoreModuleExtras::performFactoryRegistrations($this);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	return null;
    }
}
?>
