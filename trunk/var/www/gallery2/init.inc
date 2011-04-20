<?php
/*
 * $RCSfile: init.inc,v $
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
 * @version $Revision: 1.19 $ $Date: 2006/01/30 23:00:05 $
 * @package Gallery
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Perform all necessary initialization of the Gallery framework
 */
function GalleryInitFirstPass($params=array()) {
    global $gallery;

    ini_set('magic_quotes_runtime', 0);
    ini_set('magic_quotes_sybase', 0);

    /* Specify that when an assertion fails, we terminate right away. */
    assert_options(ASSERT_WARNING, 1);
    assert_options(ASSERT_BAIL, 1);

    /* Load all the core Gallery classes */
    $galleryBase = dirname(__FILE__) . '/';
    require_once($galleryBase . 'modules/core/classes/GalleryCoreApi.class');
    GalleryCoreApi::requireOnce('modules/core/classes/GalleryConstants.class', true);
    GalleryCoreApi::requireOnce('modules/core/classes/GalleryUtilities.class', true);
    GalleryCoreApi::requireOnce('modules/core/classes/GalleryCapabilities.class', true);
    GalleryCoreApi::requireOnce('modules/core/classes/GalleryView.class', true);
    GalleryCoreApi::requireOnce('modules/core/classes/GalleryModule.class', true);

    if (!strncasecmp(PHP_OS, 'win', 3)) {
	GalleryCoreApi::requireOnce(
	    'modules/core/classes/GalleryPlatform/WinNtPlatform.class', true);
	$platform = new WinNtPlatform();
    } else {
	GalleryCoreApi::requireOnce(
	    'modules/core/classes/GalleryPlatform/UnixPlatform.class', true);
	$platform = new UnixPlatform();
    }

    $gallery->setPlatform($platform);
    $slash = $platform->getDirectorySeparator();

    if (isset($params['debug'])) {
	$gallery->setDebug($params['debug']);
    }

    /* Sanitize the data path */
    $dataBase = $gallery->getConfig('data.gallery.base');
    if ($dataBase{strlen($dataBase)-1} != $slash) {
	$dataBase .= $slash;
	$gallery->setConfig('data.gallery.base', $dataBase);
    }

    /* Init for downloadable plugins */
    $gallery->setConfig('plugins.dirname', 'plugins');
    $gallery->setConfig('repository.url', 'http://gallery.menalto.com/repository/gallery2/');
    $gallery->setConfig('repository.cache', sprintf('%splugins_data%smodules%score%srepository%s',
	$dataBase, $slash, $slash, $slash, $slash));

    /* Set our various data paths */
    $gallery->setConfig('data.gallery.cache', $dataBase . 'cache' . $slash);
    $gallery->setConfig('data.gallery.albums', $dataBase . 'albums' . $slash);
    $gallery->setConfig('data.gallery.locks', $dataBase . 'locks'. $slash);
    $gallery->setConfig('data.gallery.tmp', $dataBase . 'tmp' . $slash);
    $gallery->setConfig('data.smarty.base', $dataBase . 'smarty' . $slash);
    $gallery->setConfig('data.smarty.templates_c',
	$dataBase . 'smarty' . $slash . 'templates_c' . $slash);
    $gallery->setConfig('data.gallery.plugins', $galleryBase . 'plugins' . $slash);
    $gallery->setConfig('data.gallery.plugins_data', $dataBase . 'plugins_data' . $slash);

    /* Configure our url generator */
    if (!isset($params['noDatabase'])) {
	/*
	 * Swallow error to prevent GalleryFactoryHelper_loadRegistry
	 * cache from breaking upgrade to core 1.0.6.
	 */
	list ($ret, $urlGenerator) = @GalleryCoreApi::newFactoryInstance('GalleryUrlGenerator');
	/* Swallow ERROR_STORAGE_FAILURE, or automatic upgrading fails */
	if ($ret && !($ret->getErrorCode() & ERROR_STORAGE_FAILURE)) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
    }
    if (!isset($urlGenerator)) {
	GalleryCoreApi::requireOnce('modules/core/classes/GalleryUrlGenerator.class', true);
	$urlGenerator = new GalleryUrlGenerator();
    }
    /* Allow for overrides from GalleryEmbed ($param) or from config.php */
    $configBaseUri = @$gallery->getConfig('baseUri');
    $ret = $urlGenerator->init(
	isset($params['baseUri']) ? $params['baseUri'] :
	                            (!empty($configBaseUri) ? $configBaseUri : null),
	isset($params['g2Uri']) ? $params['g2Uri'] : null,
	isset($params['embedSessionString']) ? $params['embedSessionString'] : null);
    if ($ret) {
	return $ret->wrap(__FILE__, __LINE__);
    }
    $gallery->setUrlGenerator($urlGenerator);

    /* Initialize our session */
    if (!isset($params['noDatabase'])) {
	if (isset($params['gallerySessionId'])) {
	    GalleryCoreApi::requireOnce('modules/core/classes/GallerySession.class', true);
	    GalleryUtilities::putRequestVariable(SESSION_ID_PARAMETER, $params['gallerySessionId']);
	}
	$ret = $gallery->initSession();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
    } else {
	$gallery->initEmptySession();
    }

    /* Initialize our translator */
    $language = GalleryUtilities::getRequestVariables('language');
    if (isset($params['activeLanguage']) || !empty($language)) {
	GalleryCoreApi::requireOnce('modules/core/classes/GalleryTranslator.class', true);
	list ($language) = GalleryTranslator::getSupportedLanguageCode(
	    empty($language) ? $params['activeLanguage'] : $language);
	$gallery->setActiveLanguageCode($language);
    }
    $ret = $gallery->initTranslator(isset($params['noDatabase']));
    if ($ret) {
	return $ret->wrap(__FILE__, __LINE__);
    }

    return null;
}

function GalleryInitSecondPass() {
    global $gallery;
    $session =& $gallery->getSession();
    $urlGenerator =& $gallery->getUrlGenerator();

    $ret = $urlGenerator->initNavigation();
    if ($ret) {
	return $ret->wrap(__FILE__, __LINE__);
    }
    
    /*
     * Set our active user id.  Check to see if we have one in our session.  If
     * not, make us the anonymous user.  If we don't have a session, this will
     * initiate one for us.
     */
    $activeUserId = $session->getUserId();
    if (empty($activeUserId)) {
	/* No active user -- be anonymous */
	list ($ret, $activeUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
    }

    list ($ret, $activeUser) = GalleryCoreApi::loadEntitiesById($activeUserId);
    if ($ret) {
	if ($ret->getErrorCode() & ERROR_MISSING_OBJECT) {
	    /* Missing user -- be anonymous */
	    list ($ret, $activeUserId) =
		GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $activeUser) = GalleryCoreApi::loadEntitiesById($activeUserId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	} else {
	    return $ret->wrap(__FILE__, __LINE__);
	}
    }

    $gallery->setActiveUser($activeUser);

    return null;
}
?>
