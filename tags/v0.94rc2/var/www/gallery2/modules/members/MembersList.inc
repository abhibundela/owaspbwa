<?php
/*
 * $RCSfile: MembersList.inc,v $
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
 * @version $Revision: 1.15 $ $Date: 2006/01/10 04:40:36 $
 * @package Members
 * @subpackage UserInterface
 * @author Robert Balousek <rbalousek@hotmail.com>
 */

/**
 * This view will allow users to view the member list
 *
 * @package Members
 * @subpackage UserInterface
 */
class MembersListView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
    	global $gallery;
	GalleryCoreApi::requireOnce('modules/members/classes/GalleryMembersHelper.class');
	list ($ret, $result) =
	    GalleryMembersHelper::canViewMembersModule($gallery->getActiveUserId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!$result) {
	    return array(null,
			 array('redirect' => array('view' => 'core.ShowItem')));
	}

	if ($form['formName'] != 'MembersList') {
	    /* Set some defaults */
	    $form['text']['userName'] = '';
	    $form['text']['userId'] = '';
	    $form['formName'] = 'MembersList';
	    $MembersList = array();
	}

	/* Bring in the root album information for the breadcrumb */
	list ($ret, $rootAlbumId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $rootAlbum) = GalleryCoreApi::loadEntitiesById($rootAlbumId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$MembersList['rootAlbum'] = (array)$rootAlbum;

	if (!isset($form['list']['page']) || $form['list']['page'] < 1) {
	    $form['list']['page'] = 1;
	}

	list ($ret, $groupId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.allUserGroup');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Fetch the user count every time we reload */
	list ($ret, $totalUserCount) = GalleryCoreApi::fetchUserCount(null, $groupId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$form['list']['count'] = $totalUserCount;
	$form['list']['pageSize'] = $totalUserCount > 25 ? 25 : $totalUserCount;

	/* Figure out our max pages, make sure our current page fits in it */
	$form['list']['maxPages'] = ceil($form['list']['count'] / $form['list']['pageSize']);
	if ($form['list']['page'] > $form['list']['maxPages']) {
	    $form['list']['page'] = $form['list']['maxPages'];
	}

	/* Calculate the next/back pages */
	$form['list']['nextPage'] = min($form['list']['page']+1, $form['list']['maxPages']);
	$form['list']['backPage'] = max(1, $form['list']['page']-1);
	$form['list']['startingUser'] = ($form['list']['page'] - 1) * $form['list']['pageSize'];

	list ($ret, $userNames) = GalleryCoreApi::fetchUsersForGroup($groupId,
								     $form['list']['pageSize'],
								     $form['list']['startingUser']);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $users) = GalleryCoreApi::loadEntitiesById(array_keys($userNames));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$MembersList['users'] = array();
	foreach ($users as $user) {
	    $MembersList['users'][$user->getId()] = (array)$user;
	}

	$form['list']['totalUserCount'] = $totalUserCount;

	/* Render the HTML body */
	$template->setVariable('MembersList', $MembersList);

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'members');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$template->title($module->translate('Members List'));
	return array(null,
		     array('body' => 'modules/members/templates/MembersList.tpl'));
    }
}
?>
