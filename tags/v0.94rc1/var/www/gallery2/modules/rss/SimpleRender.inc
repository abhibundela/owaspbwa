<?php
/*
 * $RCSfile: SimpleRender.inc,v $
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
 * @version $Revision: 1.4 $ $Date: 2006/02/27 19:27:30 $
 * @package RSS
 * @author Jonatan Heyman <http://heyman.info>
 * @author Pierre-Luc Paour
 * @author Daniel Grund <http://www.photogrund.nl>
 */

GalleryCoreApi::requireOnce('modules/rss/classes/RssHelper.class');
GalleryCoreApi::requireOnce('modules/rss/classes/RssGenerator.class');
GalleryCoreApi::requireOnce('modules/rss/classes/RssMapHelper.class');
GalleryCoreApi::requireOnce('modules/rss/Render.inc');

/**
 * Render the Simple Feed
 *
 * @package RSS
 * @subpackage UserInterface
 */
class SimpleRenderView extends RenderView {
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

	if ($params['allowSimpleFeed'] != 1) {
	    $this->handleError($gallery->i18n('Feeds of that type are not allowed!'));
	    return null;
	}

	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$params['itemId'] = $item->getId();

	/* set feed type */
	if (GalleryUtilities::isA($item, 'GalleryAlbumItem')) {
	    $params['feedType'] = $params['sfAlbumType'];
	} elseif(GalleryUtilities::isA($item, 'GalleryDataItem')) {
	    $params['feedType'] = 'commentsPhoto';
	}

	$params['feedDate'] = $params['sfDate'];

	/* apply defaults */
	foreach (array('copyright' => 'defaultCopyright',
		      'version' => 'defaultVersion',
		      'count' => 'defaultCount',
		      'photosRecurseLimit' => 'sfPhotosRecurseLimit',
		      'ttl' => 'defaultTtl') as $name => $defaultName) {
	    $params[$name] = $params[$defaultName];
	}

	$params['language'] = 'en-us';
	$params['useImage'] = true;
	$params['useEnclosure'] = false;
	$params['useCloud'] = false;

	$ret = $this->continueRender($params);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }
}
?>
