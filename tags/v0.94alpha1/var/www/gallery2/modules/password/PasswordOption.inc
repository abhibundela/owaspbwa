<?php
/*
 * $RCSfile: PasswordOption.inc,v $
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
 * @version $Revision: 1.1 $ $Date: 2006/01/27 19:53:15 $
 * @package Password
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 * @author Jess Martin <jmartin@cs.unc.edu>
 */

/**
 * ItemEditOption for adding password protection to an item or album
 *
 * @package Password
 * @subpackage UserInterface
 */
class PasswordOption extends ItemEditOption {

    /**
     * @see ItemEditOption::isAppropriate
     */
    function isAppropriate($item, $thumbnail) {
	list ($ret, $canChange) =
	    GalleryCoreApi::hasItemPermission($item->getId(), 'core.changePermissions');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	return array(null, $canChange);
    }

    /**
     * @see ItemEditOption::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	$form['PasswordOption']['hasPassword'] = $item->hasOnLoadHandler('Password');
	$form['PasswordOption']['isAlbum'] = $item->getCanContainChildren();
	return array(null, 'modules/password/templates/PasswordOption.tpl', 'modules_password');
    }

    /**
     * @see ItemEditOption::handleRequestAfterEdit
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	$error = $warning = array();

	$ret = GalleryCoreApi::assertHasItemPermission($item->getId(), 'core.changePermissions');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if (isset($form['PasswordOption']['remove']) && $form['PasswordOption']['remove']) {
	    GalleryCoreApi::requireOnce('modules/password/classes/PasswordHelper.class');
	    $ret = PasswordHelper::removePassword($item);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	} else if (!empty($form['PasswordOption']['password1'])
		|| !empty($form['PasswordOption']['password2'])) {
	    if ($form['PasswordOption']['password1'] == $form['PasswordOption']['password2']) {
		GalleryCoreApi::requireOnce('modules/password/classes/PasswordHelper.class');
		$ret = PasswordHelper::setPassword($item,
			GalleryUtilities::htmlEntityDecode($form['PasswordOption']['password1']));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null);
		}
	    } else {
		/* Throw an error because both passwords do not match */
		$error[] = 'form[error][PasswordOption][mismatch]';
	    }
	}

	return array(null, $error, $warning);
    }
}
?>
