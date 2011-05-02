<?php
/*
 * $RCSfile: PrintPhotos.inc,v $
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
 * @version $Revision: 1.13 $ $Date: 2006/02/23 09:41:11 $
 * @package Shutterfly
 * @subpackage UserInterface
 * @author Alan Harder <alan.harder@sun.com>
 */

/**
 * This view creates a form to send data to shutterfly.com
 *
 * @package Shutterfly
 * @subpackage UserInterface
 */
class PrintPhotosView extends GalleryView {

    /**
     * @see GalleryView::isImmediate
     */
    function isImmediate() {
	return true;
    }

    /**
     * @see GalleryView::renderImmedate()
     */
    function renderImmediate($status, $error) {
	global $gallery;
	$urlGenerator =& $gallery->getUrlGenerator();

	list ($itemId, $returnUrl) = GalleryUtilities::getRequestVariables('itemId', 'return');
	if (!empty($itemId)) {
	    $cartItemIds = array($itemId => 1);
	} else {
	    $session =& $gallery->getSession();
	    $cartItemIds = $session->get('shutterfly.cart');
	    $session->remove('shutterfly.cart');
	}
	if (empty($cartItemIds) || empty($returnUrl)) {
	    return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
	}

	/* Load the necessary item data */
	$itemIds = array_keys($cartItemIds);
	list ($ret, $items) = GalleryCoreApi::loadEntitiesById($itemIds);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $thumbnails) = GalleryCoreApi::fetchThumbnailsByItemIds($itemIds);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $resizes) = GalleryCoreApi::fetchResizesByItemIds($itemIds);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	list ($ret, $preferreds) = GalleryCoreApi::fetchPreferredsByItemIds($itemIds);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	$ret = GalleryCoreApi::studyPermissions($itemIds);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* We want to know which are viewable to guests */
	list ($ret, $anonymousUserId) =
	    GalleryCoreApi::getPluginParameter('module', 'core', 'id.anonymousUser');
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}
	$ret = GalleryCoreApi::studyPermissions($itemIds, $anonymousUserId);
	if ($ret) {
	    return $ret->wrap(__FILE__, __LINE__);
	}

	/* Assemble all our data */
	$i = 1;
	$entries = array();
	foreach ($items as $item) {
	    $itemId = $item->getId();
	    list ($ret, $permissions) = GalleryCoreApi::getPermissions($itemId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $publicPermissions) =
		GalleryCoreApi::getPermissions($itemId, $anonymousUserId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (!isset($permissions['shutterfly.print'])) {
		/* Skip any cart items for which we don't have print permission */
		continue;
	    }
	    if (isset($permissions['core.viewSource'])) {
		$source = isset($preferreds[$itemId]) ? $preferreds[$itemId] : $item;
		$needSession = !isset($publicPermissions['core.viewSource']);
	    } else if (isset($permissions['core.viewResizes']) && !empty($resizes[$itemId])) {
		$maxSize = null;
		foreach ($resizes[$itemId] as $resize) {
		    $size = $resize->getDerivativeSize();
		    if (!isset($maxSize) || $size > $maxSize) {
			$source = $resize;
			$maxSize = $size;
		    }
		}
		$needSession = !isset($publicPermissions['core.viewResizes']);
	    } else if (isset($thumbnails[$itemId])) {
		$source = $thumbnails[$itemId];
		$needSession = !isset($publicPermissions['core.view']);
	    } else {
		continue;
	    }

	    if ($needSession && !isset($sfSession)) {
		/*
		 * Get G2 session for shutterfly to access non-public images.
		 * We can't use this session because hijack protection will prevent access
		 * plus the current user could logout before shutterfly retrieves the images.
		 * Create a new session with the rights of current user for shutterfly to use.
		 */
		$sfSession = new GallerySession();
		$ret = $sfSession->initEmpty(true, $gallery->getActiveUserId());
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
		/*
		 * TODO: Would like to enforce a particular session timeout to ensure this
		 * session lasts long enough for shutterfly to retrieve the images.  With
		 * current file based sessions this would require modifying ctime of files
		 * which we can't do.  Maybe if sessions move to the database we'll be able
		 * to guarantee session lifetime.
		 * When we can do this, maybe also store the sessionid in this session so we
		 * can reuse it for multiple print requests (and just bump timeout each time).
		 */
		$ret = $sfSession->save();
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}
	    }
	    $sessionParam =
		$needSession ? array($sfSession->getKey() => $sfSession->getId()) : array();

	    $entry = array('item' => (array)$item);
	    $entry['imageUrl'] = $urlGenerator->generateUrl(
		array_merge(array('view' => 'core.DownloadItem', 'itemId' => $source->getId()),
			    $sessionParam),
		array('forceSessionId' => false, 'forceFullUrl' => true));

	    if (isset($thumbnails[$itemId])) {
		$entry['thumbUrl'] = $urlGenerator->generateUrl(
		    array_merge(array('view' => 'core.DownloadItem',
				      'itemId' => $thumbnails[$itemId]->getId()),
				$sessionParam),
		    array('forceSessionId' => false, 'forceFullUrl' => true));
		$entry['thumbWidth'] = $thumbnails[$itemId]->getWidth();
		$entry['thumbHeight'] = $thumbnails[$itemId]->getHeight();
	    }
	    $entry['imageWidth'] = $source->getWidth();
	    $entry['imageHeight'] = $source->getHeight();

	    /*
	     * Ugh, the Shutterfly api doesn't have a parameter for quantity.
	     * Repeat the same entry multiple times to get desired quantity!
	     */
	    for ($j = 0; $j < $cartItemIds[$itemId]; $j++) {
		$entries[$i++] = $entry;
	    }
	}

	/*
	 * Ugh, the Shutterfly api can only track its session data via some cookies
	 * (redirecting to a url with embedded session id won't work) so we must
	 * render a form and submit it.. here we set our own cookie that will be
	 * checked to ensure we submit our form only once.
	 */
	if (!headers_sent() /* Should only skip cookie in unit test */) {
	    setcookie('G2_shutterfly', '1');
	    header("Content-type: text/html; charset=UTF-8");
	}

	GalleryCoreApi::requireOnce('modules/core/classes/GalleryTemplate.class');
	$template = new GalleryTemplate(dirname(dirname(dirname(__FILE__))));
	$template->setVariable('l10Domain', 'modules_shutterfly');
	$template->setVariable('PrintPhotos', array('returnUrl' => $returnUrl,
						    'count' => count($entries),
						    'entries' => $entries));
	$template->display('gallery:modules/shutterfly/templates/PrintPhotos.tpl');

	return null;
    }
}
?>
