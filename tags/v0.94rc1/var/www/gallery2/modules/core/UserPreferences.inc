<?php
/*
 * $RCSfile: UserPreferences.inc,v $
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
 * @version $Revision: 1.29 $ $Date: 2006/03/06 06:56:06 $
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
class UserPreferencesController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$userId = $gallery->getActiveUserId();

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($userId == $anonymousUserId) {
	    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
	}

	$results = array();
	$status = array();
	$error = array();
	if (isset($form['action']['cancel'])) {

	    /* Redirect back to the previous view */
	    $results['return'] = true;

	} else if (isset($form['action']['undo'])) {

	    /* Redirect back to the same view to reset the form */
	    $redirect['view'] = 'core.UserAdmin';
	    $redirect['subView'] = 'core.UserPreferences';

	} else if (isset($form['action']['save'])) {

	    /* Validate our data before we continue. */

	    /* full name is optional */

	    /* language is optional for everybody */

	    list ($ret, $isAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock($userId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    
	    list ($ret, $user) = GalleryCoreApi::loadEntitiesById($userId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }


	    /* email is required for non-anonymous but optional for administrators */
	    if (empty($form['email']) && !$isAdmin) {
		$error[] = 'form[error][email][missing]';
	    } else if (!empty($form['email'])) {
		$form['email'] = trim($form['email']);
		if (!GalleryUtilities::isValidEmailString($form['email'])) {
		    $error[] = 'form[error][email][invalid]';
		}
	    }

	    if (!empty($form['currentPassword']) &&
		    !$user->isCorrectPassword($form['currentPassword'])) {
		$error[] = 'form[error][currentPassword][incorrect]';
	    }
	    
	    /* Set a default if for some reason it doesn't get sent from the browser */
	    $form['email'] = empty($form['email']) ? '' : $form['email'];

	    /* Require a password to change the email address */
	    if ($form['email'] != $user->getEmail()) {
		if (empty($form['currentPassword'])) {
		    $error[] = 'form[error][currentPassword][missing]';
		}
		}

	    /* If all the right fields are in place then go ahead and modify the user. */
	    if (empty($error)) {
		$user->setFullname(trim($form['fullName']));
		$user->setEmail($form['email']);

		if (isset($form['language'])) {
		    $user->setLanguage($form['language']);
		    /* update the effective language in the current session */
		    $session =& $gallery->getSession();
		    $session->put('core.language', $form['language']);
		}

		$ret = $user->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Request a redirect to the confirmation screen */
		$redirect['view'] = 'core.UserAdmin';
		$redirect['subView'] = 'core.UserPreferences';
		$status['saved'] = 1;
	    }

	    $ret = GalleryCoreApi::releaseLocks($lockId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.UserAdmin';
	    $results['delegate']['subView'] = 'core.UserPreferences';
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
class UserPreferencesView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$userId = $gallery->getActiveUserId();
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($userId == $anonymousUserId) {
	    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
	}

	$user = $gallery->getActiveUser();

	/* Load the form with user data the first time around. */
	if ($form['formName'] != 'UserPreferences') {
	    $form['userName'] = $user->getUserName();
	    $form['fullName'] = $user->getFullName();
	    $form['email'] = $user->getEmail();
	    $form['language'] = $user->getLanguage();
	    $form['formName'] = 'UserPreferences';
	}

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

	$UserPreferences['translationsSupported'] = $translator->canTranslate();
	$UserPreferences['languageList'] = $languageList;

	/* Render the HTML body */
	$template->setVariable('UserPreferences', $UserPreferences);
	$template->setVariable('controller', 'core.UserPreferences');
	return array(null,
		     array('body' => 'modules/core/templates/UserPreferences.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('your account'));
    }
}
?>
