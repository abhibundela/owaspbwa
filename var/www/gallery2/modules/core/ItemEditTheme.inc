<?php
/*
 * $RCSfile: ItemEditTheme.inc,v $
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
 * @version $Revision: 1.8 $ $Date: 2006/02/06 03:36:42 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the changes users make to an album.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemEditTheme extends ItemEditPlugin {

    /**
     * @see ItemEditPlugin::handleRequest
     */
    function handleRequest($form, &$item, &$preferred) {
	global $gallery;

	list ($ret, $themeId) = GalleryCoreApi::fetchThemeId($item);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	}
	list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	}

	$status = null;
	$error = $errorMessage = array();

	if ($theme->isAdvancedSettings()) {
	    list ($ret, $error, $status) = $theme->handleSettingsRequest($form, $item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	    }
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
	}

	if (isset($form['action']['save']) && empty($error)) {
	    /* Validate the settings */
	    $validationErrors = $theme->validateSettings($form['key']);
	    if (empty($validationErrors)) {
		/* Get the item settings */
		list ($ret, $settings) = $theme->getSettings($item->getId());
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		}

		if (isset($form['changeInDescendents'])) {
		    list ($ret, $subAlbumIds) =
			GalleryCoreApi::fetchDescendentAlbumItemIds($item);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null, null, null);
		    }
		    array_unshift($subAlbumIds, $item->getId());
		} else {
		    $subAlbumIds = array($item->getId());
		}

		/*
		 * Any settings that are marked as "use global" should be removed from this item.
		 * Others should be updated in the database.  Note that we set the parameter even
		 * if it is the same as the original setting value, since we want to make sure
		 * that the value is stored per item, and the original setting value could have
		 * been from the global space.
		 */
		foreach ($settings as $setting) {
		    $key = $setting['key'];
		    if (!empty($form['useGlobal'][$key])) {
			$ret = $theme->removeParameter($key, $subAlbumIds);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			}
		    } else {
			if ($setting['type'] == 'checkbox') {
			    $value = isset($form['key'][$setting['key']]) ? 1 : 0;
			} else {
			    $value = $form['key'][$setting['key']];
			}

			if (!empty($setting['typeParams']['packType'])) {
			    list ($success, $value) = $theme->packSetting(
				$setting['typeParams']['packType'], $value);
			}

			$ret = $theme->setParameter($key, $value, $subAlbumIds);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null, null, null);
			}
		    }
		}

		$status = $module->translate('Successfully saved theme settings');
	    } else {
		foreach ($validationErrors as $validationKey => $validationMessage) {
		    $error[] = sprintf('form[error][key][%s][invalid]', $validationKey);
		    $errorMessage[$validationKey] = $validationMessage;
		}
		GalleryUtilities::putRequestVariable('form[errorMessage]', $errorMessage);
	    }
	} else if (isset($form['action']['undo'])) {
	    /* Do nothing and we'll be redirected back to the same page which resets the form */
	}

	return array(null, $error, $status, false);
    }

    /**
     * @see ItemEditPlugin::loadTemplate
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	global $gallery;
	$ItemEditTheme = array();

	list ($ret, $themeId) = GalleryCoreApi::fetchThemeId($item);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}
	list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($theme->isAdvancedSettings()) {
	    list ($ret, $tpl) = $theme->loadSettingsTemplate($template, $form, $item->getId());
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null, null);
	    }
	    $ItemEditTheme['customTemplate'] = $tpl;
	}

	/* Get the per-item settings (global + item) */
	list ($ret, $settings) = $theme->getSettings($item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	/* Fetch the global-only params */
	list ($ret, $globalParams) =
	    GalleryCoreApi::fetchAllPluginParameters('theme', $theme->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	for ($i = 0; $i < count($settings); $i++) {
	    if (!empty($settings[$i]['typeParams']['packType'])) {
		$packType = $settings[$i]['typeParams']['packType'];
		$key = $settings[$i]['key'];

		list ($success, $settings[$i]['value']) =
		    $theme->unpackSetting($packType, $settings[$i]['value']);
		list ($success, $globalParams[$key]) =
		    $theme->unpackSetting($packType, $globalParams[$key]);
	    }
	}

	/* Fetch the item-only params */
	list ($ret, $itemParams) =
	    GalleryCoreApi::fetchAllPluginParameters('theme', $theme->getId(), $item->getId());
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	if ($form['formName'] != 'ItemEditTheme') {
	    $form['formName'] = 'ItemEditTheme';
	    foreach ($settings as $setting) {
		$key = $setting['key'];
		$form['key'][$key] = $setting['value'];

		/* If the key doesn't exist in the item params then it's a global setting */
		$form['useGlobal'][$key] = isset($itemParams[$key]) ? 0 : 1;
	    }
	} else {
	    /* Make sure all checkbox values are defined */
	    foreach ($settings as $setting) {
		$key = $setting['key'];
		if (!isset($form['useGlobal'][$key])) {
		    $form['useGlobal'][$key] = 0;
		}
	    }
	}
	$ItemEditTheme['settings'] = $settings;
	$ItemEditTheme['globalParams'] = $globalParams;

	list ($ret, $ItemEditTheme['availableBlocks']) = $this->loadAvailableBlocks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null, null);
	}

	$ItemEditTheme['theme']['name'] = $theme->getName();
	$ItemEditTheme['theme']['l10Domain'] = $theme->getL10Domain();

	$template->javascript('lib/javascript/BlockSelectWidget.js');
	$template->setVariable('ItemEditTheme', $ItemEditTheme);
	$template->setVariable('controller', 'core.ItemEditTheme');
	return array(null,
		     'modules/core/templates/ItemEditTheme.tpl', 'modules_core');
    }

    /**
     * @see ItemEditPlugin::isSupported
     */
    function isSupported($item, $thumbnail) {
	return (GalleryUtilities::isA($item, 'GalleryAlbumItem'));
    }

    /**
     * @see ItemEditPlugin::getTitle
     */
    function getTitle() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('Theme'));
    }

    /**
     * @see AdminThemesView::loadAvailableBlocks
     */
    function loadAvailableBlocks() {
	/*
	 * For now, delegate to AdminThemes::loadAvailableBlocks.  At some point
	 * in the future we can make this a utility  method.
	 */
	GalleryCoreApi::requireOnce('modules/core/AdminThemes.inc');
	list ($ret, $blocks) = AdminThemesView::loadAvailableBlocks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $blocks);
    }
}
?>
