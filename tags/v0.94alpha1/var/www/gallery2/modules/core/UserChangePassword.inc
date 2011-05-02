<?php
/*
 * $RCSfile: UserChangePassword.inc,v $
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
 * @version $Revision: 1.16 $ $Date: 2006/01/10 04:39:21 $
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
class UserChangePasswordController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$user = $gallery->getActiveUser();

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($user->getId() == $anonymousUserId) {
	    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
	}

	$results = $status = $error = array();
	if (isset($form['action']['cancel'])) {

	    /* Redirect back to user preferences */
	    $redirect['view'] = 'core.UserAdmin';
	    $redirect['subView'] = 'core.UserPreferences';

	} else if (isset($form['action']['undo'])) {

	    /* Redirect back to the same view to reset the form */
	    $redirect['view'] = 'core.UserAdmin';
	    $redirect['subView'] = 'core.UserChangePassword';

	} else if (isset($form['action']['save'])) {

	    /*
	     * Validate our data before we continue.
	     */
	    if (empty($form['currentPassword'])) {
		$error[] = 'form[error][currentPassword][missing]';
	    } else if (!$user->isCorrectPassword($form['currentPassword'])) {
		$error[] = 'form[error][currentPassword][incorrect]';
	    }

	    if (empty($form['password1'])) {
		$error[] = 'form[error][password1][missing]';
	    }

	    if (empty($form['password2'])) {
		$error[] = 'form[error][password2][missing]';
	    }

	    if (!$error && $form['password1'] != $form['password2']) {
		$error[] = 'form[error][password2][mismatch]';
	    }

	    /*
	     * If all the right fields are in place then go ahead and modify
	     * the user.
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

		$user->changePassword(GalleryUtilities::htmlEntityDecode($form['password1']));

		$ret = $user->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Request a redirect to the confirmation screen */
		$redirect['view'] = 'core.UserAdmin';
		$redirect['subView'] = 'core.UserChangePassword';
		$status['changedPassword'] = 1;
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.UserAdmin';
	    $results['delegate']['subView'] = 'core.UserChangePassword';
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
class UserChangePasswordView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	/* Load the form with user data the first time around. */
	if ($form['formName'] != 'UserChangePassword') {
	    $form['formName'] = 'UserChangePassword';
	}

	/* Render the HTML body */
	$template->setVariable('controller', 'core.UserChangePassword');
	return array(null,
		     array('body' => 'modules/core/templates/UserChangePassword.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $core->translate('change password'));
    }
}
?>
