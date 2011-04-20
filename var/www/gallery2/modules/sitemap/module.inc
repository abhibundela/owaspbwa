<?php
/*
 * $RCSfile: module.inc,v $
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
 * Module that creates a Google Sitemap for your Gallery2 install.
 *
 * @version $Revision: 1.5 $ $Date: 2006/03/22 03:52:35 $
 * @package Sitemap
 * @author Bharat Mediratta <bharat@menalto.com>
 */
class SitemapModule extends GalleryModule {

    function SitemapModule() {
	global $gallery;
	$this->setId('sitemap');
	$this->setName($gallery->i18n('Sitemap'));
	$this->setDescription($gallery->i18n('Generate a Google Sitemap for your Gallery'));
	$this->setVersion('1.0.0');
	$this->setGroup('export', $gallery->i18n('Export'));
	$this->setCallbacks('getSiteAdminViews');
	$this->setRequiredCoreApi(array(7, 0));
	$this->setRequiredModuleApi(array(3, 0));
    }

    /**
     * @see GalleryModule::getSiteAdminViews();
     */
    function getSiteAdminViews() {
	$data = array(array('name' => $this->translate('Google Sitemap'),
			    'view' => 'sitemap.AdminSitemap'));
	return array(null, $data);
    }

    /**
     * @see GalleryModule::getRewriteRules
     */
    function getRewriteRules() {
	$rules = array();

	$rule = array();
	$rule['match'] = array('view' => 'sitemap.Sitemap');
	$rule['pattern'] = 'sitemap';
	$rule['comment'] = $this->translate('Download Sitemap');
	$rules[] = $rule;

	return $rules;
    }
}
?>
