<?php
/*
 * $RCSfile: AdminThemes.inc,v $
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
 * @version $Revision: 1.13 $ $Date: 2006/03/18 03:40:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle an administration request for a module
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminThemesController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = $errorMessage = array();

	$themeId = GalleryUtilities::getRequestVariables('themeId');
	$theme = null;
	if (!empty($themeId) && !empty($form['action'])) {
	    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId, false, true);
	    if ($ret) {
		if ($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH &&
		    isset($form['action']['upgrade'])) {
		    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId, true);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		} else {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }

	    list ($ret, $isActive) = $theme->isActive();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if (isset($form['action']['activate'])) {
		/* Only activate if we're not already active! */
		if (!$isActive) {
		    list ($ret, $redirect) = $theme->activate();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $status['activated'] = $theme->translate($theme->getName());
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'config';
	    } else if (isset($form['action']['install'])) {
		$ret = $theme->installOrUpgrade();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'config';
		$status['installed'] = $theme->translate($theme->getName());
	    } else if (isset($form['action']['upgrade'])) {
		$ret = $theme->installOrUpgrade();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'config';
		$status['upgraded'] = $theme->translate($theme->getName());
	    } else if (isset($form['action']['uninstall'])) {
		if (!$isActive) {
		    $ret = $theme->uninstall();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $status['uninstalled'] = $theme->translate($theme->getName());
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'config';
	    } else if (isset($form['action']['deactivate'])) {
		list ($ret, $defaultThemeId) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'default.theme');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Only deactivate if we're not already deactive and not the default! */
		if ($isActive && $theme->getId() != $defaultThemeId) {
		    list ($ret, $redirect) = $theme->deactivate();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    $status['deactivated'] = $theme->translate($theme->getName());
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'config';
	    } else if (isset($form['action']['saveTheme'])) {
		/* Get all the possible settings */
		list ($ret, $settings) = $theme->getSettings(null);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Validate the settings */
		$validationErrors = $theme->validateSettings($form['key']);
		if (empty($validationErrors)) {
		    /* Save any settings that have changed */
		    foreach ($settings as $setting) {
			if ($setting['type'] == 'checkbox') {
			    $value = isset($form['key'][$setting['key']]) ? 1 : 0;
			} else {
			    $value = $form['key'][$setting['key']];
			}

			if (!empty($setting['typeParams']['packType'])) {
			    list ($success, $value) = $theme->packSetting(
				$setting['typeParams']['packType'], $value);
			}

			if ($setting['value'] !== $value) {
			    $ret = $theme->setParameter($setting['key'], $value);
			    if ($ret) {
				return array($ret->wrap(__FILE__, __LINE__), null);
			    }
			}
		    }

		    if ($theme->isAdvancedSettings()) {
			list ($ret, $error, $statusKey) = $theme->handleSettingsRequest($form);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
			if (empty($error)) {
			    $status[$statusKey] = $theme->translate($theme->getName());
			}
		    }

		    if (empty($error)) {
			/* Redirect upon success */
			$redirect['view'] = 'core.SiteAdmin';
			$redirect['subView'] = 'core.AdminThemes';
			$redirect['mode'] = 'editTheme';
			$redirect['themeId'] = $themeId;
			$status['savedTheme'] = $theme->translate($theme->getName());
		    }
		} else {
		    foreach ($validationErrors as $validationKey => $validationMessage) {
			$error[] = sprintf('form[error][key][%s][invalid]', $validationKey);
			$errorMessage[$validationKey] = $validationMessage;
		    }
		    GalleryUtilities::putRequestVariable('form[errorMessage]', $errorMessage);
		}
	    } else if (isset($form['action']['undoTheme'])) {
		/* Redirect back to ourselves to reset the form */
		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'editTheme';
		$redirect['themeId'] = $themeId;
		$status['restoredTheme'] = $theme->translate($theme->getName());
	    }
	} else {  /* no theme id provided */
	    if (isset($form['action']['saveDefaults'])) {
		foreach (array('default.orderBy',
			       'default.orderDirection',
			       'default.theme',
			       'default.newAlbumsUseDefaults')
			 as $key) {
		    list ($outer, $inner) = explode('.', $key);
		    if (isset($form[$outer][$inner])) {
			$value = $form[$outer][$inner];
			if ($key == 'default.orderBy' && !empty($form['default']['presort'])) {
			    $value = $form['default']['presort'] . '|' . $value;
			}
			$ret = GalleryCoreApi::setPluginParameter('module', 'core', $key, $value);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    }
		}

		/* Redirect upon success */
		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminThemes';
		$redirect['mode'] = 'defaults';
		$status['savedDefaults'] = 1;
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'core.SiteAdmin';
	    $results['delegate']['subView'] = 'core.AdminThemes';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will show administration possibilities for available themes
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class AdminThemesView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($themeId, $mode) = GalleryUtilities::getRequestVariables('themeId', 'mode');
	if (empty($mode)) {
	    $mode = 'defaults';
	}

	/* Load the theme list */
	list ($ret, $themes) = GalleryCoreApi::fetchPluginStatus('theme', true);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/*
	 * It's possible that we have some out-of-date themes which haven't
	 * been deactivated yet, since the deactivation only occurs when we try
	 * to load the theme.  Load all the active themes now to force the
	 * version check, then reload the theme status when we're done to pick
	 * up any changes we trigger.
	 */
	foreach ($themes as $id => $status) {
	    if (!empty($status['active'])) {
		list($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $id, false, true);
		if ($ret && !($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH)) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}

	/* Reload the theme list, which may now be updated because of obsolete modules. */
	list ($ret, $themes) = GalleryCoreApi::fetchPluginStatus('theme', true);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/*
	 * Figure out how many active themes we have (because we don't allow
	 * deactivating the last one.
	 */
	$activeCount = 0;
	foreach (array_keys($themes) as $id) {
	    $activeCount += !empty($themes[$id]['active']);
	}

	list ($ret, $defaultThemeId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'default.theme');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Transform it to include all the theme data that we want to display */
	$themeList = array();
	foreach ($themes as $id => $status) {

	    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $id, true);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Detect and remove old themes which not even had a setId() in their constructor */
	    $getId = $theme->getId();
	    if (empty($getId)) {
		unset($themes[$id]);
		continue;
	    }

	    $entry = array();
	    $entry['name'] = $theme->translate($theme->getName());
	    $entry['active'] = !empty($status['active']);
	    $entry['l10Domain'] = $theme->getL10Domain();
	    $entry['action'] = array();
	    $coreApiCompatible = GalleryUtilities::isCompatibleWithApi(
		$theme->getRequiredCoreApi(), GalleryCoreApi::getApiVersion());
	    $themeApiCompatible = GalleryUtilities::isCompatibleWithApi(
		$theme->getRequiredThemeApi(), GalleryTheme::getApiVersion());
	    if ($coreApiCompatible && $themeApiCompatible) {
		if (empty($status['active'])) {
		    $entry['state'] = 'inactive';

		    /*
		     * If the database versions doesn't match the theme
		     * version, we need to get the user to install the theme.
		     */
		    if ($status['version'] != $theme->getVersion()) {
			if (empty($status['version'])) {
			    $entry['action'][] =
				array('text' => $module->translate('install'),
				      'params' => array('controller' => 'core.AdminThemes',
							'themeId' => $id,
							'form[action][install]' => 1));
			    $entry['state'] = 'install';
			} else {
			    $entry['action'][] =
				array('text' => $module->translate('upgrade'),
				      'params' => array('controller' => 'core.AdminThemes',
							'themeId' => $id,
							'form[action][upgrade]' => 1));
			    $entry['state'] = 'upgrade';
			}
		    } else {
			$entry['action'][] =
			    array('text' => $module->translate('activate'),
				  'params' => array('controller' => 'core.AdminThemes',
						    'themeId' => $id,
						    'form[action][activate]' => 1));
			$entry['action'][] =
			    array('text' => $module->translate('uninstall'),
				  'params' => array('controller' => 'core.AdminThemes',
						    'themeId' => $id,
						    'form[action][uninstall]' => 1));
		    }
		} else {
		    $entry['state'] = 'active';
		    if ($id != $defaultThemeId) {
			$entry['action'][] =
			    array('text' => $module->translate('deactivate'),
				  'params' => array('controller' => 'core.AdminThemes',
						    'themeId' => $id,
						    'form[action][deactivate]' => 1));
		    }
		    if ($status['version'] != $theme->getVersion()) {
			$entry['action'][] =
			    array('text' => $module->translate('upgrade'),
				  'params' => array('controller' => 'core.AdminThemes',
						    'themeId' => $id,
						    'form[action][upgrade]' => 1));
			$entry['state'] = 'upgrade';
		    }
		}
	    } else {
		$entry['state'] = 'incompatible';
		$entry['api']['required']['theme'] = join('.', $theme->getRequiredThemeApi());
		$entry['api']['required']['core'] = join('.', $theme->getRequiredCoreApi());
		$entry['api']['provided']['theme'] = join('.', GalleryTheme::getApiVersion());
		$entry['api']['provided']['core'] = join('.', GalleryCoreApi::getApiVersion());
	    }

	    $entry['description'] = $theme->getDescription();
	    $entry['version'] = $theme->getVersion();
	    $entry['installedVersion'] = isset($status['version']) ? $status['version'] : null;
	    $themes[$id] = $entry;

	    if (!empty($status['active'])) {
		$themeList[$id] = $theme->translate($theme->getName());
	    }
	}

	$AdminThemes = array();
	switch($mode) {
	case 'defaults':
	    /* Load up the stored defaults */
	    foreach (array('default.orderBy',
			   'default.orderDirection',
			   'default.theme',
			   'default.theme',
			   'default.newAlbumsUseDefaults')
		     as $key) {

		list ($ret, $value) =
		    GalleryCoreApi::getPluginParameter('module', 'core', $key);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if ($key == 'default.orderBy') {
		    $tmp = explode('|', $value, 2);
		    if (count($tmp) == 2) {
			$value = $tmp[1];
			$form['default']['presort'] = $tmp[0];
		    } else {
			$form['default']['presort'] = '';
		    }
		}

		list ($outer, $inner) = explode('.', $key);
		$form[$outer][$inner] = $value;
	    }

	    $newAlbumsUseDefaultsList = array(
		'false' => $module->translate('Inherit settings from parent album'),
		'true' => $module->translate('Use these settings')
	    );

	    /* Set up our sort order selection list */
	    GalleryCoreApi::requireOnce(
		'modules/core/classes/GallerySortInterface_1_1.class');
	    list ($ret, $orderByList, $presortList, $orderDirectionList) =
		GallerySortInterface_1_1::getAllSortOrders(false);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $AdminThemes['orderByList'] = $orderByList;
	    $AdminThemes['presortList'] = $presortList;
	    $AdminThemes['orderDirectionList'] = $orderDirectionList;
	    $AdminThemes['themeList'] = $themeList;
	    $AdminThemes['newAlbumsUseDefaultsList'] = $newAlbumsUseDefaultsList;
	    break;

	case 'config':
	    $AdminThemes['defaultThemeId'] = $defaultThemeId;
	    break;

	case 'editTheme':
	    /* Load the config settings from the active theme */
	    list ($ret, $theme) = GalleryCoreApi::loadPlugin('theme', $themeId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($theme->isAdvancedSettings()) {
		list ($ret, $tpl) = $theme->loadSettingsTemplate($template, $form);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$AdminThemes['customTemplate'] = $tpl;
	    }

	    list ($ret, $AdminThemes['settings']) = $theme->getSettings(null);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $AdminThemes['availableBlocks']) = $this->loadAvailableBlocks();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    break;
	}

	if ($form['formName'] != 'AdminThemes') {
	    $form['formName'] = 'AdminThemes';

	    switch ($mode) {
	    case 'editTheme':
		$settingTypes = array();
		if (isset($AdminThemes['settings'])) {
		    foreach ($AdminThemes['settings'] as $setting) {
			$key = $setting['key'];
			if (!empty($setting['typeParams']['packType'])) {
			    list ($success, $form['key'][$key]) = $theme->unpackSetting(
				$setting['typeParams']['packType'], $setting['value']);
			} else {
			    $form['key'][$key] = $setting['value'];
			}

			$settingTypes[$setting['type']] = 1;
		    }
		}

		if (isset($settingTypes['block-list'])) {
		    $template->javascript('lib/javascript/BlockSelectWidget.js');
		}
	    }
	}

	$AdminThemes['mode'] = $mode;
	$AdminThemes['themes'] = $themes;
	$AdminThemes['themeId'] = $themeId;

	$template->setVariable('AdminThemes', $AdminThemes);
	$template->setVariable('controller', 'core.AdminThemes');
	return array(null,
		     array('body' => 'modules/core/templates/AdminThemes.tpl'));
    }

    /**
     * Load the block configuration files from every module.
     *
     * @return array object GalleryStatus a status code
     *               array block configurations
     */
    function loadAvailableBlocks() {
	global $gallery;
	$platform =& $gallery->getPlatform();

	list ($ret, $pluginStatus) = GalleryCoreApi::fetchPluginStatus('module');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$data = array();
	$base = dirname(dirname(__FILE__));
	foreach ($pluginStatus as $pluginId => $status) {
	    if (empty($status['active'])) {
		continue;
	    }

	    $configFile = "$base/$pluginId/templates/blocks/blocks.inc";
	    if (!$platform->file_exists($configFile)) {
		continue;
	    }

	    $blocks = array();
	    include($configFile);

	    if (!empty($blocks)) {
		$data[$pluginId] = $blocks;
	    }
	}

	return array(null, $data);
    }
}
?>
