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
 * @version $Revision: 1.38 $ $Date: 2006/03/22 03:52:34 $
 * @package SelfRegistration
 * @author Sebastian Eichner <mailsp@sebastian-eichner.de>
 */

/**
 * The self-registration module
 *
 * @package SelfRegistration
 */
class RegisterModule extends GalleryModule {

    function RegisterModule() {
	global $gallery;

	$this->setId('register');
	$this->setName($gallery->i18n('Registration'));
	$this->setDescription($gallery->i18n('New User Registration'));
	$this->setVersion('1.0.6');
	$this->setGroup('gallery', $gallery->i18n('Gallery'));
	$this->setCallbacks('getSystemLinks|getUserAdminViews|getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::upgrade()
     */
    function upgrade($currentVersion) {
	list ($ret, $params) = GalleryCoreApi::fetchAllPluginParameters('module', 'register');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	foreach (array('confirmation' => 'admin', 'emailadmins' => 1,
		       'subject' => $this->translate('Account activation'),
		       'adminsubject' => $this->translate('New user registration'),
		       'from' => 'webmaster@' . GalleryUtilities::getServerVar('SERVER_NAME'),
		       'validation.level' => 'HIGH')
		as $key => $value) {
	    if (!isset($params[$key])) {
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
	    'GalleryEntity', 'GalleryPendingUser', 'GalleryPendingUser',
	    'modules/register/classes/GalleryPendingUser.class', 'register', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::registerFactoryImplementation(
	    'CaptchaAdminOption', 'RegisterCaptchaAdminOption', 'RegisterCaptchaAdminOption',
	    'modules/register/classes/RegisterCaptchaAdminOption.class', 'register', null);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::getModuleEntityTypes
     */
    function getModuleEntityTypes() {
	return array('GalleryPendingUser');
    }

    /**
     * @see GalleryModule::getSystemLinks
     */
    function getSystemLinks() {
	global $gallery;
	$links = array();

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Only anonymous users will see the register link */
	if ($gallery->getActiveUserId() == $anonymousUserId) {
	    $links['Register'] = array(
		'text' => $this->translate('Register'),
		'params' => array('view' => 'core.UserAdmin',
				  'subView' => 'register.UserSelfRegistration',
				  'return' => 1));
	}

	return array(null, $links);
    }

    /**
     * @see GalleryModule::getUserAdminViews();
     */
    function getUserAdminViews($user) {
	global $gallery;
	$views = array();

	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Only anonymous users will see the register link */
	if ($gallery->getActiveUserId() == $anonymousUserId) {
	    $views[] = array('name' => $this->translate('Register'),
			     'view' => 'register.UserSelfRegistration');
	}

	return array(null, $views);
    }

    /**
     * @see GalleryModule::getSiteAdminViews
     */
    function getSiteAdminViews() {
	return array(null,
		     array(array('name' => $this->translate('Registration'),
				 'view' => 'register.AdminSelfRegistration')));
    }
}
?>
