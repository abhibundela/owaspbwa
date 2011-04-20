<?php
/*
 * $RCSfile: UserLogin.inc,v $
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
 * @version $Revision: 1.32 $ $Date: 2006/02/09 05:02:27 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle an user logging in to Gallery
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class UserLoginController extends GalleryController {

    /**
     * ValidationPlugin instances to use when handling this request.  Only used by test code.
     *
     * @var array $_plugins (array of GalleryValidationPlugin)
     * @access private
     */
    var $_pluginInstances;

    /**
     * Tests can use this method to hardwire a specific set of plugin instances to use.
     * This avoids situations where some of the option instances will do unpredictable
     * things and derail the tests.
     *
     * @param array of GalleryValidationPlugin
     */
    function setPluginInstances($pluginInstances) {
	$this->_pluginInstances = $pluginInstances;
    }

    /**
     * @see GalleryController::isAllowedInMaintenance()
     */
    function isAllowedInMaintenance() {
	return true;
    }

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	global $gallery;

	$results = array();
	$error = array();
	if (isset($form['action']['login'])) {
	    if (empty($form['username'])) {
		$error[] = 'form[error][username][missing]';
	    }

	    if (empty($form['password'])) {
		$error[] = 'form[error][password][missing]';
	    }

	    if (empty($error)) {
		list ($ret, $user) = GalleryCoreApi::fetchUserByUsername($form['username']);
		if ($ret && !($ret->getErrorCode() & ERROR_MISSING_OBJECT)) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$isCorrect = ($user != null && $user->isCorrectPassword(
			    GalleryUtilities::htmlEntityDecode($form['password'])));

		/* Prepare for validation */
		$options = array('pass' => $isCorrect);
		list ($ret, $options['level']) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'validation.level');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($options['level'] == 'MEDIUM') {
		    $options['key'] = 'core.UserLogin.' . $form['username'];
		}
		if ($options['level'] == 'OFF') {
		    $pluginInstances = array();
		} else if (isset($this->_pluginInstances)) {
		    $pluginInstances = $this->_pluginInstances;
		} else {
		    list ($ret, $pluginInstances) =
			GalleryCoreApi::getAllFactoryImplementationIds('GalleryValidationPlugin');
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    foreach (array_keys($pluginInstances) as $pluginId) {
			list ($ret, $pluginInstances[$pluginId]) =
			    GalleryCoreApi::newFactoryInstanceById('GalleryValidationPlugin',
								   $pluginId);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    }
		}

		/* Let each plugin do its verification */
		foreach ($pluginInstances as $plugin) {
		    list ($ret, $pluginErrors, $continue) =
			$plugin->performValidation($form, $options);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $error = array_merge($error, $pluginErrors);
		    if (!$continue) {
			break;
		    }
		}
	    }

	    if (empty($error)) {
		if ($isCorrect) {
		    $gallery->setActiveUser($user);

		    $event = GalleryCoreApi::newEvent('Gallery::Login');
		    $event->setEntity($user);
		    list ($ret, $redirect) = GalleryCoreApi::postEvent($event);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $session =& $gallery->getSession();
		    $ret = $session->regenerate();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    /* Redirect if requested by event listener, otherwise return */
		    if (!empty($redirect)) {
			$results['redirect'] = array_shift($redirect);
		    } else {
			$results['return'] = 1;
		    }
		} else {
		    $error[] = 'form[error][invalidPassword]';
		}
	    }

	} else if (isset($form['action']['cancel'])) {
	    $results['return'] = 1;
	}

	if (!empty($error)) {
	    $results['delegate']['view'] = 'core.UserAdmin';
	    $results['delegate']['subView'] = 'core.UserLogin';
	}
	$results['status'] = array();
	$results['error'] = $error;

	return array(null, $results);
    }

}

/**
 * This view prompts for login information
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class UserLoginView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	if ($form['formName'] != 'UserLogin') {
	    $form['formName'] = 'UserLogin';
	    $form['username'] = '';
	    
	    /*
	     * When logging in we don't have a session yet, thus no navigation history / a place
	     * to store the returnUrl. Thus store the returnUrl in the login form
	     */
	    $returnUrl = GalleryUtilities::getRequestVariables('return');
	    if (!empty($returnUrl)) {
		$form['returnUrl'] = $returnUrl;
	    }
	}

	$template->setVariable('controller', 'core.UserLogin');
	return array(null, array('body' => 'modules/core/templates/UserLogin.tpl'));
    }
}
?>
