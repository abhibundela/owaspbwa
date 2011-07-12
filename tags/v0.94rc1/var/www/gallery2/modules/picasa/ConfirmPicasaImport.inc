<?php
/*
* $RCSfile: ConfirmPicasaImport.inc,v $
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
* @version $Revision: 1.2 $ $Date: 2006/01/10 04:41:32 $
* @package Picasa
* @subpackage UserInterface
* @author Waldemar Schlackow <waldemar@opencodes.org> based on the migrate module by Jesse Mullan
*/

GalleryCoreApi::requireOnce('modules/picasa/classes/Picasa2DataParser.class');

/**
 * @package Picasa
 * @subpackage UserInterface
 */
class ConfirmPicasaImportController extends GalleryController {

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
        global $gallery;

        /* Verify that active user is an admin */
        $ret = GalleryCoreApi::assertUserIsSiteAdministrator();
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

        /* build framework for error and status arrays */
        $error = $status = array();

        if (isset($form['action']['cancel'])) {
            $redirect['view'] = 'core.SiteAdmin';
            $redirect['subView'] = 'picasa.SelectPicasaExportPath';
        } else if (isset($form['action']['import'])) {
            $templateAdapter =& $gallery->getTemplateAdapter();
            $templateAdapter->registerTrailerCallback(
                array($this, 'performImport'), array($form));
            $results['delegate']['view'] = 'core.ProgressBar';
        }

        if (!empty($redirect)) {
            $results['redirect'] = $redirect;
        } else if (empty($results['delegate'])) {
            $results['delegate']['view'] = 'core.SiteAdmin';
            $results['delegate']['subView'] = 'picasa.ConfirmPicasaImport';
        }
        $results['status'] = $status;
        $results['error'] = $error;

