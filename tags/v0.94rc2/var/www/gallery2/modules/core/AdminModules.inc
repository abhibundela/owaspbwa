<?php
/*
 * $RCSfile: AdminModules.inc,v $
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
 * @version $Revision: 1.56 $ $Date: 2006/01/17 01:52:01 $
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
class AdminModulesController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest() {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = array();

	list($moduleId, $action) = GalleryUtilities::getRequestVariables('moduleId', 'action');
	if (!empty($moduleId) && !empty($action)) {
	    list($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId, true);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $isActive) = $module->isActive();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    switch($action) {

	    case 'install':
		$ret = $module->installOrUpgrade();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list($ret, $autoConfigured) = $module->autoConfigure();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminModules';
		$status['installed'] = $module->translate($module->getName());
		$status['autoConfigured'] = $autoConfigured;
		break;

	    case 'upgrade':
		$ret = $module->installOrUpgrade();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminModules';
		$status['upgraded'] = $module->translate($module->getName());
		break;

	    case 'activate':
		/* Can't activate if we're already active! */
		if (!$isActive) {
		    list ($ret, $needsConfiguration) = $module->needsConfiguration();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    /* Can't activate if we need to be configured! */
		    if ($needsConfiguration) {
			return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__),
				     null);
		    }

		    list ($ret, $redirect) = $module->activate();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    if (empty($redirect)) {
			$redirect['view'] = 'core.SiteAdmin';
			$redirect['subView'] = 'core.AdminModules';
		    }
		    $status['activated'] = $module->translate($module->getName());
		}
		break;

	    case 'deactivate':
		/* Can't deactivate if we're not active! */
		if ($isActive) {
		    list ($ret, $redirect) = $module->deactivate();
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }
		    if (empty($redirect)) {
			$redirect['view'] = 'core.SiteAdmin';
			$redirect['subView'] = 'core.AdminModules';
		    }
		    $status['deactivated'] = $module->translate($module->getName());
		}
		break;

	    case 'uninstall':
		/* Show the confirmation view */
		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminModulesVerifyUninstall';
		$redirect['moduleId'] = $module->getId();
		$status['uninstalled'] = $module->translate($module->getName());
		break;

	    default:
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__),
			     null);
	    }
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    if (empty($delegate)) {
		$results['delegate']['view'] = 'core.SiteAdmin';
		$results['delegate']['subView'] = 'core.AdminModules';
	    } else {
		$results['delegate'] = $delegate;
	    }
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will show administration possibilities for available modules
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class AdminModulesView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $coreModule) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load the module list */
	list ($ret, $moduleStatus) = GalleryCoreApi::fetchPluginStatus('module', true);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/*
	 * It's possible that we have some out-of-date modules which haven't
	 * been deactivated yet, since the deactivation only occurs when we try
	 * to load the module.  Load all the active modules now to force the
	 * version check, then reload the module status when we're done to pick
	 * up any changes we trigger.
	 */
	foreach ($moduleStatus as $moduleId => $status) {
	    if (!empty($status['active'])) {
		list($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId);
		if ($ret && !($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH)) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
	    }
	}

	/* Reload the module list, which may now be updated because of obsolete modules. */
	list ($ret, $moduleStatus) = GalleryCoreApi::fetchPluginStatus('module', true);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'AdminModules') {
	    /* We have no form variables */
	    $form['formName'] = 'AdminModules';
	}

	foreach ($moduleStatus as $moduleId => $status) {
	    /* Get the selected module's admin view (even if the module needs upgrading)  */
	    list($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId, true);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    $entry = array();
	    $entry['name'] = $module->translate($module->getName());
	    $entry['action'] = array();
	    $coreApiCompatible = GalleryUtilities::isCompatibleWithApi(
		$module->getRequiredCoreApi(), GalleryCoreApi::getApiVersion());
	    $moduleApiCompatible = GalleryUtilities::isCompatibleWithApi(
		$module->getRequiredModuleApi(), GalleryModule::getApiVersion());
	    if ($coreApiCompatible && $moduleApiCompatible) {
		if (empty($status['active'])) {
		    $version = $status['version'];
		    $entry['state'] = 'inactive';

		    /*
		     * If the database versions doesn't match the module
		     * version, we need to get the user to install the module.
		     */
		    if ($version != $module->getVersion()) {
			if (empty($version)) {
			    $entry['action'][] = array('text' => $coreModule->translate('install'),
						       'controller' => 'core.AdminModules',
						       'moduleId' => $module->getId(),
						       'action' => 'install');
			    $entry['state'] = 'install';
			} else {
			    $entry['action'][] = array('text' => $coreModule->translate('upgrade'),
						       'controller' => 'core.AdminModules',
						       'moduleId' => $module->getId(),
						       'action' => 'upgrade');
			    $entry['state'] = 'upgrade';
			}
		    } else {
			/*
			 * The versions match, but the module can still demand
			 * to be configured before being activated.
			 */
			list ($ret, $needsConfig) = $module->needsConfiguration();
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}

			if ($needsConfig) {
			    $configView = $module->getConfigurationView();
			    $entry['action'][] = array('text' =>
						       $coreModule->translate('configure'),
						       'view' => $configView);
			} else {
			    $entry['action'][] = array('text' => $coreModule->translate('activate'),
						       'controller' => 'core.AdminModules',
						       'moduleId' => $moduleId,
						       'action' => 'activate');
			}
			$entry['action'][] = array('text' => $coreModule->translate('uninstall'),
						   'controller' => 'core.AdminModules',
						   'moduleId' => $moduleId,
						   'action' => 'uninstall');
		    }
		} else {
		    $entry['state'] = 'active';
		    if ($moduleId != 'core') {
			$entry['action'][] = array('text' => $coreModule->translate('deactivate'),
						   'controller' => 'core.AdminModules',
						   'moduleId' => $moduleId,
						   'action' => 'deactivate');
		    }
		}
	    } else {
		$entry['state'] = 'incompatible';
		$entry['api']['required']['module'] = join('.', $module->getRequiredModuleApi());
		$entry['api']['required']['core'] = join('.', $module->getRequiredCoreApi());
		$entry['api']['provided']['module'] = join('.', GalleryModule::getApiVersion());
		$entry['api']['provided']['core'] = join('.', GalleryCoreApi::getApiVersion());
	    }

	    $entry['description'] = $module->translate($module->getDescription());
	    $entry['version'] = $module->getVersion();
	    $entry['installedVersion'] = isset($status['version']) ? $status['version'] : null;
	    $modules[] = array_merge($entry, $module->getGroup());
	}

	usort($modules, array($this, 'moduleSort'));
	$template->setVariable('AdminModules', array('modules' => $modules));
	$template->setVariable('controller', 'core.AdminModules');

	return array(null,
		     array('body' => 'modules/core/templates/AdminModules.tpl'));
    }

    function moduleSort($a, $b) {
	static $groupOrder;
	if (!isset($groupOrder)) {
	    /* gallery first, toolkits second, other last */
	    $groupOrder = array('gallery' => 1, 'toolkits' => 2, '' => 3, 'other' => 4);
	}
	$ag = $a['group'];
	$bg = $b['group'];
	if ($ag != $bg) {
	    $ao = isset($groupOrder[$ag]) ? $groupOrder[$ag] : $groupOrder[''];
	    $bo = isset($groupOrder[$bg]) ? $groupOrder[$bg] : $groupOrder[''];
	    if ($ao != $bo) {
		return ($ao > $bo) ? 1 : -1;
	    }
	    $ag = isset($a['groupLabel']) ? $a['groupLabel'] : $ag;
	    $bg = isset($b['groupLabel']) ? $b['groupLabel'] : $bg;
	    return ($ag > $bg) ? 1 : -1;
	} else {
	    $an = $a['name'];
	    $bn = $b['name'];
	    if ($an == $bn) {
		return 0;
	    } else {
		return ($an > $bn) ? 1 : -1;
	    }
	}
    }
}
?>
