<?php
/*
 * $RCSfile: CSS.inc,v $
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
 * @version $Revision: 1.5 $ $Date: 2006/01/10 04:40:23 $
 * @package ImageFrame
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * Display CSS needed to render image frames.
 *
 * @package ImageFrame
 * @subpackage UserInterface
 */
class CSSView extends GalleryView {
    /**
     * @see GalleryView::isImmediate()
     */
    function isImmediate() {
	return true;
    }

    /**
     * @see GalleryView::isAllowedInEmbedOnly()
     */
    function isAllowedInEmbedOnly() {
	return true;
    }

    /**
     * @see GalleryView::shouldSaveSession()
     */
    function shouldSaveSession() {
	return false;
    }

    /**
     * @see GalleryView::renderImmediate
     */
    function renderImmediate($status, $error) {
	$frameList = GalleryUtilities::getRequestVariables('frames');
	if (empty($frameList)) {
	    return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
	}
	$frameList = explode('|', $frameList);

	if (function_exists('getallheaders')) {
	    $headers = getallheaders();
	    if (isset($headers['If-modified-since'])) {
		$headers['If-Modified-Since'] = $headers['If-modified-since'];
	    }
	    if (isset($headers['If-Modified-Since']) &&
		    ($when = strtotime($headers['If-Modified-Since'])) > 0) {
		/* Check date on frame.inc files to determine if we should resend the CSS */
		global $gallery;
		$platform =& $gallery->getPlatform();
		foreach ($frameList as $frameId) {
		    $inc = dirname(__FILE__) . "/frames/$frameId/frame.inc";
		    if ($platform->file_exists($inc) && ($stat = $platform->stat($inc)) &&
			    $stat[9] > $when) {
			$new = true;
			break;
		    }
		}
		if (!isset($new)) {
		    header('HTTP/1.x 304 Not Modified');
		    return null;
		}
	    }
	}

	list ($ret, $imageframe) = GalleryCoreApi::newFactoryInstance('ImageFrameInterface_1_1');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	if (!isset($imageframe)) {
	    return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryTemplate.class');
	$template = new GalleryTemplate(dirname(dirname(dirname(__FILE__))));
	$template->setVariable('l10Domain', 'modules_imageframe');

	$ret = $imageframe->init($template, $frameList);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	header('Content-type: text/css');
	header('Last-Modified: ' . GalleryUtilities::getHttpDate());
	header('Expires: ' . GalleryUtilities::getHttpDate(time() + 2592000));

	$ret = $template->display('gallery:modules/imageframe/templates/ImageFrameStyle.tpl');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }
}
?>
