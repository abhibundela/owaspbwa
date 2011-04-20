<?php
/*
 * $RCSfile: Sitemap.inc,v $
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
 * @version $Revision: 1.3 $ $Date: 2006/01/13 03:39:51 $
 * @package Sitemap
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Create an appropriate Google Sitemap for this site.
 *
 * @package Sitemap
 * @subpackage UserInterface
 */
class SitemapView extends GalleryView {

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

 	list ($ret, $rootId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
 	    return $ret->wrap(__FILE__, __LINE__);
 	}

	$ret = $this->renderSitemap($rootId);
 	if ($ret) {
 	    return $ret->wrap(__FILE__, __LINE__);
 	}
    }

    /**
     * Output a site map rooted at the given id
     *
     * @param the root id
     * @return object GalleryStatus a status code
     */
    function renderSitemap($rootId) {
	global $gallery;
	$urlGenerator =& $gallery->getUrlGenerator();
	$phpVm = $gallery->getPhpVm();

	/*
	 * Ideas:
	 *  - Calculate priority by using a percentage of item view count to max view counts
	 */

	/*
	 * Don't use a template for this as we may wind up trying to buffer
	 * way too much data
	 */
	$phpVm->header('Content-type: application/xhtml+xml');
	print '<?xml version=\'1.0\' encoding=\'UTF-8\'?>';
	print "\n";
	print '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" ';
	print 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
	print 'xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 ';
	print 'http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">';
	print "\n";

 	$queue = array($rootId);
	while (!empty($queue)) {
	    $currentId = array_shift($queue);
	    list ($ret, $newIds) =
		GalleryCoreApi::fetchChildItemIdsWithPermission($currentId, 'core.view');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $queue = array_merge($queue, $newIds);

	    /* TODO: load entities in chunks */
	    list ($ret, $entity) = GalleryCoreApi::loadEntitiesById($currentId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    printf("<url><loc>%s</loc><lastmod>%s</lastmod></url>\n",
 		   $urlGenerator->generateUrl(
 		       array('view' => 'core.ShowItem', 'itemId' => $currentId),
 		       array('forceSessionId' => false, 'forceFullUrl' => true)),
		   substr_replace(
		       gmstrftime("%Y-%m-%dT%H:%M:%S%z", $entity->getModificationTimestamp()),
		       ":", -2, 0));
	}
	print '</urlset>';

	return null;
    }
}
?>
