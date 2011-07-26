<?php
/*
 * $RCSfile: NewItemsSiteAdmin.inc,v $
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
 * @version $Revision: 1.10 $ $Date: 2006/01/17 01:52:02 $
 * @package NewItems
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package NewItems
 * @subpackage UserInterface
 */
class NewItemsSiteAdminController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = array();
	if (isset($form['action']['save'])) {
	    if (!isset($form['days']['new']) || !is_numeric($form['days']['new'])) {
		$error[] = 'form[error][new]';
	    }
	    if (!isset($form['days']['updated']) || !is_numeric($form['days']['updated'])) {
		$error[] = 'form[error][updated]';
	    }

	    if (empty($error)) {
		foreach (array('days.new', 'days.updated') as $key) {
		    $keys = explode('.', $key);
		    $ret = GalleryCoreApi::setPluginParameter(
			'module', 'newitems', $key, $form[$keys[0]][$keys[1]]);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
		$status['saved'] = 1;
	    }
	} else if(isset($form['action']['reset'])) {
	    list ($ret, $default) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'default.orderBy');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (strpos($default, 'NewItems') !== false) {
		$ret = GalleryCoreApi::setPluginParameter(
				     'module', 'core', 'default.orderBy', 'orderWeight');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	    GalleryCoreApi::requireOnce('modules/newitems/classes/NewItemsHelper.class');
	    $ret = NewItemsHelper::resetNewItemSorts();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $status['reset'] = 1;
	} /* else $form['action']['reset'] */

	/* Figure out where to redirect upon success */
	$method = empty($error) ? 'redirect' : 'delegate';
	$results[$method]['view'] = 'core.SiteAdmin';
	$results[$method]['subView'] = 'newitems.NewItemsSiteAdmin';
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package NewItems
 * @subpackage UserInterface
 */
class NewItemsSiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	GalleryCoreApi::requireOnce('modules/newitems/classes/NewItemsHelper.class');

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'NewItemsSiteAdmin') {
	    $form['formName'] = 'NewItemsSiteAdmin';
	    foreach (array('days.new', 'days.updated') as $key) {
		list ($ret, $value) =
		    GalleryCoreApi::getPluginParameter('module', 'newitems', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$key = explode('.', $key);
		$form[$key[0]][$key[1]] = $value;
	    }
	}
	list ($ret, $form['count']) = NewItemsHelper::fetchAlbumCount();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $default) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'default.orderBy');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$form['isDefault'] = strpos($default, 'NewItems') !== false;

	$template->setVariable('controller', 'newitems.NewItemsSiteAdmin');
	return array(null,
		     array('body' => 'modules/newitems/templates/NewItemsSiteAdmin.tpl'));
    }
}
?>
