<?php
/*
 * $RCSfile: DownloadRegistryFile.inc,v $
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
 * Generates the Windows registry file for web publishing.
 *
 * This is an immediate view which generates the registry file for Windows.
 * The information in the registry file informs Windows that there is a web service
 * which supports uploading media.
 *
 * @version $Revision: 1.6 $ $Date: 2006/01/13 15:39:38 $
 * @package PublishXp
 * @author Timothy Webb <tiwebb@cisco.com>
 */
class DownloadRegistryFileView extends GalleryView {
    /**
     * @see GalleryView::isImmediate()
     */
    function isImmediate() {
	return true;
    }

    /**
     * Renders the Windows registry file.
     *
     * Generates the registry file to be downloaded and installed on Windows
     * systems.  The gallery name and hostname are used as part of the web service
     * for publishing.
     *
     * @see GalleryView::renderImmediate
     */
    function renderImmediate($status, $error) {
	global $gallery;

	/* Get the domain name */
	$urlGenerator =& $gallery->getUrlGenerator();
	$domain = $urlGenerator->getHostName();

	/* Get the name of the gallery */
	list($ret, $rootId) = GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	list($ret, $root) = GalleryCoreApi::loadEntitiesById($rootId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$title = $root->getTitle();

	/* Setup the headers for the Registry file */
	header("Cache-control: private");
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: filename=install_registry.reg");

        GalleryCoreApi::requireOnce('modules/core/classes/GalleryTemplate.class');
        $template = new GalleryTemplate(dirname(__FILE__) . '/../..');
	$template->setVariable('l10Domain', 'modules_publishxp');

	$DownloadRegistryFile['title'] = $title;
	$DownloadRegistryFile['domain'] = $domain;
	$template->setVariable('DownloadRegistryFile', $DownloadRegistryFile);
	list ($ret, $html) =
	    $template->fetch('gallery:modules/publishxp/templates/DownloadRegistryFile.tpl');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Make sure we use Windows line endings */
	print preg_replace("/(?<!\r)\n/", "\r\n", $html);

	return null;
    }
}
?>
