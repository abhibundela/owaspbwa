<?php
/*
 * $RCSfile: main.php,v $
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
include(dirname(__FILE__) . '/bootstrap.inc');

/*
 * If they don't have a setup password, we assume that the config.php is
 * empty and this is an initial install.
 */
if (!@$gallery->getConfig('setup.password')) {
    /* May be invalid if a multisite install lost its config.php; galleryBaseUrl unknown */
    header('Location: install/');
    return;
}

if (GalleryUtilities::isEmbedded()) {
    require_once(dirname(__FILE__) . '/init.inc');
} else {
    /* If this is a request for a public data file, give it to the user immediately */
    list ($view, $itemId) = GalleryUtilities::getRequestVariables('view', 'itemId');
    if ($view == 'core.DownloadItem' && !empty($itemId)) {
	/*
	 * Our urls are immutable because they have the serial numbers embedded.
	 * So if the browser presents us with an If-Modified-Since then it has
	 * the latest version of the file already.
	 */
	if (GalleryUtilities::getServerVar('HTTP_IF_MODIFIED_SINCE') ||
	        (function_exists('getallheaders') && ($headers = GetAllHeaders()) &&
		 (isset($headers['If-Modified-Since']) || isset($headers['If-modified-since'])))) {
	    header('HTTP/1.x 304 Not Modified');
	    return;
	}

	/*
	 * Fast download depends on having data.gallery.cache set, so set it now.  If for some
	 * reason we fail, we'll reset it in init.inc (but that's ok).
	 */
	$gallery->setConfig(
	    'data.gallery.cache', $gallery->getConfig('data.gallery.base') . 'cache/');

	$path = GalleryDataCache::getCachePath(
	    array('type' => 'fast-download', 'itemId' => $itemId));
	/* We don't have a platform yet so we have to use the raw file_exists */
	/* Disable fast-download in maintenance mode.. admins still get via core.DownloadItem */
	if (file_exists($path) && !$gallery->getConfig('mode.maintenance')) {
	    include($path);
	    if (GalleryFastDownload()) {
		return;
	    }
	}
    }

    /* Otherwise, proceed with our regular process */
    require_once(dirname(__FILE__) . '/init.inc');
    $ret = GalleryInitFirstPass();
    if ($ret) {
	_GalleryMain_errorHandler($ret->wrap(__FILE__, __LINE__), null, false);
	return;
    }

    /* Process the request */
    GalleryMain();
}

function GalleryMain($embedded=false) {
    global $gallery;

    /* Process the request */
    list($ret, $g2Data) = _GalleryMain($embedded);
    if (!$ret) {
	$gallery->performShutdownActions();

	/* Write out our session data */
	$session =& $gallery->getSession();
	$ret = $session->save();
    }

    /* Complete our transaction */
    if (!$ret && $gallery->isStorageInitialized()) {
	$storage =& $gallery->getStorage();
	$ret = $storage->commitTransaction();
    }

    /* Error handling (or redirect info in debug mode) */
    if ($ret) {
	_GalleryMain_errorHandler($ret->wrap(__FILE__, __LINE__), $g2Data);
	$g2Data['isDone'] = true;

	if ($ret && $gallery->isStorageInitialized()) {
	    /* Nuke our transaction, too */
	    $storage =& $gallery->getStorage();
	    $storage->rollbackTransaction();
	}
    } else if (isset($g2Data['redirectUrl'])) {
	/* If we're in debug mode, show a redirect page */
	print '<h1> Debug Redirect </h1> ' .
	    'Not automatically redirecting you to the next page because we\'re in debug mode<br/>';
	printf('<a href="%s">Continue to the next page</a>', $g2Data['redirectUrl']);
	print '<hr/>';
	print "<pre>";
	print $gallery->getDebugBuffer();
	print "</pre>";
    }

    return $g2Data;
}

/**
 * Process our request
 * @return array object GalleryStatus a status code
 *               array[]
 */
