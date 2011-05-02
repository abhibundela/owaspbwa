<?php
/*
 * $RCSfile: ItemEdit.inc,v $
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
 * @version $Revision: 1.23 $ $Date: 2006/01/10 04:39:20 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the editing of an item
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class ItemEditController extends GalleryController {

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	list ($itemId, $editPlugin) = GalleryUtilities::getRequestVariables('itemId', 'editPlugin');

	/* Make sure we have permission do edit this item */
	$ret = GalleryCoreApi::assertHasItemPermission($itemId, 'core.edit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Check to see if we have a preferred source */
	list ($ret, $preferredTable) =
	    GalleryCoreApi::fetchPreferredsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$preferred = empty($preferredTable) ? null : array_shift($preferredTable);

	/* Load the thumbnail */
	list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	$thumbnail = empty($thumbnails) ? null : array_shift($thumbnails);

	/* Get all the edit options */
	list ($ret, $optionInstances) =
	    ItemEditOption::getAllOptions($editPlugin, $item, $thumbnail);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load the correct edit plugin */
	list ($ret, $plugin) =
	    GalleryCoreApi::newFactoryInstanceById('ItemEditPlugin', $editPlugin);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!isset($plugin)) {
	    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	}

	$status = array();
	list ($ret, $error, $status['editMessage'], $requiresProgressBar) =
	    $plugin->handleRequest($form, $item, $preferred);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!empty($requiresProgressBar)) {
	    $results['delegate']['view'] = 'core.ProgressBar';
	}

	/* Now let each option process its data */
	if (!isset($status['warning'])) {
	    $status['warning'] = array();
	}
	if (isset($form['action']['save'])) {
	    $progressBarOptions = array();
	    foreach ($optionInstances as $option) {
		if ($option->requiresProgressBar($form)) {
		    $progressBarOptions[] = $option;
		} else {
		    list ($ret, $optionErrors, $optionWarnings) =
			$option->handleRequestAfterEdit($form, $item, $preferred);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $error = array_merge($error, $optionErrors);
		    $status['warning'] = array_merge($status['warning'], $optionWarnings);
		}
	    }

	    if (empty($error) && $progressBarOptions) {
		$templateAdapter =& $gallery->getTemplateAdapter();
		$templateAdapter->registerTrailerCallback(
		    array($this, 'runProgressBarOptions'),
		    array($progressBarOptions, $form, $item, $preferred, $status, $editPlugin));
		$results['delegate']['view'] = 'core.ProgressBar';
	    }
	}

	if (empty($results['delegate'])) {
	    /* It's not a progress bar view */
	    if (empty($error)) {
		$results['redirect'] = array('view' => 'core.ItemAdmin',
					     'subView' => 'core.ItemEdit',
					     'editPlugin' => $editPlugin,
					     'itemId' => $item->getId());
	    } else {
		$results['delegate']['view'] = 'core.ItemAdmin';
		$results['delegate']['subView'] = 'core.ItemEdit';
		$results['delegate']['editPlugin'] = $editPlugin;
	    }
	}

	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }

    function runProgressBarOptions($options, $form, $item, $preferred, $status, $editPlugin) {
	global $gallery;
	$templateAdapter =& $gallery->getTemplateAdapter();

	$error = array();
	foreach ($options as $option) {
	    list ($ret, $optionErrors, $optionWarnings) =
		$option->handleRequestAfterEdit($form, $item, $preferred);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $error = array_merge($error, $optionErrors);
	    $status['warning'] = array_merge($status['warning'], $optionWarnings);
	}
	$session =& $gallery->getSession();

	$redirect = array();
	$redirect['view'] = 'core.ItemAdmin';
	$redirect['subView'] = 'core.ItemEdit';
	$redirect['itemId'] = $item->getId();
	$redirect['editPlugin'] = $editPlugin;
	$redirect['statusId'] = $session->putStatus($status);

	$urlGenerator =& $gallery->getUrlGenerator();
	$templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));

	return null;
    }
}

