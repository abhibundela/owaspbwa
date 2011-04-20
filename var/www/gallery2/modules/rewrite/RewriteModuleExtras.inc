<?php
/*
 * $RCSfile: RewriteModuleExtras.inc,v $
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
 * @package Rewrite
 * @version $Revision: 1.38 $ $Date: 2006/03/22 03:52:35 $
 * @author Douglas Cau <douglas@cau.se>
 */

class RewriteModuleExtras {

    /**
     * @see GalleryModule::upgrade
     */
    function upgrade($module, $currentVersion) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	switch ($currentVersion) {
	case null:
	    /* Initial install */
	    foreach (array('accessList' => serialize(array()),
			   'allowEmptyReferer' => '1',
			   'shortUrls' => serialize(array()),
			   'activeRules' => serialize(array()),
			   'parserId' => '',
			   'modrewrite.galleryLocation' => '',
			   'modrewrite.status' => serialize(array()),
			   'modrewrite.embeddedLocation' => '',
			   'modrewrite.embeddedHtaccess' => '',
			   'isapirewrite.forced' => '0',
			   'isapirewrite.galleryLocation' => '',
			   'isapirewrite.embeddedLocation' => '',
			   'isapirewrite.httpdini' => '',
			   'pathinfo.forced' => '0',
			   'pathinfo.parser' => serialize(array()))
		     as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	    break;

	case '0.0.1':
	case '0.0.2':
	case '0.0.3':
	case '0.0.4':
	case '0.0.5':
	case '0.1.0':
	case '0.1.1':
	case '0.1.2':
	case '0.1.3':
	case '0.1.4':
	case '0.1.5':
	case '0.1.6':
	case '0.1.7':
	case '0.1.8':
	case '0.1.9':
	case '0.1.10':
	case '0.8.0':
	case '0.8.1':
	case '0.9.0':
	case '0.9.1':
	case '0.9.2':
	    /* Reset Gallery location, needs to be reconfigured. */
	    foreach (array('galleryLocation' => '',
			   'embeddedLocation' => '',
			   'embeddedHtaccess' => '')
		     as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	    /* The module needs to be reconfigured */
	    list ($ret) = $module->deactivate();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	case '0.9.3':
	case '0.9.4':
	    foreach (array('accessList' => serialize(array()))
		     as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.5':
	    /*
	     * Previously we changed all module:View names to module.View. Since we no longer
	     * store view names in the active rules list, and the old table is deleted in version
	     * 1.1.0 we no longer need to do this.
	     */

	case '0.9.6':
	case '0.9.7':
	case '0.9.8':
	case '0.9.9':
	case '0.9.10':
	    foreach (array('status' => serialize(array()))
		     as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '0.9.11':
	case '0.9.12':
	case '0.9.13':
	case '0.9.14':
	case '0.9.15':
	case '0.9.16':
	case '0.9.17':
	case '0.9.18':
	case '1.0.0':
	case '1.0.1':
	case '1.0.2':
	case '1.0.3':
	case '1.0.4':
	    /* Get the custom rules and save it to a plugin param (RewriteMap depricated) */
	    $query = '
		SELECT
		    [RewriteMap::module], [RewriteMap::ruleId], [RewriteMap::pattern]
		FROM
		    [RewriteMap]
		ORDER BY
		    [RewriteMap::module] ASC, [RewriteMap::ruleId] ASC
	    ';

	    /*
	     * If this is an upgrade from Beta 2 we dont have a RewriteMap. Previous to 0.9.3
	     * rules were static, therefore leave $activeRules empty. It'll get populated
	     * with the default rules when activate() is called.
	     */
	    $activeRules = array();
	    if (version_compare($currentVersion, '0.9.3', '>=')) {
		list ($ret, $searchResults) = $gallery->search($query);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		while ($result = $searchResults->nextResult()) {
		    $activeRules[$result[0]][$result[1]] = $result[2];
		}
	    }

	    $params = array('shortUrls' => serialize(array()),
			    'activeRules' => serialize($activeRules),
			    'allowEmptyReferer' => '1',
			    'parserId' => 'modrewrite',
			    'pathinfo.parser' => serialize(array()),
			    'pathinfo.forced' => '0',
			    'isapirewrite.forced' => '0',
			    'isapirewrite.galleryLocation' => '',
			    'isapirewrite.embeddedLocation' => '',
			    'isapirewrite.httpdini' => '');

	    /* Rename mod_rewrite specific parameters */
	    foreach (array('galleryLocation',
			   'status',
			   'embeddedLocation',
			   'embeddedHtaccess')
		     as $key) {
		list ($ret, $value) = $module->getParameter($key);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		$params['modrewrite.' . $key] = $value;
	    }

	    list ($ret, $value) = $module->getParameter('accessList');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $params['accessList'] = $value;

	    /* Cleanup: Remove all old params */
	    $ret = GalleryCoreApi::removeAllPluginParameters('module', $module->getId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Set the new params */
	    foreach ($params as $key => $value) {
		$ret = $module->setParameter($key, $value);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }

	case '1.1.0':
	    /* Major GalleryUrlGenerator change */
	case '1.1.1':
	    /* GalleryCoreApi 7.0 and GalleryModule 3.0 */
	case '1.1.2':
	case '1.1.3':
	    
	case 'end of upgrade path':
	    break;

	default:
	    return GalleryCoreApi::error(ERROR_BAD_PLUGIN, __FILE__, __LINE__,
					sprintf('Unknown module version %s', $currentVersion));
	}

	return null;
    }

    /**
     * @see GalleryModule::handleEvent
     */
    function handleDeactivatePlugin($module, $pluginId) {
	list ($ret, $activeRules) = $module->getParameter('activeRules');
    	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$activeRules = unserialize($activeRules);

	/* Don't update the rules if not necissary */
	if (isset($activeRules[$pluginId])) {
    	    GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
	    list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    /* Save the old patterns for later use if module gets reactivated */
	    $ret = $module->setParameter('history.' . $pluginId,
		serialize($activeRules[$pluginId]));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    unset($activeRules[$pluginId]);

	    /* Try to activate the new rules */
	    list ($ret) = $rewriteParser->saveActiveRules($activeRules, $module);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	}

	return null;
    }

    /**
     * @see GalleryModule::handleEvent
     */
    function handleUninstallPlugin($module, $pluginId) {
	$ret = $module->removeParameter('history.' . $pluginId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	return null;
    }

    /**
     * @see GalleryModule::handleEvent
     */
    function handleActivatePlugin($module, $pluginId) {
	/* Get saved patterns */
	list ($ret, $plugin) = GalleryCoreApi::loadPlugin('module', $pluginId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$rules = $plugin->getRewriteRules();

	list ($ret, $history) = $module->getParameter('history.' . $pluginId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$ret = $module->removeParameter('history.' . $pluginId);
	if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	}

	if (!empty($history)) {
	    $history = unserialize($history);
	    list ($ret, $activeRules) = $module->getParameter('activeRules');
    	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $activeRules = unserialize($activeRules);
	    $originalSize = sizeof($activeRules);

	    $seen = array();
	    foreach (array_keys($activeRules) as $moduleId) {
		foreach (array_keys($activeRules[$moduleId]) as $ruleId) {
		    $seen[$activeRules[$moduleId][$ruleId]] = 1;
		}
	    }

	    foreach (array_keys($history) as $ruleId) {
		if (isset($rules[$ruleId]) && !isset($seen[$history[$ruleId]])) {
		    $activeRules[$pluginId][$ruleId] = $history[$ruleId];
		    $seen[$history[$ruleId]] = 1;
		}
	    }

	    /* Only update the active rules if we added something */
	    if (sizeof($activeRules) > $originalSize) {
		GalleryCoreApi::requireOnce('modules/rewrite/classes/RewriteHelper.class');
		list ($ret, $rewriteParser) = RewriteHelper::getRewriteParser();
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		list ($ret) = $rewriteParser->saveActiveRules($activeRules, $module);
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	}

	return null;
    }
}
?>
