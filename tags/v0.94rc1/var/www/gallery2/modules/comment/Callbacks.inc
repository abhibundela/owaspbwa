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
 * @version $Revision: 1.4 $ $Date: 2006/01/10 04:39:11 $
 * @package Comment
 * @author Bharat Mediratta <bharat@menalto.com>
 */
class CommentCallbacks {

    /**
     */
    function callback($params, &$smarty, $callback, $userId) {

	switch ($callback) {
	case 'LoadComments':
	    $itemId = $params['itemId'];
	    list ($ret, $item) = GalleryCoreApi::loadEntitiesById($itemId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $permissions) =
		GalleryCoreApi::fetchPermissionsForItems(array($itemId), $userId);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    if (!isset($permissions[$itemId]['comment.view'])) {
		return null;
	    }

	    $viewMax = $params['show'];

	    GalleryCoreApi::requireOnce('modules/comment/classes/GalleryCommentHelper.class');
	    list ($ret, $comments) =
		GalleryCommentHelper::fetchComments($item->getId(), $viewMax, ORDER_DESCENDING);
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    list ($ret, $counts) =
		GalleryCommentHelper::fetchCommentCounts(array($item->getId()));
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    $commenters = array();
	    if (isset($counts[$item->getId()])) {
		$totalComments = $counts[$item->getId()];

		for ($i = 0; $i < sizeof($comments); $i++) {
		    /* Get the commenter ids */
		    $commenters[$comments[$i]->getCommenterId()] = 1;

		    /* Extract the member data */
		    $comments[$i] = (array)$comments[$i];
		}

		/* Load all the commenters */
		if (sizeof($commenters) > 0) {
		    list ($ret, $commentersList) =
			GalleryCoreApi::loadEntitiesById(array_keys($commenters));
		    if ($ret) {
			return $ret->wrap(__FILE__, __LINE__);
		    }
		}

		foreach ($commentersList as $commenter) {
		    $commenters[$commenter->getId()] = (array)$commenter;
		}
	    } else {
		$totalComments = 0;
	    }

	    list ($ret, $permissions) = GalleryCoreApi::getPermissions($item->getId());
	    if ($ret) {
		return $ret->wrap(__FILE__, __LINE__);
	    }

	    foreach (array('delete' => 'comment.delete',
			   'edit' => 'comment.edit',
			   'view' => 'comment.view')
		     as $canFlag => $permission) {
		$can[$canFlag] = isset($permissions[$permission]);
	    }

	    $LoadComments = array();
	    $LoadComments['comments'] = $comments;
	    $LoadComments['commenters'] = $commenters;
	    $LoadComments['can'] = $can;
	    $LoadComments['item'] = (array)$item;
	    $LoadComments['totalComments'] = $totalComments;

	    $block =& $smarty->_tpl_vars['block'];
	    $block['comment']['LoadComments'] = $LoadComments;

	    return null;
	}

	return GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__);
    }
}
?>
