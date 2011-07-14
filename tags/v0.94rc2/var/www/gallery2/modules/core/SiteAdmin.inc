<?php
/*
 * $RCSfile: SiteAdmin.inc,v $
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
 * @version $Revision: 1.49 $ $Date: 2006/01/17 01:52:01 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This view will show the availiable site-admin options
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class SiteAdminView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	/* Make sure we have adequate permissions */
	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Bring in the root album information for the breadcrumb */
	list ($ret, $rootAlbumId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	list ($ret, $rootAlbum) = GalleryCoreApi::loadEntitiesById($rootAlbumId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$rootAlbumData = (array)$rootAlbum;

	/* Load the module list */
	list ($ret, $moduleStatus) = GalleryCoreApi::fetchPluginStatus('module');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Get a list of all the admin views for each module */
	$subViewChoices = array();
	ksort($moduleStatus);

	foreach ($moduleStatus as $moduleId => $status) {
	    if (empty($status['active'])) {
		continue;
	    }

	    /* Get the selected module's admin view */
	    if (in_array('getSiteAdminViews', explode('|', $status['callbacks']))) {
		list($ret, $module) = GalleryCoreApi::loadPlugin('module', $moduleId);
		if ($ret) {
		    if ($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH) {
			continue;
		    }
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $moduleViews) = $module->getSiteAdminViews();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$group = $module->getGroup();
		foreach (array_keys($moduleViews) as $i) {
		    $moduleViews[$i] = array_merge($group, $moduleViews[$i]);
		}

		$subViewChoices = array_merge($subViewChoices, $moduleViews);
	    }
	}

	/* Sort and group */
	$subViewGroups = $groupLabels = array();
	foreach ($subViewChoices as $adminView) {
	    $group = $adminView['group'];
	    $subViewGroups[$group][] = $adminView;

	    if (!empty($adminView['groupLabel'])) {
		$label = $adminView['groupLabel'];
		if (!isset($groupLabels[$group][$label])) {
		    $groupLabels[$group][$label] = 1;
		} else {
		    $groupLabels[$group][$label]++;
		}
	    }
	}

	/* Set labels */
	foreach (array_keys($subViewGroups) as $group) {
	    $data =& $subViewGroups[$group];
	    usort($data, array($this, 'nameSort'));
	    if (isset($groupLabels[$group])) {
		$tmp = -1;
		foreach ($groupLabels[$group] as $label => $count) {
		    if ($count > $tmp) {
			$tmp = $count;
			$data[0]['groupLabel'] = $label;
		    }
		}
	    } else {
		$data[0]['groupLabel'] = ucwords($data[0]['group']);
	    }
	}
	usort($subViewGroups, array($this, 'groupSort'));

	/* If we have a specific sub view, render it now */
	$subViewName = GalleryUtilities::getRequestVariables('subView');
	if ($subViewName == 'core.SiteAdmin') {
	    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	}
	if (empty($subViewName)) {
	    $subViewName = 'core.AdminCore';
	}

	list ($ret, $subView) = GalleryView::loadView($subViewName);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $results) = $subView->loadTemplate($template, $form);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Our sub view may have set some hints, like the encoding type */
	if ($template->hasVariable('SiteAdmin')) {
	    $SiteAdmin =& $template->getVariableByReference('SiteAdmin');
	} else {
	    $SiteAdmin = array();
	}

	/* Get the links for navigating back to where we came from */
	$urlGenerator =& $gallery->getUrlGenerator();
	list ($ret, $navigationLinks) = $urlGenerator->getNavigationLinks();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Set up my view parameters */
	$SiteAdmin['rootAlbum'] = $rootAlbumData;
	$SiteAdmin['navigationLinks'] = $navigationLinks;
	$SiteAdmin['subViewGroups'] = $subViewGroups;
	$SiteAdmin['subViewName'] = $subViewName;
	$SiteAdmin['viewBodyFile'] = $results['body'];
	$SiteAdmin['viewL10Domain'] = $subView->getL10Domain();

	$template->setVariable('SiteAdmin', $SiteAdmin);
	$template->title($module->translate('Gallery Site Administration'));
	return array(null,
		     array('body' => 'modules/core/templates/SiteAdmin.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	/* Get the description from the current subView */
	$subViewName = GalleryUtilities::getRequestVariables('subView');
	if (empty($subViewName)) {
	    $subViewName = 'core.AdminCore';
	}
	list ($ret, $subView) = GalleryView::loadView($subViewName);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $description) = $subView->getViewDescription();
	if (!empty($description)) {
	    return array(null, $description);
	}
	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Fallback if subView doesn't have a name */
	return array(null, $core->translate('site admin'));
    }

    /**
     * @see GalleryView::getViewType()
     */
    function getViewType() {
	return VIEW_TYPE_ADMIN;
    }

    function groupSort($a, $b) {
	static $groupOrder;
	if (!isset($groupOrder)) {
	    /* gallery first, toolkits second, other last */
	    $groupOrder = array('gallery' => 1, 'toolkits' => 2, '' => 3, 'other' => 4);
	}
	$ag = $a[0]['group'];
	$bg = $b[0]['group'];
	$ao = isset($groupOrder[$ag]) ? $groupOrder[$ag] : $groupOrder[''];
	$bo = isset($groupOrder[$bg]) ? $groupOrder[$bg] : $groupOrder[''];
	if ($ao != $bo) {
	    return ($ao > $bo) ? 1 : -1;
	}
	$ag = isset($a[0]['groupLabel']) ? $a[0]['groupLabel'] : $ag;
	$bg = isset($b[0]['groupLabel']) ? $b[0]['groupLabel'] : $bg;
	return ($ag > $bg) ? 1 : -1;
    }

    function nameSort($a, $b) {
	$an = $a['name'];
	$bn = $b['name'];
	if ($an == $bn) {
	    return 0;
	}
	return ($an > $bn) ? 1 : -1;
    }
}
?>
