<?php
/*
 * $RCSfile: MimeAdmin.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/01/17 01:52:02 $
 * @package Mime
 * @author Felix Rabinovich <Felix@Rabinovich.org>
 */

/**
 * @package Mime
 * @subpackage Administration
 */
class MimeAdminController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
        $ret = GalleryCoreApi::assertUserIsSiteAdministrator();
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

	$status = array();
        if (isset($form['action']['delete'])) {
	    $mimeType = $form['action']['delete'];
            $ret = GalleryCoreApi::removeMimeType(
                array('mimeType' => $mimeType));
            if ($ret) {
                return array($ret->wrap(__FILE__, __LINE__), null);
            }
	    $status['deleted'] = $mimeType;

            $results['redirect'] = array('view' => 'core.SiteAdmin',
                'subView' => 'mime.MimeAdmin');
        } elseif (isset($form['action']['addNew'])) {
            $results['redirect'] = array('view' => 'core.SiteAdmin',
                'subView' => 'mime.MimeEdit');
        }

        $results['status'] = $status;
        $results['error'] = array();

        return array(null, $results);
    }
}

/**
 * @package Mime
 * @subpackage Administration
 */
class MimeAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
        $ret = GalleryCoreApi::assertUserIsSiteAdministrator();
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

	if ($form['formName'] != 'MimeAdmin') {
	    $form['formName'] = 'MimeAdmin';
	}
        GalleryCoreApi::requireOnce('modules/mime/classes/MimeHelper.class');
        list ($ret, $typeMap) = MimeHelper::getMimeTypeMap();
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

        $MimeAdmin = array();
        foreach(array_keys($typeMap['ext']) as $mimeType) {
            $ext = implode(', ', $typeMap['ext'][$mimeType]);
            $MimeAdmin[$mimeType]['ext'] = $ext;
            $MimeAdmin[$mimeType]['viewable'] = $typeMap['viewable'][$mimeType];
        }

        $template->setVariable('controller', 'mime.MimeAdmin');
        $template->setVariable('MimeAdmin', $MimeAdmin);
        return array(null,
             array('body' => 'modules/mime/templates/MimeAdmin.tpl'));
    }
}
?>
