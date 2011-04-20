<?php
/*
 * $RCSfile: Render.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/03/06 23:10:41 $
 * @package RSS
 * @author Jonatan Heyman <http://heyman.info>
 * @author Pierre-Luc Paour
 * @author Daniel Grund <http://www.photogrund.nl>
 */

GalleryCoreApi::requireOnce('modules/rss/classes/RssHelper.class');
GalleryCoreApi::requireOnce('modules/rss/classes/RssGenerator.class');
GalleryCoreApi::requireOnce('modules/rss/classes/RssMapHelper.class');

/**
 * Render the Feed
 *
 * @package RSS
 * @subpackage UserInterface
 */
class RenderView extends GalleryView {
    /**
     * @see GalleryView::isImmediate
     */
    function isImmediate() {
	return true;
    }

    /**
     * @see GalleryView::renderImmediate
     */
    function renderImmediate($status, $error) {
	global $gallery;

	/* get all parameters */
	list ($ret, $params) = GalleryCoreApi::fetchAllPluginParameters('module', 'rss');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* retrieve request variables */
	$name = GalleryUtilities::getRequestVariables('name');

	/* we're a configurable feed, load the parameters */
	list ($ret, $params1) = RssMapHelper::fetchFeed($name);
	if ($ret) {
	    $this->handleError($gallery->i18n('This feed no longer exists!'));
	    return null;
	}

	$params = array_merge($params, $params1);
	$feedType = $params['feedType'];

	if (($feedType == 'album' && $params['allowAlbums'] != 1)
		|| ($feedType == 'photos' && $params['allowPhotos'] != 1)
		|| ($feedType == 'photosRecurse' && $params['allowPhotosRecurse'] != 1)
		|| ($feedType == 'commentsAlbum' && $params['allowCommentsAlbum'] != 1)
		|| ($feedType == 'commentsPhoto' && $params['allowCommentsPhoto'] != 1)) {
	    $this->handleError($gallery->i18n('Feeds of that type are not allowed!'));
	    return null;
	}

	$ret = $this->continueRender($params);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    function continueRender($params) {
	global $gallery;

	/* check permissions */
	list ($ret, $perm) = GalleryCoreApi::hasItemPermission($params['itemId'], 'core.view');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	if (!$perm) {
	    $this->handleError($gallery->i18n('Not permitted to view this item'));
	    return null;
	}

	/* check render type */
	$feedType = $params['feedType'];
	if ($feedType != 'album' && $feedType != 'photos'  && $feedType != 'photosRecurse' &&
		$feedType != 'commentsAlbum' && $feedType != 'commentsPhoto') {
	    $this->handleError($gallery->i18n('Incorrect feed type!'));
	    return null;
	}

	/* check if we comments are active */
	if ($params['feedType'] == 'commentsAlbum' || $params['feedType'] == 'commentsPhoto') {
	    /* make sure that the comments module is active */
	    list ($ret, $modulestatus) = GalleryCoreApi::fetchPluginStatus('module');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if ($modulestatus['comment']['active'] != '1') {
		$this->handleError($gallery->i18n(
			'Comments module is not active or comment feeds are not allowed!'));
		return null;
	    }

	    /* check the permissions */
	    list ($ret, $perm) =
		GalleryCoreApi::hasItemPermission($params['itemId'], 'comment.view');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (!$perm) {
		$this->handleError($gallery->i18n('Not permitted to view comments for this item'));
		return null;
	    }
	}

	/* check if count is set, if not, retrieve default values */
	$maxcount = $params['maxCount'];
	$count = $params['count'];
	if (empty($count)) {
	    $count = $params['defaultCount'];
	}
	if ($count > $maxcount) {
	    $count = $maxcount;
	}
	$params['count'] = $count;

	/* create an instance of the rss data generator */
	$generator = new RssGenerator();

	$ret = RssHelper::getFeed($generator, $params['itemId'], $params);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$generator->sliceItems($count);
	$phpVm = $gallery->getPhpVm();
	if (!$phpVm->headers_sent()) {
	    $phpVm->header('Content-type: text/xml; charset=UTF-8');
	}

	print($generator->generate($params['version']));
	return null;
    }

    function handleError($message) {
	header('HTTP/1.0 500 Internal Server Error');

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'rss');
	if ($ret) {
	    /* swallow the error and just print the untranslated message */
	    print($message);
	} else {
	    print($module->translate($message));
	}
    }
}
?>