function _GalleryMain($embedded=false) {
    global $gallery;

    $main = array();
    $urlGenerator =& $gallery->getUrlGenerator();

    /* Figure out the target view/controller */
    list($viewName, $controllerName) = GalleryUtilities::getRequestVariables('view', 'controller');
    $gallery->debug("controller $controllerName, view $viewName");

    /* Check if core module needs upgrading */
    list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core', true);
    if ($ret) {
	return array($ret->wrap(__FILE__, __LINE__), null);
    }
    $installedVersions = $core->getInstalledVersions();
    if ($installedVersions['core'] != $core->getVersion()) {
	if ($redirectUrl = @$gallery->getConfig('mode.maintenance')) {
	    /* Maintenance mode -- redirect if given url, else simple message */
	    if ($redirectUrl === true) {
		print $core->translate('Site is temporarily down for maintenance.');
		exit;
	    }
	} else {
	    $gallery->debug('Redirect to the upgrade wizard, core module version is out of date');
	    $redirectUrl = $urlGenerator->getCurrentUrlDir(true) . 'upgrade/index.php';
	}
	return array(null, _GalleryMain_doRedirect($redirectUrl));
    }

    $ret = GalleryInitSecondPass();
    if ($ret) {
	return array($ret->wrap(__FILE__, __LINE__), null);
    }

    /* Load and run the appropriate controller */
    $results = array();
    if (!empty($controllerName)) {
	GalleryCoreApi::requireOnce('modules/core/classes/GalleryController.class');
	list ($ret, $controller) = GalleryController::loadController($controllerName);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!$embedded && $gallery->getConfig('mode.embed.only') &&
		!$controller->isAllowedInEmbedOnly()) {
	    /* Lock out direct access when embed-only is set */
	    return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
	}
	if ($gallery->getConfig('mode.maintenance') && !$controller->isAllowedInMaintenance()) {
	    /* Maintenance mode - allow admins, else redirect to given or standard url */
	    list ($ret, $isAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (!$isAdmin) {
		if (($redirectUrl = $gallery->getConfig('mode.maintenance')) === true) {
		    $redirectUrl =
			$urlGenerator->generateUrl(array('view' => 'core.MaintenanceMode'),
						   array('forceFullUrl' => true));
		}
		return array(null, _GalleryMain_doRedirect($redirectUrl));
	    }
	}

	/* Get our form and return variables */
	$form = GalleryUtilities::getFormVariables('form');

	/* Let the controller handle the input */
	list ($ret, $results) = $controller->handleRequest($form);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	/* Check to make sure we got back everything we want */
	if (!isset($results['status']) ||
	    !isset($results['error']) ||
	    (!isset($results['redirect']) &&
	     !isset($results['delegate']) &&
	     !isset($results['return']))) {
	    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
					      'Controller results are missing status, ' .
					      'error, (redirect, delegate, return)'),
			 null);
	}

	/* Try to return if the controller instructs it */
	if (!empty($results['return'])) {
	    list ($ret, $navigationLinks) = $urlGenerator->getNavigationLinks(1);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (count($navigationLinks) > 0) {
		/* Go back to the previous navigation point in our history */
		$redirectUrl = $navigationLinks[0]['url'];
	    } else {
		$redirectUrl = GalleryUtilities::getRequestVariables('return');
		if (empty($redirectUrl)) {
		    $redirectUrl = GalleryUtilities::getRequestVariables('formUrl');
		}
	    }
	}

	/* Failing that, redirect if so instructed */
	if (empty($redirectUrl) && !empty($results['redirect'])) {
	    /*
	     * If we have a status, store its data in the session and attach it
	     * to the URL.
	     */
	    if (!empty($results['status'])) {
		$session =& $gallery->getSession();
		$results['redirect']['statusId'] = $session->putStatus($results['status']);
	    }

	    $urlToGenerate = $results['redirect'];
	    /* Keep our navId in the URL */
	    $navId = $urlGenerator->getNavigationId();
	    if (!empty($navId)) {
		$urlToGenerate['navId'] = $navId;
	    }
	    $redirectUrl = $urlGenerator->generateUrl($urlToGenerate,
						      array('forceFullUrl' => true));
	}

	/* If we have a redirect url.. use it */
	if (!empty($redirectUrl)) {
	    return array(null,
			 _GalleryMain_doRedirect($redirectUrl, null, $controllerName));
	}

	/* Let the controller specify the next view */
	if (!empty($results['delegate'])) {
	    /* Load any errors into the request */
	    if (!empty($results['error'])) {
		foreach ($results['error'] as $error) {
		    GalleryUtilities::putRequestVariable($error, 1);
		}
	    }

	    /* Save the view name, put the rest into the request so the view can get it */
	    foreach ($results['delegate'] as $key => $value) {
		switch($key) {
		case 'view':
		    $viewName = $value;
		    break;

		default:
		    GalleryUtilities::putRequestVariable($key, $value);
		    break;
		}
	    }
	}
    }
    /* Load and run the appropriate view */
    if (empty($viewName)) {
	$viewName = GALLERY_DEFAULT_VIEW;
	GalleryUtilities::putRequestVariable('view', $viewName);
    }

    list ($ret, $view) = GalleryView::loadView($viewName);
    if ($ret) {
	return array($ret->wrap(__FILE__, __LINE__), null);
    }

    if ($gallery->getConfig('mode.maintenance') && !$view->isAllowedInMaintenance()) {
	/* Maintenance mode - allow admins, else redirect to given url or show standard view */
	list ($ret, $isAdmin) = GalleryCoreApi::isUserInSiteAdminGroup();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!$isAdmin) {
	    if (($redirectUrl = $gallery->getConfig('mode.maintenance')) !== true) {
		return array(null, _GalleryMain_doRedirect($redirectUrl));
	    }
	    $viewName = 'core.MaintenanceMode';
	    list ($ret, $view) = GalleryView::loadView($viewName);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}
    }
    if (!$embedded && $gallery->getConfig('mode.embed.only') && !$view->isAllowedInEmbedOnly()) {
	/* Lock out direct access when embed-only is set */
	return array(GalleryCoreApi::error(ERROR_PERMISSION_DENIED, __FILE__, __LINE__), null);
    }

    /* Check if the page is cached and return the cached version, else generate the page */
    list ($ret, $shouldCache) = GalleryDataCache::shouldCache('read', 'full');
    if ($ret) {
	return array($ret->wrap(__FILE__, __LINE__), null);
    }

    $html = '';
    if ($shouldCache) {
	$session =& $gallery->getSession();
	list ($ret, $html) = GalleryDataCache::getPageData(
	    'page', $urlGenerator->getCacheableUrl());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
    }

    if (!empty($html)) {
	/*
	 * TODO: If we cache all the headers and replay them here, we could send a 304 not
	 * modified back
	 */
	$session =& $gallery->getSession();
	$html = $session->replaceTempSessionIdIfNecessary($html);

	/* Set the appropriate charset in our HTTP header */
	if (!headers_sent()) {
	    header('Content-Type: text/html; charset=UTF-8');
	}

	print $html;
	$data['isDone'] = true;
    } else {
	/* Initialize our container for template data */
	$gallery->setCurrentView($viewName);

	/*
	 * If we render directly to the browser, we need get a session before,
	 * or no session at all
	 */
	if ($view->isImmediate() || $viewName == 'core.ProgressBar') {
	    /*
	     * Session: Find out whether we need to send a cookie & get a new sessionId and save it
	     * (make sure there's a sessionId before starting to render, but only if we need a
	     * session)
	     */
	    $session =& $gallery->getSession();
	    $ret = $session->start();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    /* From now on, don't add navid / sessionId to URLs if there's no persistent session */
	    $session->doNotUseTempId();
	}

	/*
	 * If this is an immediate view, it will send its own output directly.  This is
	 * used in the situation where we want to send back data that's not controlled by the
	 * layout.  That's usually something that's not user-visible like a binary file.
	 */
	$data = array();
	if ($view->isImmediate()) {
	    $status = isset($results['status']) ? $results['status'] : array();
	    $error = isset($results['error']) ? $results['error'] : array();
	    $ret = $view->renderImmediate($status, $error);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $data['isDone'] = true;
	} else {
	    GalleryCoreApi::requireOnce('modules/core/classes/GalleryTemplate.class');
	    $template = new GalleryTemplate(dirname(__FILE__));
	    list ($ret, $results, $theme) = $view->doLoadTemplate($template);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (isset($results['redirect']) || isset($results['redirectUrl'])) {
		if (isset($results['redirectUrl'])) {
		    $redirectUrl = $results['redirectUrl'];
		} else {
		    $redirectUrl = $urlGenerator->generateUrl($results['redirect'],
		    					      array('forceFullUrl' => true));
		}
		return array(null,
			     _GalleryMain_doRedirect($redirectUrl, $template));
	    }

	    if (empty($results['body'])) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
						   'View results are missing body file'), null);
	    }

	    $templatePath = 'gallery:' . $results['body'];
	    $template->setVariable('l10Domain', $theme->getL10Domain());
	    $template->setVariable('isEmbedded', $embedded);

	    if ($viewName == 'core.ProgressBar') {
		/* Render progress bar pages immediately so that the user sees the bar moving */
		$ret = $template->display($templatePath);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$data['isDone'] = true;
	    } else {
		list ($ret, $html) = $template->fetch($templatePath);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$html = preg_replace('/^\s+/m', '', $html);

		list ($ret, $shouldCache) = GalleryDataCache::shouldCache('write', 'full');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($shouldCache && $results['cacheable']) {
		    $htmlForCache = $html;
		}

		/*
		 * Session: Find out whether we need to send a cookie & need a new session
		 * (only if we don't have one yet)
		 */
		$session =& $gallery->getSession();
		$ret = $session->start();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$html = $session->replaceTempSessionIdIfNecessary($html);

		if ($embedded) {
		    $data = $theme->splitHtml($html, $results);
		    $data['themeData'] =& $template->getVariableByReference('theme');
		    $data['isDone'] = false;
		} else {
		    /* Set the appropriate charset in our HTTP header */
		    if (!headers_sent()) {
			header('Content-Type: text/html; charset=UTF-8');
		    }
		    print $html;

		    if ($shouldCache && $results['cacheable']) {
			$session =& $gallery->getSession();
			if ($session->getId() != SESSION_TEMP_ID) {
			    $htmlForCache = str_replace($session->getId(),
			    				SESSION_TEMP_ID, $htmlForCache);
			}
			$ret = GalleryDataCache::putPageData(
			    'page', $results['cacheable'],
			    $urlGenerator->getCacheableUrl(),
			    $htmlForCache);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    }
		    $data['isDone'] = true;
		}
	    }
	}
    }

    return array(null, $data);
}

