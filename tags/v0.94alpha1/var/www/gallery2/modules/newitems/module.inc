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
 * @version $Revision: 1.32 $ $Date: 2006/03/22 03:52:33 $
 * @package NewItems
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * Add new/updated image in summary info for new/updated items.
 * New sort option to put new items in front, follwed by another sort.
 * Thanks to Felix Rabinovich (virshu) for the idea.
 *
 * @package NewItems
 */
class NewItemsModule extends GalleryModule {

    function NewItemsModule() {
	global $gallery;

	$this->setId('newitems');
	$this->setName($gallery->i18n('New Items'));
	$this->setDescription($gallery->i18n('Highlight new/updated Gallery items'));
	$this->setVersion('1.0.3');
	$this->setGroup('display', $gallery->i18n('Display'));
	$this->setCallbacks('getSiteAdminViews|getItemSummaries');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	if (!isset($currentVersion)) {
	    foreach (array('days.new' => '7',
			   'days.updated' => '7' )
		     as $key => $value) {
		$ret = $this->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	return null;
    }

    /**
     * @see GalleryModule::performFactoryRegistrations()
     */
    function performFactoryRegistrations() {
	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'GallerySortInterface_1_1', 'NewItemsSortOrder', 'NewItems',
	    'modules/newitems/classes/NewItemsSortOrder.class', 'newitems', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::deactivate()
     */
    function deactivate($postDeactivationEvent=true) {
	GalleryCoreApi::requireOnce('modules/newitems/classes/NewItemsHelper.class');
	list ($ret, $count) = NewItemsHelper::fetchAlbumCount();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $default) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'default.orderBy');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($count > 0 || strpos($default, 'NewItems') !== false) {
	    /* Can't deactivate if NewItems sort is in use */
	    return array(null,
			 array('view' => 'core.SiteAdmin',
			       'subView' => 'newitems.NewItemsSiteAdmin'));
	}

	list ($ret, $redirect) = parent::deactivate($postDeactivationEvent);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null, $redirect);
    }

    /**
     * @see GalleryModule::getSiteAdminViews()
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('New Items'),
				 'view' => 'newitems.NewItemsSiteAdmin')));
    }

    /**
     * @see GalleryModule::getItemSummaries()
     */
    function getItemSummaries($items, $permissions) {
	list ($ret, $param) = GalleryCoreApi::fetchAllPluginParameters('module', 'newitems');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$time = time();
	$newTime = $time - $param['days.new'] * 86400;
	$updatedTime = $time - $param['days.updated'] * 86400;

	$newString = $this->translate('New');
	$updatedString = $this->translate('Updated');

	$summaries = array();
	foreach ($items as $item) {
	    if ($param['days.new'] > 0 && $item->getCreationTimestamp() > $newTime) {
		$summaries[$item->getId()] = '<span class="giNew">'
		    . $newString . '</span>';
	    } else if ($param['days.updated'] > 0
			&& $item->getModificationTimestamp() > $updatedTime) {
		$summaries[$item->getId()] = '<span class="giUpdated">'
		    . $updatedString . '</span>';
	    }
	}
	return array(null, $summaries);
    }
}
?>
