<?php
/*
 * $RCSfile: AdminSitemap.inc,v $
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
 * @version $Revision: 1.3 $ $Date: 2006/01/17 01:52:04 $
 * @package Sitemap
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * @package Sitemap
 * @subpackage UserInterface
 */
class AdminSitemapView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $moduleStatus) = GalleryCoreApi::fetchPluginStatus('module');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$template->setVariable('AdminSitemap',
		array('canRewrite' => !empty($moduleStatus['rewrite']['active'])));

	return array(null,
		     array('body' => 'modules/sitemap/templates/AdminSitemap.tpl'));
    }
}
?>