function _GalleryMain_doRedirect($redirectUrl, $template=null, $controller=null) {
    global $gallery;

    /* Create a valid sessionId for guests, if required */
    $session =& $gallery->getSession();
    $ret = $session->start();
    if ($ret) {
	return array($ret->wrap(__FILE__, __LINE__), null);
    }
    $redirectUrl = $session->replaceTempSessionIdIfNecessary($redirectUrl);
    $session->doNotUseTempId();

    /*
     * UserLogin returnUrls don't have a sessionId in the URL to replace, make sure
     * there's a sessionId in the redirectUrl for users that don't use cookies
     */
    if (!$session->isUsingCookies() && $session->isPersistent() &&
	    strpos($redirectUrl, $session->getKey()) === false) {
        $redirectUrl = GalleryUrlGenerator::appendParamsToUrl(
         				$redirectUrl,
         				array($session->getKey() => $session->getId()));
    }

    if ($gallery->getDebug() == false || $gallery->getDebug() == 'logged') {
	/*
	 * The URL generator makes HTML 4.01 compliant URLs using
	 * &amp; but we don't want those in our Location: header.
	 */
	$redirectUrl = str_replace('&amp;', '&', $redirectUrl);
	$redirectUrl = rtrim($redirectUrl, '&? ');

	/*
	 * IIS 3.0 - 5.0 webservers will ignore all other headers if the location header is set.
	 * It will simply not send other headers, e.g. the set-cookie header, which is important
	 * for us in the login and logout requests / redirects.
	 * see: http://support.microsoft.com/kb/q176113/
	 * Our solution: detect IIS version and append GALLERYSID to the Location URL if necessary
	 */
	if (in_array($controller, array('core.Logout', 'core.UserLogin', 'publishxp.Login'))) {
	    /* Check if it's IIS and if the version is < 6.0 */
	    $webserver = GalleryUtilities::getServerVar('SERVER_SOFTWARE');
	    if (!empty($webserver) &&
		    preg_match('|^Microsoft-IIS/(\d)\.\d$|', trim($webserver), $matches) &&
		    $matches[1] < 6) {
		/*
		 * It is IIS and it's a version with this bug, check if GALLERYSID is already in
		 * the URL, else append it
		 */
		$session =& $gallery->getSession();
		$sessionParamString =
		    GalleryUtilities::prefixFormVariable(urlencode($session->getKey())) . '=' .
		    urlencode($session->getId());
		if ($session->isPersistent() && !strstr($redirectUrl, $sessionParamString)) {
		    $redirectUrl .= (strpos($redirectUrl, '?') === false) ? '?' : '&';
		    $redirectUrl .= $sessionParamString;
		}
	    }
	}

	/* Use our PHP VM for testability */
	$phpVm = $gallery->getPhpVm();
	$phpVm->header("Location: $redirectUrl");
	return array('isDone' => true);
    } else {
	return array('isDone' => true, 'redirectUrl' => $redirectUrl, 'template' => $template);
    }
}