/**
 * This view will show options to edit an item
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemEditView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	list ($itemId, $editPlugin) = GalleryUtilities::getRequestVariables('itemId', 'editPlugin');

	/* Make sure we have permission do edit this item */
	$ret = GalleryCoreApi::assertHasItemPermission($itemId, 'core.edit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Load the thumbnail */
	list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds(array($item->getId()));
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if (!empty($thumbnails)) {
	    list ($ret, $thumbnail) = GalleryCoreApi::rebuildDerivativeCacheIfNotCurrent(
						      $thumbnails[$item->getId()]->getId());
	    if ($ret) {
		/* Ignore thumbnail errors so we can edit items with broken thumbnail */
	    }
	} else {
	    $thumbnail = null;
	}

	/* Get the edit plugins that support this item type */
	list ($ret, $allPluginIds) =
	    GalleryCoreApi::getAllFactoryImplementationIds('ItemEditPlugin');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$pluginInstances = array();
	foreach (array_keys($allPluginIds) as $pluginId) {
	    list ($ret, $plugin) =
		GalleryCoreApi::newFactoryInstanceById('ItemEditPlugin', $pluginId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($plugin->isSupported($item, $thumbnail)) {
		$pluginInstances[$pluginId] = $plugin;
	    }
	}

	/*
	 * If the plugin is empty get it from the session.  If it's empty there,
	 * default to the first plugin we find.  Either way, save the user's
	 * preference in the session.
	 */
	$session =& $gallery->getSession();
	$editPluginSessionKey = 'core.view.ItemEdit.editPlugin.' . get_class($item);
	if (empty($editPlugin)) {
	    $editPlugin = $session->get($editPluginSessionKey);
	    if (empty($editPlugin) || !in_array($editPlugin, array_keys($pluginInstances))) {
		$ids = array_keys($pluginInstances);
		$editPlugin = $ids[0];
	    }
	}
	$session->put($editPluginSessionKey, $editPlugin);

	/* Get display data for all plugins */
	$plugins = array();
	foreach ($pluginInstances as $pluginId => $plugin) {
	    list ($ret, $title) =  $plugin->getTitle();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $plugins[] = array('title' => $title,
			       'id' => $pluginId,
			       'isSelected' => ($pluginId == $editPlugin));
	}

	/* Record our item serial number in the form so that all plugins can use it */
	$form['serialNumber'] = $item->getSerialNumber();

	$ItemEdit = array();
	$ItemEdit['editPlugin'] = $editPlugin;
	$ItemEdit['plugins'] = $plugins;
	$ItemEdit['itemTypeNames'] = $item->itemTypeName();
	$ItemEdit['showEditThumbnail'] = $thumbnail != null;

	list ($ret, $ItemEdit['isAdmin']) = GalleryCoreApi::isUserInSiteAdminGroup();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Let the plugin load its template data */
	list ($ret, $ItemEdit['pluginFile'], $ItemEdit['pluginL10Domain']) =
	    $pluginInstances[$editPlugin]->loadTemplate($template, $form, $item, $thumbnail);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Get all the edit options */
	list ($ret, $optionInstances) =
	    ItemEditOption::getAllOptions($editPlugin, $item, $thumbnail);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Now let all options load their template data */
	$ItemEdit['options'] = array();
	foreach ($optionInstances as $option) {
	    list ($ret, $entry['file'], $entry['l10Domain']) =
		$option->loadTemplate($template, $form, $item, $thumbnail);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (!empty($entry['file'])) {
		$ItemEdit['options'][] = $entry;
	    }
	}

	$template->setVariable('ItemEdit', $ItemEdit);
	$template->setVariable('controller', 'core.ItemEdit');
	return array(null,
		     array('body' => 'modules/core/templates/ItemEdit.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	global $gallery;

	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	list ($ret, $core) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$itemTypeNames = $item->itemTypeName(true);

	return array(null,
		     $core->translate(array('text' => 'edit %s', 'arg1' => $itemTypeNames[1])));
    }
}

/**
 * Interface for plugins to the ItemEdit view and controller
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemEditPlugin {
    /**
     * Does this plugin support the given item type?
     *
     * @param the item
     * @param the item's thumbnail
     * @return boolean true if it's supported
     */
    function isSupported($item, $thumbnail) {
	return false;
    }

    /**
     * Load the template with data from this plugin
     * @see GalleryView::loadTemplate
     *
     * @param object GalleryTemplate the template instance
     * @param array the form values
     * @param the item
     * @param the item's thumbnail
     * @return array object GalleryStatus a status code
     *               string the path to a template file to include
     *               string localization domain for the template file
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__), null, null);
    }

    /**
     * Let the plugin handle the incoming request
     * @see GalleryController::handleRequest
     *
     * @param array the form values
     * @param the item
     * @param the item's preferred derivative, if there is one
     * @return array object GalleryStatus a status code
     *               array error messages
     *               string localized status message
     *               boolean true if progress bar is needed
     */
    function handleRequest($form, &$item, &$preferred) {
	return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__),
		     null, null, null);
    }

    /**
     * Return a localized title for this plugin, suitable for display to the user
     *
     * @return array object GalleryStatus a status code
     *               string localized title
     */
    function getTitle() {
	return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__), null);
    }
}