        return array(null, $results);
    }

    /**
     * Actually performs the import from Picasa into G2
     *
     * @param array the form variables
     * @return object GalleryStatus a status code
     */
    function performImport($form) {
        global $gallery;
        $storage = $gallery->getStorage();
        $templateAdapter =& $gallery->getTemplateAdapter();

        $platform =& $gallery->getPlatform();
        $slash = $platform->getDirectorySeparator();

        $finishedAlbums = $albumPosition = array();

        $status = array('albumId' => false,
            'albumImportFailure' => array(),
            'albumImportSuccess' => array(),
            'pictureImportSuccess' => array(),
            'pictureImportFailure' => array());

        list($ret, $module) = GalleryCoreApi::loadPlugin('module', 'picasa');
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        $sourceEncoding = 'UTF-8';

        list ($ret, $markupType) =
            GalleryCoreApi::getPluginParameter('module', 'core', 'misc.markup');
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        $itemsCreated = $picasaAlbum = array();

        /*
         * Check to see if the user selected a destination.  If not,
         * default to the root album of the new gallery install.
         */
        if (!isset($form['destinationAlbumId'])) {
            list ($ret, $rootId) =
                GalleryCoreApi::getPluginParameter('module', 'core', 'id.rootAlbum');
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            $form['destinationAlbumId'] = $rootId;
        }

        /* Convert the form variables into local temporary variables */
        $picasaXmlPath = $form['picasaXmlPath'];
        $destinationAlbumId = $form['destinationAlbumId'];

        /*
         * Import the Album (as of now Picasa supports only the export of a
         * single album and there is no concept of subalbums)
         */

        GalleryCoreApi::requireOnce('modules/picasa/classes/Picasa2DataParser.class');
        GalleryCoreApi::requireOnce('modules/picasa/classes/PicasaImportHelper.class');

        list($ret, $picasaAlbum) = Picasa2DataParser::getAlbum($picasaXmlPath);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        $totalItemsToImport = count($picasaAlbum['images']);

        if (empty($totalItemsToImport)) {
            $totalItemsToImport = 1;
        }
        $i = 0;

        $numberOfItemsImported = 0;
        $gallery->guaranteeTimeLimit(30);
        $newAlbumInstanceId = false;

        /*
         * We definitely need notices before each album.
         */
        $ret = $templateAdapter->updateProgressBar(
            $module->translate('Importing albums'),
            $picasaAlbum['albumName'], $numberOfItemsImported / $totalItemsToImport);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        $desiredname = $platform->legalizePathComponent($picasaAlbum['albumName']);
        $k = 0;
        $invalidName = true;
        while($invalidName) {
            list ($ret, $existingAlbumId) =
                GalleryCoreApi::fetchChildIdByPathComponent($destinationAlbumId, $desiredname);
            if ($ret) {
                if (!$ret->getErrorCode() & ERROR_MISSING_OBJECT) {
                    return $ret->wrap(__FILE__, __LINE__);
                } else {
                    $invalidName = false;
                }
            } else {
                $desiredname = $picasaAlbum['albumName'] . '_' . $k++;
            }
        }

        /* Make sure we have permission to edit the target item */
        $ret = GalleryCoreApi::assertHasItemPermission($destinationAlbumId, 'core.addAlbumItem');
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        /* Try to load targeted parent */
        list ($ret, $targetAlbumObject) = GalleryCoreApi::loadEntitiesById($destinationAlbumId);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        /* Get a lock on said parent */
        list ($ret, $importLockIds[]) = GalleryCoreApi::acquireReadLock($destinationAlbumId);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        /*
         * If everything is good so far, we create a new instance to be our new
         * album
         */
        if (!empty($desiredname) && $platform->isLegalPathComponent($desiredname)) {
            list ($ret, $newAlbumInstance) =
                GalleryCoreApi::newFactoryInstance('GalleryEntity', 'GalleryAlbumItem');
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            if (!isset($newAlbumInstance)) {
                return GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__);
            }

            /* this is where the album is actually created */
            $ret = $newAlbumInstance->create($destinationAlbumId, $desiredname);
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }

            $newAlbumInstanceId = $newAlbumInstance->getId();

            /* load up the album with metadata from the old album */
            if (isset($picasaAlbum['albumName'])) {
                $newAlbumInstance->setTitle(
		    PicasaImportHelper::convertHtml($markupType, $picasaAlbum['albumName'],
						    $sourceEncoding));
            }
            if (isset($picasaAlbum['albumCaption'])) {
                $newAlbumInstance->setDescription(
		    PicasaImportHelper::convertHtml($markupType, $picasaAlbum['albumCaption'],
						    $sourceEncoding));
                $newAlbumInstance->setSummary(
		    PicasaImportHelper::convertHtml($markupType, $picasaAlbum['albumCaption'],
						    $sourceEncoding));
            }
            $newAlbumInstance->setKeywords('');

            $ret = $newAlbumInstance->save();
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }

            $itemsCreated[] = $newAlbumInstance->getId();

            $finishedAlbums[$picasaAlbum['albumName']] = $newAlbumInstanceId;
            $status['albumImportSuccess'][$picasaAlbum['albumName']] = $newAlbumInstanceId;
            $status['albumId'] = $newAlbumInstanceId;
        }
        /*
         * we can let the parent album (and anything else that we might
         * have tied up) be edited by others now
         */

        $ret = GalleryCoreApi::releaseLocks($importLockIds);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        } else {
            $importLockIds = array();
        }

        list ($ret, $importLockIds[]) =
            GalleryCoreApi::acquireReadLock($newAlbumInstanceId);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }

        $photos = $picasaAlbum['images'];
        $numberOfItemsImported = 1;
        foreach($photos as $j => $importAlbumItem) {
            $gallery->guaranteeTimeLimit(30);
            $filepath = $picasaXmlPath . str_replace('/', $slash, $importAlbumItem['path']);
            $filename = $importAlbumItem['name'];
            $filecaption = PicasaImportHelper::convertHtml($markupType, $importAlbumItem['caption'],
                $sourceEncoding);
            $ret = $templateAdapter->updateProgressBar(
                $module->translate('Importing photos'),
                $picasaAlbum['albumName'] . ' ' . $filename,
                $numberOfItemsImported / $totalItemsToImport);
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            list ($base, $extension) =
                GalleryUtilities::getFileNameComponents($filename);
            list ($ret, $mimeType) =
                GalleryCoreApi::convertExtensionToMime($extension);
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            list ($ret, $newItem) = GalleryCoreApi::addItemToAlbum(
                $filepath, $filename, $base, $filecaption,
                $filecaption, $mimeType, $newAlbumInstanceId);
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            $itemsCreated[] = $newItem->getId();
            $numberOfItemsImported++;
            if ($ret) {
                return $ret->wrap(__FILE__, __LINE__);
            }
            $status['pictureImportSuccess'][$filename] = $newItem->getId();

        }

        $session =& $gallery->getSession();

        $redirect['view'] = 'core.SiteAdmin';
        $redirect['subView'] = 'picasa.ImportResults';
        $redirect['statusId'] = $session->putStatus($status);

        $urlGenerator =& $gallery->getUrlGenerator();
        $templateAdapter->completeProgressBar($urlGenerator->generateUrl($redirect));

        $ret = GalleryCoreApi::releaseLocks($importLockIds);
        if ($ret) {
            return $ret->wrap(__FILE__, __LINE__);
        }
        return null;
    }
}

