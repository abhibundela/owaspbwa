<?php
/*
 * $RCSfile: SetupRewrite.inc,v $
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
 * @package Rewrite
 * @version $Revision: 1.16 $ $Date: 2006/01/10 04:42:16 $
 * @author Douglas Cau <douglas@cau.se>
 */

/**
 * @package Rewrite
 * @subpackage UserInterface
 */
class SetupRewriteController extends GalleryController {

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest(&$form) {
	GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
	global $gallery;
	$platform =& $gallery->getPlatform();
	$status = $error = array();
	$results = array('redirect' => array('view' => 'core.SiteAdmin',
					     'subView' => 'rewrite.SetupRewrite'));

	if (isset($form['action']['testParser'])) {
	    list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $error, $status) = $rewriteParser->handleTestResultsRequest($form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	} else if (isset($form['action']['adminParser'])) {
	    list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $error, $status) = $rewriteParser->handleAdminParserRequest($form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	} else if (isset($form['action']['save']) && isset($form['parser'])) {
	    if (empty($form['parser']) || !preg_match('/\w/', $form['parser'])) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	    }
	    
	    $ret = GalleryCoreApi::setPluginParameter('module', 'rewrite',
	        'parserId', $form['parser']);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $code) = $rewriteParser->needsConfiguration();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (!$code) {
		$results['redirect']['subView'] = 'core.AdminModules';
	    }

	    $status['saved'] = 1;
	} else if (isset($form['action']['done'])) {
	    $results['redirect']['subView'] = 'core.AdminModules';
	} else if (isset($form['action']['back'])) {
	    $ret = GalleryCoreApi::setPluginParameter('module', 'rewrite', 'parserId', '');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	if (!empty($error)) {
	    unset($results['redirect']);
	    $results['delegate'] = array('view' => 'core.SiteAdmin',
					 'subView' => 'rewrite.SetupRewrite');
	}
	$results['error'] = $error;
	$results['status'] = $status;

	return array(null, $results);
    }
}

/**
 * @package Rewrite
 * @subpackage UserInterface
 */
class SetupRewriteView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	$platform =& $gallery->getPlatform();
	$SetupRewrite = array();

	/* Make sure the module is not yet active */
	list ($ret, $rewrite) = GalleryCoreApi::loadPlugin('module', 'rewrite');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $isActive) = $rewrite->isActive();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($isActive) {
	    return array(GalleryCoreApi::error(ERROR_UNSUPPORTED_OPERATION, __FILE__, __LINE__,
		'rewrite module is active'), null);
	}

    	GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
	list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	if ($ret && !($ret->getErrorCode() & ERROR_MISSING_VALUE)) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if (isset($rewriteParser)) {
	    /* Error handeling for defined (numeric) REWRITE_STATUS_* errors */
	    $errors = array();
	    if (isset($form['error'])) {
		foreach ($form['error'] as $errno => $tmp) {
		    if (is_numeric($errno)) {
			list ($ret, $errors[]) = $rewriteParser->getErrorMessage($errno);
		    }
		}
	    }
	    if (!empty($errors)) {
		$SetupRewrite['errors'] = $errors;
	    }

	    $ret = $rewriteParser->loadTestResultsTemplate($template, $form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $SetupRewrite['needsConfiguration']) = $rewriteParser->needsConfiguration();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $ret = $rewriteParser->loadAdminParserTemplate($template, $form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	} else {
	    $SetupRewrite['bootstrap'] = 1;
	    $SetupRewrite['server'] = 'UNKNOWN';
	    $serverTag = GalleryUtilities::getServerVar('SERVER_SOFTWARE');
	    if (stristr($serverTag, 'apache') !== false) {
		$SetupRewrite['server'] = 'APACHE';
	    } else if (stristr($serverTag, 'iis') !== false) {
		$SetupRewrite['server'] = 'IIS';
	    }
	}

	if ($form['formName'] != 'SetupRewrite') {
	    $form['formName'] = 'SetupRewrite';
	}

	$template->setVariable('SetupRewrite', $SetupRewrite);
	$template->setVariable('controller', 'rewrite.SetupRewrite');
	return array(null,
		array('body' => 'modules/rewrite/templates/SetupRewrite.tpl'));
    }
}
?>
