<?php
/*
 * $RCSfile: AdminDcraw.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/01/17 01:52:01 $
 * @package Dcraw
 * @author Bharat Mediratta <bharat@menalto.com>
 */

GalleryCoreApi::requireOnce('modules/dcraw/classes/DcrawToolkitHelper.class');

/**
 * @package Dcraw
 * @subpackage UserInterface
 */
class AdminDcrawController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$error = $status = array();
	if (isset($form['action']['save'])) {
	    if (empty($form['path'])) {
		$error[] = 'form[error][path][missing]';
	    } else {
		$form['path'] = trim($form['path']);

		list ($ret, $testResults, $version) = DcrawToolkitHelper::testBinary($form['path']);
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_BAD_PATH) {
			$error[] = 'form[error][path][badPath]';
		    } else {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		} else {
		    $failCount = 0;
		    foreach ($testResults as $testResult) {
			/* All tests should work, else this path is not a valid one */
			if (!$testResult['success']) {
			    $failCount++;
			}
		    }

		    if ($failCount > 0) {
			$error[] = 'form[error][path][testError]';
		    }
		}
	    }

	    if (empty($error)) {
		foreach (array('path' => $form['path'],
			       'version' => $version)
			 as $key => $value) {
		    $ret = GalleryCoreApi::setPluginParameter('module', 'dcraw', $key, $value);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		}

		list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'dcraw');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		list ($ret, $isActive) = $module->isActive();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$redirect['view'] = 'core.SiteAdmin';
		if ($isActive) {
		    $redirect['subView'] = 'dcraw.AdminDcraw';
		    $status['saved'] = 1;
		} else {
		    $redirect['subView'] = 'core.AdminModules';
		    $status['configured'] = 'dcraw';
		}
	    }
	} else if (isset($form['action']['reset'])) {
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'dcraw.AdminDcraw';
	} else if (isset($form['action']['cancel'])) {
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'core.AdminModules';
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.SiteAdmin';
	    $results['delegate']['subView'] = 'dcraw.AdminDcraw';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * @package Dcraw
 * @subpackage UserInterface
 */
class AdminDcrawView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load our default values if we didn't just come from this form. */
	if ($form['formName'] != 'AdminDcraw') {
	    $form['formName'] = 'AdminDcraw';

	    list ($ret, $form['path']) =
		GalleryCoreApi::getPluginParameter('module', 'dcraw', 'path');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	}

	$form['path'] = trim($form['path']);

	$tests = array();
	$debugSnippet = '';
	$failCount = 0;
	if (isset($form['action']['test'])) {
	    if (!empty($form['path'])) {
		$gallery->startRecordingDebugSnippet();
		list ($ret, $testResults) = DcrawToolkitHelper::testBinary($form['path']);
		$debugSnippet = $gallery->stopRecordingDebugSnippet();

		$success = true;
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_BAD_PATH) {
			$testResults = array();
			$form['error']['path']['bad'] = 1;
		    } else {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		} else {
		    $success = true;
		    foreach ($testResults as $testResult) {
			if (!$testResult['success']) {
			    $success = false;
			    $failCount++;
			}
			$tests[] = $testResult;
		    }
		    if ($success) {
		    }
		}
	    } else {
		$form['error']['path']['missing'] = 1;
	    }
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'dcraw');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $isActive) = $module->isActive();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$AdminDcraw = array();
	$AdminDcraw['tests'] = $tests;
	$AdminDcraw['debugSnippet'] = $debugSnippet;
	$AdminDcraw['failCount'] = $failCount;
	$AdminDcraw['isConfigure'] = !$isActive;

	if ($failCount > 0) {
	    $template->javascript('lib/javascript/BlockToggle.js');
	}

	$template->setVariable('AdminDcraw', $AdminDcraw);
	$template->setVariable('controller', 'dcraw.AdminDcraw');

	return array(null,
		     array('body' => 'modules/dcraw/templates/AdminDcraw.tpl'));
    }
}
?>
