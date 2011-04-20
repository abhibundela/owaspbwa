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
 * @version $Revision: 1.6 $ $Date: 2006/01/10 04:38:59 $
 * @package Cart
 * @author Bharat Mediratta <bharat@menalto.com>
 */
class CartCallbacks {
    /**
     */
    function callback($params, &$smarty, $callback, $userId=null) {
	global $gallery;

	switch($callback) {
	case 'LoadCart':
	    /* The cart contents' count */
	    GalleryCoreApi::requireOnce('modules/cart/classes/CartHelper.class');
	    list ($ret, $cartItemIds) = CartHelper::fetchCartItemCounts();
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $unique = array_keys($cartItemIds);
	    $total = 0;
	    foreach ($cartItemIds as $itemId => $count) {
		$total += $count;
	    }

	    $block =& $smarty->_tpl_vars['block'];
	    $block['cart']['ShowCart']['unique'] = $unique;
	    $block['cart']['ShowCart']['total'] = $total;

	    return null;
	}

	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }
}
?>
