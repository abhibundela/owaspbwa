<?php
/*
 * $RCSfile: AdminToolkitPriority.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/01/17 01:52:01 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This controller adjusts relative toolkit priorities
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminToolkitPriorityController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = array();
	if (isset($form['action']['save'])) {
	    list ($ret, $list) = GalleryCoreApi::getRedundantToolkitPriorities();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    foreach ($form['priority'] as $priority => $toolkit) {
		if ($priority != $list[$toolkit]) {
		    $ret = GalleryCoreApi::updateMapEntry(
			'GalleryToolkitOperationMimeTypeMap',
			array('toolkitId' => $toolkit), array('priority' => $priority));
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
	    }
	    GalleryDataCache::removeByPattern('GalleryToolkitHelper::');
	    $status['saved'] = 1;
	} /* else $form['action']['reset'] */

	$results['return'] = 0;
	$results['redirect']['view'] = 'core.SiteAdmin';
	$results['redirect']['subView'] = 'core.AdminToolkitPriority';
	$results['status'] = $status;
	$results['error'] = array();

	return array(null, $results);
    }
}

/**
 * This view will show toolkits and their relative priorities
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class AdminToolkitPriorityView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'AdminToolkitPriority') {
	    $form['formName'] = 'AdminToolkitPriority';
	}

	list ($ret, $list) = GalleryCoreApi::getRedundantToolkitPriorities();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	asort($list);

	$AdminToolkitPriority = array('list' => $list, 'count' => count($list));
	$template->setVariable('AdminToolkitPriority', $AdminToolkitPriority);
	$template->setVariable('controller', 'core.AdminToolkitPriority');

	return array(null,
		     array('body' => 'modules/core/templates/AdminToolkitPriority.tpl'));
    }
}
?>
