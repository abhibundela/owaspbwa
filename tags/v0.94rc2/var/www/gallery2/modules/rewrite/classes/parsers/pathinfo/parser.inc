<?php
/*
 * $RCSfile: parser.inc,v $
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
 * @version $Revision: 1.2 $ $Date: 2006/01/10 04:42:18 $
 * @author Douglas Cau <douglas@cau.se>
 */

/* Required class */
GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteParser.class');

/* Status code used by the PHP Path Info parser */
define('REWRITE_STATUS_NO_PATH_INFO', 21);

/**
 * This URL Rewrite parser provides PHP Path Info support for short URLs.
 *
 * @package Rewrite
 * @subpackage Parsers
 */
class PathInfoParser extends RewriteParser {

    function PathInfoParser() {
	$this->_setParserId('pathinfo');
	$this->_setParserType('inGallery');
	$this->_setUrlGeneratorId('PathInfoUrlGenerator');
    }

    /**
     * @see RewriteParser::saveActiveRules
     */
    function saveActiveRules($activeRules=null, $rewriteModule=null) {
	GalleryCoreApi::requireOnce(
	    'modules/rewrite/classes/parsers/pathinfo/PathInfoHelper.class');
	return PathInfoHelper::saveActiveRules($this, $activeRules, $rewriteModule);
    }

    /**
     * @see RewriteParser::needsConfiguration
     */
    function needsConfiguration() {
    	GalleryCoreApi::requireOnce(
	    'modules/rewrite/classes/parsers/pathinfo/PathInfoHelper.class');

	list ($ret, $code) = PathInfoHelper::checkPathInfo();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $code != REWRITE_STATUS_OK);
    }

    /**
     * @see RewriteParser::loadTestResultsTemplate
     */
    function loadTestResultsTemplate(&$template, &$form) {
    	GalleryCoreApi::requireOnce(
	    'modules/rewrite/classes/parsers/pathinfo/PathInfoHelper.class');
	return PathInfoHelper::loadTestResultsTemplate($template, $form);
    }

    /**
     * @see RewriteParser::handleTestResultsRequest
     */
    function handleTestResultsRequest($form) {
	$error = $status = array();

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if (isset($form['force']['test'])) {
	    $ret = GalleryCoreApi::setPluginParameter('module', 'rewrite', 'pathinfo.forced', '1');
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	}

	$status['saved'] = 1;
	return array(null, $error, $status);
    }
}
?>