/**
 * Interface for options to the ItemEdit view and controller.
 * Options allow us to provide extra UI in the views and extra
 * processing in the controller so that we can add new functionality
 * to various ItemEditPlugins
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class ItemEditOption {

    /**
     * Return all the available option plugins
     *
     * @param string name of ItemEditPlugin
     * @param object GalleryItem item
     * @param object GalleryDerivative thumbnail
     * @return array object GalleryStatus a status code
     *               array object ItemEditOption instances
     * @static
     */
    function getAllOptions($editPlugin, $item, $thumbnail) {
	list ($ret, $allOptionIds) =
	    GalleryCoreApi::getAllFactoryImplementationIdsWithHint('ItemEditOption', $editPlugin);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$optionInstances = array();
	foreach (array_keys($allOptionIds) as $optionId) {
	    list ($ret, $option) =
		GalleryCoreApi::newFactoryInstanceById('ItemEditOption', $optionId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    list ($ret, $isAppropriate) = $option->isAppropriate($item, $thumbnail);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    if ($isAppropriate) {
		$optionInstances[$optionId] = $option;
	    }
	}

	return array(null, $optionInstances);
    }

    /**
     * Load the template with data from this plugin
     * @see GalleryView::loadTemplate
     *
     * @param object GalleryTemplate the template instance
     * @param array the form values
     * @param the item
     * @return array object GalleryStatus a status code
     *               string the path to a template file to include
     *               string localization domain for the template file
     */
    function loadTemplate(&$template, &$form, $item, $thumbnail) {
	return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__), null, null);
    }

    /**
     * Let the plugin handle the incoming request.  We expect the $item to be locked.
     * @see GalleryController::handleRequest
     *
     * @param array the form values
     * @param reference to the item
     * @param reference to preferred derivative
     * @return array object GalleryStatus a status code
     *               array error messages
     *               array localized warning messages
     */
    function handleRequestAfterEdit($form, &$item, &$preferred) {
	return array(GalleryCoreApi::error(ERROR_UNIMPLEMENTED, __FILE__, __LINE__), null, null);
    }

    /**
     * Is this option appropriate at this time?
     *
     * @param object GalleryItem item
     * @param object GalleryDerivative thumbnail
     * @return array object GalleryStatus a status code
     *               boolean true or false
     */
    function isAppropriate($item, $thumbnail) {
	return array(null, false);
    }

    /**
     * Will this task run so long that it requires a progress bar?
     *
     * @param the state of the current form
     * @return true or false
     */
    function requiresProgressBar($form) {
	return false;
    }
}
?>
