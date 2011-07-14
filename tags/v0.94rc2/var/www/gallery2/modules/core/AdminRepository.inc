<?php
/*
 * $RCSfile: AdminRepository.inc,v $
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
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
/**
 * @version $Revision: 1.7 $ $Date: 2006/02/26 07:23:31 $
 * @package GalleryCore
 * @subpackage UserInterface
 * @author Jozef Selesi <selesi at gmail dot com>
 */

GalleryCoreApi::requireOnce('modules/core/classes/GalleryRepository.class');

/**
 * This controller will handle an administration request for a module
 *
 * @package GalleryCore
 * @subpackage UserInterface
 *
 */
class AdminRepositoryController extends GalleryController {

    /**
     * Repository.
     *
     * @var object GalleryRepository
     * @access private
     */
    var $_repository;

    function _getRepository() {
	if (!isset($this->_repository)) {
	    $this->_repository = new GalleryRepository();
	}
	return $this->_repository;
    }

    /**
     * @see GalleryController::handleRequest
     */
    function handleRequest($form) {
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Init repository. */
	$repository = $this->_getRepository();
	$ret = $repository->init();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = $error = array();

	$mode = GalleryUtilities::getRequestVariables('mode');
	switch($mode) {
	case 'browseModules':
	case 'browseThemes':
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'core.AdminRepository';
	    $redirect['mode'] = $mode;
	    break;

	case 'download':
	    if (!isset($form['pluginType']) || !isset($form['pluginId'])) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
					     "Plugin type or ID not set [$pluginType] [$pluginId]"),
			     null);
	    }
	    $pluginType = $form['pluginType'];
	    $pluginId = $form['pluginId'];

	    if (!preg_match('/theme|module/', $pluginType)) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
						  "Invalid plugin type [$pluginType]"),
			     null);
	    }
	    $returnTab = sprintf('browse%ss', ucfirst($form['pluginType']));

	    /* Handle cancel action. */
	    if (isset($form['action']['cancel'])) {
		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminRepository';
		$redirect['mode'] = $returnTab;
		break;
	    }

	    /* Create package list. */
	    $packages = array();
	    if (isset($form['upgradeBaseFiles'])) {
		$packages['base'] = 'upgrade';
	    } else if (isset($form['downloadBaseFiles'])) {
		$packages['base'] = 'download';
	    }

	    if (isset($form['downloadLanguages'])) {
		foreach ($form['downloadLanguages'] as $language) {
		    $packages['lang-' . $language] = 1;
		}
	    }

	    if (isset($form['upgradeLanguages'])) {
		foreach ($form['upgradeLanguages'] as $language) {
		    $packages['lang-' . $language] = 1;
		}
	    }

	    if (isset($form['downloadTest'])) {
		$packages['test'] = 1;
	    }

	    /* Show error message if no packages have been selected for download. */
	    if (empty($packages)) {
		$delegate['view'] = 'core.SiteAdmin';
		$delegate['subView'] = 'core.AdminRepository';
		$delegate['mode'] = 'download';
		$error[] = 'form[packages][empty]';
		GalleryUtilities::putRequestVariable('pluginId', $pluginId);
		GalleryUtilities::putRequestVariable('pluginType', $pluginType);
		break;
	    }

	    $templateAdapter =& $gallery->getTemplateAdapter();
	    $templateAdapter->registerTrailerCallback(
		array($this, 'performDownloadAndInstallation'), array(array(
		$pluginType => array($pluginId => $packages)), $returnTab));
	    $delegate['view'] = 'core.ProgressBar';
	    break;

	default:
	    if (isset($form['action']['update'])) {
		/* Try to update the index. */
		$ret = $repository->downloadIndex();
		if ($ret) {
		    $status['error'] = $ret->getErrorMessage();
		} else {
		    $status['indexUpdated'] = 1;
		}
	    } else if (isset($form['action']['upgradeAll'])) {
		/* Get list of upgradeable packages. */
		list ($ret, $packages) = $repository->getAllUpgradeablePackages();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if (empty($packages)) {
		    $status['noUpgradeAvailable'] = 1;
		} else {
		    /* Start upgrade process and show progress bar. */
		    $templateAdapter =& $gallery->getTemplateAdapter();
		    $templateAdapter->registerTrailerCallback(
			array($this, 'performDownloadAndInstallation'),
			array($packages, 'commonTasks'));
		    $delegate['view'] = 'core.ProgressBar';
		    break;
		}
	    }
	    /* Show common tasks tab. */
	    $redirect['view'] = 'core.SiteAdmin';
	    $redirect['subView'] = 'core.AdminRepository';
	    $redirect['mode'] = 'commonTasks';
	    break;
	}

	if (!empty($redirect)) {
	    $results['redirect'] = $redirect;
	} else {
	    if (empty($delegate)) {
		$results['delegate']['view'] = 'core.SiteAdmin';
		$results['delegate']['subView'] = 'core.AdminRepository';
	    } else {
		$results['delegate'] = $delegate;
	    }
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }

    /**
     * Download specified packages to the local repository cache and perform installation.
     *
     * TODO: Show a summary page (or at least a link to it) which contains details about
     * the exact tasks that were performed and any errors that were encountered.
     *
     * @param array packages
     * @param string 'commonTasks', 'browseThemes' or 'browseModules'
     */
    function performDownloadAndInstallation($pluginData, $returnTab) {
	global $gallery;
	$templateAdapter =& $gallery->getTemplateAdapter();

	$repository = $this->_getRepository();
	$ret = $repository->init();
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Create download file list. */
	list ($ret, $files) = $repository->getDownloadFileList($pluginData);
	if ($ret) {
	    $templateAdapter->errorProgressBar($ret);
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'core');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Download files. */
	$titleText = $module->translate('Performing Maintenance Tasks');
	foreach ($files as $pluginType => $plugins) {
	    foreach ($plugins as $pluginId => $pluginDownloadData) {
		$packageUrls = $pluginDownloadData['files'];
		$pluginName = $pluginDownloadData['name'];

		/* Update plugin index file if this is when the base files have been downloaded. */
		$ret = $repository->updatePluginIndexFile($pluginType, $pluginId);
		if ($ret) {
		    $templateAdapter->errorProgressBar($ret);
		    return $ret->wrap(__FILE__, __LINE__);
		}

		/* Set up progress bar variables. */
		$currentUrlIndex = 1;
		$totalUrls = count($packageUrls);

		foreach ($packageUrls as $packageName => $relativePackageUrl) {
		    /* Update progress bar. */
		    $ret = $templateAdapter->updateProgressBar($titleText,
			sprintf($module->translate('Downloading %s'), $pluginName),
			$currentUrlIndex / $totalUrls);
		    if ($ret) {
			$templateAdapter->errorProgressBar($ret);
			return $ret->wrap(__FILE__, __LINE__);
		    }
		    $currentUrlIndex++;

		    /* Download and unpack package. */
		    list ($ret, $pluginDescriptor) = $repository->downloadAndUnpack(
			$pluginType, $pluginId, $packageName, $relativePackageUrl);
		    if ($ret) {
			$templateAdapter->errorProgressBar($ret);
			return $ret->wrap(__FILE__, __LINE__);
		    }

		    if (!empty($pluginDescriptor)) {
			/* Save current package's descriptor. */
			$descriptor = $pluginDescriptor;
		    } else {
			/* Sanity check. */
			if (empty($descriptor)) {
			    $ret = GalleryCoreApi::error(ERROR_STORAGE_FAILURE, __FILE__, __LINE__,
							'Descriptor must not be empty.');
			    $templateAdapter->errorProgressBar($ret);
			    return $ret;
			}

			/* Check the unpacked files' integrity. */
			$ret = $repository->verifyPackageIntegrity(
			    $packageName, $descriptor);
			if ($ret) {
			    $templateAdapter->errorProgressBar($ret);
			    return $ret->wrap(__FILE__, __LINE__);
			}

			/* Update plugin package map. */
			list ($ret, $version, $build) =
			    $repository->getPackageVersionAndBuild($pluginType, $pluginId,
								   $packageName);
			if ($ret) {
			    $templateAdapter->errorProgressBar($ret);
			    return $ret->wrap(__FILE__, __LINE__);
			}
			$ret = $repository->updatePackageMetaData(
			    $pluginType, $pluginId, $packageName, $version, $build);
			if ($ret) {
			    $templateAdapter->errorProgressBar($ret);
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }
	}

	/* Update progress bar. */
	$ret = $templateAdapter->updateProgressBar($titleText, $module->translate('Done.'), 1);
	if ($ret) {
	    $templateAdapter->errorProgressBar($ret);
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Show link to return to the previously selected tab. */
	$redirect['view'] = 'core.SiteAdmin';
	$redirect['subView'] = 'core.AdminRepository';
	$redirect['mode'] = $returnTab;
	$urlGenerator =& $gallery->getUrlGenerator();
	$templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));

	return null;
    }
}

/**
 * This view will show all repository-related features.
 *
 * @package GalleryCore
 * @subpackage UserInterface
 */
class AdminRepositoryView extends GalleryView {

    /**
     * Repository.
     *
     * @var object GalleryRepository
     * @access private
     */
    var $_repository;

    function _getRepository() {
	if (!isset($this->_repository)) {
	    $this->_repository = new GalleryRepository();
	}
	return $this->_repository;
    }

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	$ret = GalleryCoreApi::assertUserIsSiteAdministrator();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Init repository. */
	$repository = $this->_getRepository();
	$ret = $repository->init();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$mode = GalleryUtilities::getRequestVariables('mode');

	$localIndexExists = $repository->localIndexExists();
	if (!$localIndexExists) {
	    /* Make sure the plugins directory is properly set up. */
	    list ($isSetUp, $errorMessage) = GalleryRepository::createPluginsDirectory();
	    if (!$isSetUp) {
		$platform =& $gallery->getPlatform();

		$redirect['view'] = 'core.SiteAdmin';
		$redirect['subView'] = 'core.AdminRepositorySetup';
		$template->setVariable('pluginsDirectoryName',
		    $gallery->getConfig('plugins.dirname'));
		$template->setVariable('galleryBaseDirectory',
		    $platform->realpath(dirname(__FILE__) . '/../..'));
		$template->setVariable('errorMessage', $errorMessage);

		return array(null,
			     array('body' => 'modules/core/templates/AdminRepositorySetup.tpl'));
	    }
	}

	if ($mode == 'browseThemes' || $mode == 'browseModules') {
	    /* Repository browse mode. */
	    $AdminRepository['mode'] = $mode;
	    $pluginType = $mode == 'browseThemes' ? 'theme' : 'module';

	    /* Check if incompatible plugins should be shown. */
	    $coreApis = array();
	    $showIncompatible = GalleryUtilities::getRequestVariables('showIncompatible');
	    if ($showIncompatible == 'true') {
		list ($coreApiVersion, $themeApiVersion, $moduleApiVersion) =
		    GalleryUtilities::getRequestVariables('coreApi', 'themeApi', 'moduleApi');
		if (empty($coreApiVersion) || empty($themeApiVersion) || empty($moduleApiVersion)) {
		    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
					"[$coreApiVersion] [$themeApiVersion] [$moduleApiVersion]"),
				 null);
		}
		$coreApis['core'] = explode('.', $coreApiVersion);
		$coreApis['module'] = explode('.', $moduleApiVersion);
		$coreApis['theme'] = explode('.', $themeApiVersion);
		$template->setVariable('showIncompatible', 1);
	    }

	    if ($localIndexExists) {
		/* Get list of plugins to show in the repository browser. */
		list ($ret, $browseData) =
		    $repository->getRepositoryPluginList($pluginType, $showIncompatible, $coreApis);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$template->setVariable('browseData', $browseData);
	    }
	} else if ($mode == 'download') {
	    list ($pluginType, $pluginId) =
		GalleryUtilities::getRequestVariables('pluginType', 'pluginId');

	    /* Plugin download options. */
	    if (!$repository->pluginExistsInIndex($pluginType, $pluginId)) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__,
					     "Plugin not found in index [$pluginId] [$pluginType]"),
			     null);
	    }

	    /*
	     * Downloading and upgrading plugins are functionally equivalent,
	     * we just adapt the UI a little.
	     */
	    list ($ret, $upgradeData) =
		$repository->getPluginUpgradeInfo($pluginType, $pluginId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $actionText = isset($upgradeData['upgradeablePackages']) ? $gallery->i18n('Upgrade')
								     : $gallery->i18n('Download');

	    list ($ret, $pluginName) = $repository->getPluginName($pluginType, $pluginId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    $template->setVariable('pluginName', $pluginName);
	    $template->setVariable('upgradeData', $upgradeData);
	    $template->setVariable('actionText', $actionText);
	    $template->setVariable('pluginId', $pluginId);
	    $template->setVariable('pluginType', $pluginType);
	    $template->setVariable('controller', 'core.AdminRepository');

	    return array(null,
			 array('body' => 'modules/core/templates/AdminRepositoryDownload.tpl'));
	} else {
	    /* Common tasks mode. */
	    $AdminRepository['mode'] = 'commonTasks';

	    if ($localIndexExists) {
		/* Get local index meta data. */
		list ($ret, $indexMetaData) = $repository->getIndexMetaData();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		$template->setVariable('indexMetaData', $indexMetaData);
	    }
	}

	if ($localIndexExists) {
	    /* Get core upgrade info. */
	    list ($ret, $isCoreUpgradeAvailable, $apiVersions) =
		$repository->isCoreUpgradeAvailable();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if ($isCoreUpgradeAvailable) {
		$template->setVariable('latestCoreApiVersion', $apiVersions['core']);
		$template->setVariable('latestThemeApiVersion', $apiVersions['theme']);
		$template->setVariable('latestModuleApiVersion', $apiVersions['module']);
	    }
	    $template->setVariable('coreUpgradeAvailable', $isCoreUpgradeAvailable);
	}

	/* Render the HTML body */
	$template->setVariable('AdminRepository', $AdminRepository);
	$template->setVariable('controller', 'core.AdminRepository');

	return array(null,
		     array('body' => 'modules/core/templates/AdminRepository.tpl'));
    }
}
?>
