<?php
/*
 * $RCSfile: Preloads.inc,v $
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
 * @version $Revision: 1.6 $ $Date: 2006/01/10 04:40:16 $
 * @package ImageBlock
 * @author Bharat Mediratta <bharat@menalto.com>
 */
class ImageBlockPreloads {
    function preload(&$template, $preload, $paramSet) {
	global $gallery;

	switch($preload) {
	case 'ImageBlock':
	    list ($ret, $moduleParams) =
		GalleryCoreApi::fetchAllPluginParameters('module', 'imageblock');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    $frameIds = array();
	    foreach ($paramSet as $params) {
		foreach (array('itemFrame', 'albumFrame', 'photoFrame') as $key) {
		    if (isset($params[$key])) {
			$frameIds[$params[$key]] = 1;
		    } else if (isset($moduleParams[$key])) {
			$frameIds[$moduleParams[$key]] = 1;
		    }
		}
	    }

	    if (!empty($frameIds)) {
		$frameIds = array_keys($frameIds);
		list ($ret, $imageframe) =
		    GalleryCoreApi::newFactoryInstance('ImageFrameInterface_1_1');
		if ($ret) {
		    return $ret->wrap(__FILE__, __LINE__);
		}

		if (isset($imageframe)) {
		    $ret = $imageframe->init($template, $frameIds);
		    if ($ret) {
			if ($ret->getErrorCode() & ERROR_PLUGIN_VERSION_MISMATCH) {
			    /* It's there, but unusable so ignore it. */
			} else {
			    return $ret->wrap(__FILE__, __LINE__);
			}
		    }
		}
	    }

	    return null;
	}


	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }
}
?>
