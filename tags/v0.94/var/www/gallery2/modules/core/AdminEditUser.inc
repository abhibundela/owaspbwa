<?php
/*
 * $RCSfile: AdminEditUser.inc,v $
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
 * @version $Revision: 1.40 $ $Date: 2006/01/21 01:36:45 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle changes made to an user
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminEditUserController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$results = $status = $error = array();

	$userId = GalleryUtilities::getRequestVariables('userId');
	list ($ret, $user) = GalleryCoreApi::loadEntitiesById($userId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/*
	 * We treat anonymous users differently, so see if we're editing the anonymous user.
	 */
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$isAnonymous = ($userId == $anonymousUserId);

	if (isset($form['action']['cancel'])) {

	    /* Redirect back to the AdminUsers view */
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'core.AdminUsers';

	} else if (isset($form['action']['undo'])) {

	    /* Redirect back to the same view to reset the form */
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'core.AdminEditUser';
	    $redirect['userId'] = $userId;

	} else if (isset($form['action']['save'])) {

	    /*
	     * Validate our data before we continue.
	     */

	    /* username is required and might conflict */
	    if (empty($form['userName'])) {
		$error[] = 'form[error][userName][missing]';
	    }

	    if (!empty($form['userName'])) {
		list ($ret, $tmpUser) = GalleryCoreApi::fetchUserByUsername($form['userName']);
		if ($ret && !($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if (!empty($tmpUser) && $tmpUser->getId() != $userId) {
		    $error[] = 'form[error][userName][duplicate]';
		}
	    }

	    /* full name is optional for everybody */

	    /* language is optional for everybody */

	    /* email is suggested, but optional for administrator edits */
	    if (!empty($form['email'])) {
		$form['email'] = trim($form['email']);
		if (!GalleryUtilities::isValidEmailString($form['email'])) {
		    $error[] = 'form[error][email][invalid]';
		}
	    }

	    if (!$isAnonymous) {
		if ($form['password1'] != $form['password2']) {
		    $error[] = 'form[error][password2][mismatch]';
		}
	    }

	    /*
	     * If all the right fields are in place then go ahead and modify the user.
	     */
	    if (empty($error)) {
		list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($user->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $user) = $user->refresh();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$user->setUserName(trim($form['userName']));
		$user->setFullname(trim($form['fullName']));
		if (!$isAnonymous) {
		    $user->setEmail($form['email']);
		    $user->setLanguage($form['language']);
		    if (!empty($form['password1'])) {
			$user->changePassword(
			    GalleryUtilities::htmlEntityDecode($form['password1']));
		    }
		}

		$ret = $user->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Request a redirect to the confirmation screen */
		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminUsers';
		$status['modifiedUser'] = $user->getUserName();
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.SiteAdmin';
	    $results['delegate']['subView'] = 'core.AdminEditUser';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will show a form to change user options
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminEditUserView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$userId = GalleryUtilities::getRequestVariables('userId');
	list ($ret, $user) = GalleryCoreApi::loadEntitiesById($userId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load the form with user data the first time around. */
	if ($form['formName'] != 'AdminEditUser') {
	    $form['userName'] = $user->getUserName();
	    $form['fullName'] = $user->getFullName();
	    $form['email'] = $user->getEmail();
	    $form['language'] = $user->getLanguage();
	    $form['formName'] = 'AdminEditUser';
	}

	/* Email is not required for the anonymous user */
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$show['email'] = ($userId != $anonymousUserId);
	$show['language'] = ($userId != $anonymousUserId);
	$show['password'] = ($userId != $anonymousUserId);

	if ($show['language']) {
	    /* Set up our language selection list */
	    list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $languageList = array();
	    $languageList[''] = $module->translate('<none>');
	    $translator =& $gallery->getTranslator();
	    $supportedLanguages = $translator->getSupportedLanguages();
	    foreach ($supportedLanguages as $language => $countryList) {
		foreach ($countryList as $country => $languageData) {
		    $languageList[$language . '_' . $country] = $languageData['description'];
		}
	    }
	}

	$AdminEditUser = array();
	$AdminEditUser['show'] = $show;
	$AdminEditUser['user'] = (array)$user;
	if (isset($languageList)) {
	    $AdminEditUser['languageList'] = $languageList;
	}

	$template->setVariable('AdminEditUser', $AdminEditUser);
	$template->setVariable('controller', 'core.AdminEditUser');
	return array(null,
		     array('body' => 'modules/core/templates/AdminEditUser.tpl'));
    }
}
?>
