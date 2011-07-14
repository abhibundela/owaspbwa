<?php
/*
 * $RCSfile: AdminRewrite.inc,v $
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
 * @version $Revision: 1.21 $ $Date: 2006/01/17 01:52:04 $
 * @author Douglas Cau <douglas@cau.se>
 */

/**
 * @package Rewrite
 * @subpackage UserInterface
 */
class AdminRewriteController extends GalleryController {

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest(&$form) {
	$error = $status = $seen = array();

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
	list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if (isset($form['action']['rules'])) {
	    $activeRules = array();
	    $mode = 'rules';

	    /* Build the activeRules array and check for emtpy / seen patterns */
	    if (isset($form['rules'])) {
		foreach (array_keys($form['rules']) as $moduleId) {
		    foreach (array_keys($form['rules'][$moduleId]) as $ruleId) {
			if (isset($form['rules'][$moduleId][$ruleId]['active'])) {
			    /* Empty or just '/' ? */
			    if (empty($form['rules'][$moduleId][$ruleId]['pattern']) ||
				    $form['rules'][$moduleId][$ruleId]['pattern'] == '/') {
				$error[] = 'form[error][empty][' . $moduleId . '][' . $ruleId . ']';
				break 2;
			    }

			    /* Dupe? */
			    if (isset($seen[$form['rules'][$moduleId][$ruleId]['pattern']])) {
				$error[] = 'form[error][dupe][' .
				    $form['rules'][$moduleId][$ruleId]['pattern'] . ']';
				break 2;
			    }

			    /* Activate the rule */
			    $pattern = $form['rules'][$moduleId][$ruleId]['pattern'];
			    if (substr($pattern, 0, 1) == '/') {
				$pattern = substr($pattern, 1);
			    }
			    $activeRules[$moduleId][$ruleId] = $pattern;
			    $seen[$pattern] = 1;
			}
		    }
		}
	    }

	    /* Save the active rules */
	    if (empty($error)) {
		list ($ret, $code, $errorId) = $rewriteParser->saveActiveRules($activeRules);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if ($code == REWRITE_STATUS_OK) {
		    $status['saved'] = 1;
		} else {
		    $errorString = 'form[error][' . $code . ']';
		    if (isset($errorId)) {
			foreach ($errorId as $errno) {
			    $errorString .= '[' . $errno. ']';
			}
		    }

		    $error[] = $errorString;
		}
	    }
	} else if (isset($form['action']['accessList'])) {
	    $mode = 'setup';

	    $accessList = array();
	    foreach ($form['accessList'] as $host) {
		if (!empty($host)) {
		    $accessList[] = $host;
		}
	    }

	    $allowEmptyReferer = false;
	    if (isset($form['allowEmptyReferer'])) {
		$allowEmptyReferer = true;
	    }

	    list ($ret, $code) = $rewriteParser->saveAccessList($accessList,
		$allowEmptyReferer);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($code == REWRITE_STATUS_OK) {
		$status['saved'] = 1;
	    } else {
		$error[] = 'form[error][' . $code . ']';
	    }
	} else if (isset($form['action']['adminParser'])) {
	    $mode = 'setup';

	    list ($ret, $error, $status) = $rewriteParser->handleAdminParserRequest($form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	} else if (isset($form['action']['testParser'])) {
	    $mode = 'test';

	    list ($ret, $error, $status) = $rewriteParser->handleTestResultsRequest($form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	} else if (isset($form['action']['refresh'])) {
	    $mode = 'test';
	}

	if (!empty($error)) {
	    $results = array('delegate' => array('view' => 'core.SiteAdmin',
						 'subView' => 'rewrite.AdminRewrite',
						 'mode' => $mode));
	} else {
	    $results = array('redirect' => array('view' => 'core.SiteAdmin',
						 'subView' => 'rewrite.AdminRewrite',
						 'mode' => $mode));
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
class AdminRewriteView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	$urlGenerator =& $gallery->getUrlGenerator();
	$AdminRewrite = array();

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
	list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$AdminRewrite['parserType'] = $rewriteParser->getParserType();
	$AdminRewrite['parserId'] = $rewriteParser->getParserId();

	$AdminRewrite['mode'] = GalleryUtilities::getRequestVariables('mode');
	if (empty($AdminRewrite['mode'])) {
	    $AdminRewrite['mode'] = 'rules';
	}

	/* Error handling for defined (numeric) REWRITE_STATUS_* errors */
	$errors = array();
	if (isset($form['error'])) {
	    foreach ($form['error'] as $errno => $tmp) {
		if (is_numeric($errno)) {
		    list ($ret, $errors[]) = $rewriteParser->getErrorMessage($errno);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}
	    }
	}
	if (!empty($errors)) {
	    $AdminRewrite['errors'] = $errors;
	}

	switch ($AdminRewrite['mode']) {
	case 'rules':
	    list($ret, $activeRules) = GalleryCoreApi::getPluginParameter(
		'module', 'rewrite', 'activeRules');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $activeRules = unserialize($activeRules);

	    /* Get all modules to walk through and get any rules it provieds */
	    list($ret, $modules) = GalleryCoreAPi::fetchPluginStatus('module');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    foreach ($modules as $moduleId => $status) {
		if (isset($status['active']) && $status['active']) {
		    list($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId);
		    if ($ret) {
			if ($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH) {
			    continue;
			}
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $AdminRewrite['modules'][$moduleId] = $module->translate($module->getName());

		    /* Walk through the rules this module provieds */
		    $rules = $module->getRewriteRules();
		    foreach ($rules as $ruleId => $rule) {
			/* Make sure this parser supports this kind of rule */
			if (!$rewriteParser->isValidRule($rule)) {
			    continue;
			}

		    	/* If we dont have old form data, populate the array with db info */
			if ($form['formName'] != 'AdminRewrite') {
			    $form['rules'][$moduleId][$ruleId]['pattern'] =
				isset($activeRules[$moduleId][$ruleId])
				    ? $activeRules[$moduleId][$ruleId]
				    : $rule['pattern'];

			    if (isset($activeRules[$moduleId][$ruleId])) {
				$form['rules'][$moduleId][$ruleId]['active'] = 1;
			    }
			}

			/* If a dupe rule is detected we need to know it's 'match' */
			$AdminRewrite['info'][$moduleId][$ruleId]['match'] = '';
			if (isset($rule['match'])) {
			    $AdminRewrite['info'][$moduleId][$ruleId]['match'] =
				join('|', $rule['match']);
			}

			/* Should we display an input box for users to edit this pattern? */
			if (isset($rule['locked'])) {
			    $AdminRewrite['info'][$moduleId][$ruleId]['locked'] = 1;
			}

			/* Get the comment, or the technical view name if there's no comment */
			if (isset($rule['comment'])) {
			    $AdminRewrite['info'][$moduleId][$ruleId]['comment'] = $rule['comment'];
			} else if (isset($rule['match'])) {
			    $AdminRewrite['info'][$moduleId][$ruleId]['comment'] =
				join('|', $rule['match']);
			} else {
			    $AdminRewrite['info'][$moduleId][$ruleId]['comment'] =
				$moduleId . '.' . $ruleId;
			}

			/* This module may provide extra keywords */
			$AdminRewrite['info'][$moduleId][$ruleId]['keywords'] =
			    isset($rule['keywords']) ? $rule['keywords'] : array();

			/* Get rule information */
			if (isset($rule['help'])) {
			    $AdminRewrite['info'][$moduleId][$ruleId]['help'] = $rule['help'];
			}
		    }
		}
	    }

	    $template->javascript('lib/javascript/BlockToggle.js');
	    break;

	case 'setup':
	    $AdminRewrite['serverName'] = $urlGenerator->getHostName();

	    if ($form['formName'] != 'AdminRewrite' &&
		    $AdminRewrite['parserType'] == 'preGallery') {

		list ($ret, $form['accessList']) = GalleryCoreApi::getPluginParameter(
		    'module', 'rewrite', 'accessList');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$form['accessList'] = unserialize($form['accessList']);

		list ($ret, $allowEmptyReferer) = GalleryCoreApi::getPluginParameter(
		    'module', 'rewrite', 'allowEmptyReferer');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($allowEmptyReferer) {
		    $form['allowEmptyReferer'] = 1;
		}
	    }

	    $ret = $rewriteParser->loadAdminParserTemplate($template, $form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    break;

	case 'test':
	    $ret = $rewriteParser->loadTestResultsTemplate($template, $form);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    break;
	}

	if ($form['formName'] != 'AdminRewrite') {
	    $form['formName'] = 'AdminRewrite';
	}

	$template->setVariable('AdminRewrite', $AdminRewrite);
	$template->setVariable('controller', 'rewrite.AdminRewrite');

	$template->javascript('lib/javascript/BlockToggle.js');

	return array(null,
	    array('body' => 'modules/rewrite/templates/AdminRewrite.tpl'));
    }
}
?>
