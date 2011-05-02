<?php
/*
 * $RCSfile: Callbacks.inc,v $
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
 * @version $Revision: 1.5 $ $Date: 2006/01/10 04:39:36 $
 * @package CustomField
 * @author Alan Harder <alan.harder@sun.com>
 */
class CustomFieldCallbacks {
    /**
     */
    function callback($params, &$smarty, $callback, $userId=null) {
	global $gallery;
	GalleryCoreApi::requireOnce('modules/customfield/classes/CustomFieldHelper.class');

	switch ($callback) {
	case 'LoadCustomFields':
	    list ($ret, $item) = GalleryCoreApi::loadEntitiesById($params['itemId']);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }
	    list ($ret, $fields) = CustomFieldHelper::fetchFieldValues(array($item), 'detail');
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $block =& $smarty->_tpl_vars['block'];
	    $block['customfield']['LoadCustomFields']['fields'] = 
		isset($fields[$item->getId()]) ? $fields[$item->getId()] : array();

	    return null;
	}

	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }
}
?>