/**
* @package Picasa
* @subpackage UserInterface
*/
class ConfirmPicasaImportView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
        global $gallery;

        /* Verify that active user is an admin */
        $ret = GalleryCoreApi::assertUserIsSiteAdministrator();
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

        $platform =& $gallery->getPlatform();
        $illegalAlbumNames = $existingAlbums = $titles = array();

        $picasaXmlPath = GalleryUtilities::getRequestVariables('picasaXmlPath');
        $destinationAlbumId = GalleryUtilities::getRequestVariables('destinationAlbumId');

        list ($ret, $album) = Picasa2DataParser::getAlbum($picasaXmlPath);
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }
        list ($ret, $targetAlbum) = GalleryCoreApi::loadEntitiesById($destinationAlbumId);
        if ($ret) {
            return array($ret->wrap(__FILE__, __LINE__), null);
        }

        $albumName = $album['albumName'];
        list ($ret, $existingAlbumId) =
            GalleryCoreApi::fetchChildIdByPathComponent($destinationAlbumId,
            $platform->legalizePathComponent($albumName));
        if ($ret) {
            if (!$ret->getErrorCode() & ERROR_MISSING_OBJECT) {
                return array($ret->wrap(__FILE__, __LINE__), null);
            }
        } else {
            $existingAlbums[$albumName] = $existingAlbumId;
        }
        if (!$platform->isLegalPathComponent($albumName)){
            $illegalAlbumNames[$albumName] = $platform->legalizePathComponent($albumName);
        }

        $ConfirmPicasaImport = array();
        $ConfirmPicasaImport['album'] = $album;
        $ConfirmPicasaImport['picasaXmlPath'] = $picasaXmlPath;
        $ConfirmPicasaImport['existingAlbums'] = $existingAlbums;
        $ConfirmPicasaImport['illegalAlbumNames'] = $illegalAlbumNames;
        $ConfirmPicasaImport['destinationAlbumId'] = $destinationAlbumId;
        $ConfirmPicasaImport['targetAlbum'] = (array)$targetAlbum;
        $template->setVariable('controller', 'picasa.ConfirmPicasaImport');
        $template->setVariable('ConfirmPicasaImport', $ConfirmPicasaImport);
	return array(null,
		     array('body' => 'modules/picasa/templates/ConfirmPicasaImport.tpl'));
    }
}
?>