/* TODO: move this out of main.php so that we don't have load it on every page view */
function _GalleryMain_errorHandler($error, $g2Data=null, $initOk=true) {
    global $gallery;
    $failsafe = false;
    if (!$initOk) {
	$failsafe = true;
    }

    if (!$failsafe) {
	list ($ret, $themeId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'default.theme');
	if ($ret) {
	    $failsafe = true;
	}
    }

    if (!$failsafe) {
	list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
	if ($ret) {
	    $failsafe = true;
	}
	$templateAdapter =& $gallery->getTemplateAdapter();
	$templateAdapter->setTheme($theme);
    }

    if (!$failsafe) {
	list ($ret, $view) = GalleryView::loadView('core.ErrorPage');
	if ($ret) {
	    $failsafe = true;
	}
    }

    if (!$failsafe) {
	$dummyForm = array();
	GalleryCoreApi::requireOnce('modules/core/classes/GalleryTemplate.class');
	$template = new GalleryTemplate(dirname(__FILE__));
	$view->setError($error);
	list ($ret, $results) = $view->loadTemplate($template, $dummyForm);
	if ($ret) {
	    $failsafe = true;
	}

	$t =& $template->getVariableByReference('theme');
	$t['errorTemplate'] = $results['body'];
    }

    if (!$failsafe) {
	$template->setVariable('l10Domain', 'modules_core');
	list ($ret, $templatePath) = $theme->showErrorPage($template);
	if ($ret) {
	    $failsafe = true;
	}
    }

    if (!$failsafe) {
	$template->setVariable('l10Domain', 'themes_' . $themeId);
	$ret = $template->display("themes/$themeId/templates/$templatePath");
	if ($ret) {
	    $failsafe = true;
	}
    }

    if ($failsafe) {
	/* Some kind of catastrophic failure.  Just dump the error out to the browser. */
	print '<h2>Error</h2>' . $error->getAsHtml(false);
	if ($gallery->getDebug() == 'buffered') {
	    print '<h3>Debug Output</h3><pre>' . $gallery->getDebugBuffer() . '</pre>';
	}
	return;
    }
}
?>
