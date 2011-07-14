<?php
/*
 * $RCSfile: EditComment.inc,v $
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
 * @version $Revision: 1.27 $ $Date: 2006/01/14 01:42:51 $
 * @package Comment
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * This controller will handle the changes made to a comment
 *
 * @package Comment
 * @subpackage UserInterface
 */
class EditCommentController extends GalleryController {

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	global $gallery;

	list ($itemId, $commentId) = GalleryUtilities::getRequestVariables('itemId', 'commentId');

	/* Make sure we have permission to edit a comment */
	$ret = GalleryCoreApi::assertHasItemPermission($itemId, 'comment.edit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$status = array();
	$error = array();
	if (isset($form['action']['save'])) {
	    /* Edit the comment */
	    list ($ret, $comment) = GalleryCoreApi::loadEntitiesById($commentId);
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Make sure that the comment<->parent relationship is right */
	    if ($comment->getParentId() != $itemId) {
		return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	    }

	    /* Verify the input parameters */
	    if (empty($form['commenterName'])) {
		$error[] = 'form[error][commenterName][missing]';
	    } else {
		list ($ret, $commenter) =
		    GalleryCoreApi::fetchUserByUsername($form['commenterName']);
		if ($ret) {
		    $error[] = 'form[error][commenterName][invalid]';
		}
	    }

	    $longDate = null;
	    if (empty($form['date'])) {
		$error[] = 'form[error][date][missing]';
	    } else {
		/*
		 * Verify that the date is at least 2 characters long.
		 * This is to get around an apparent bug in strtotime
		 * which seems to read single characters as valid dates
		 * (with the exception of 'j')
		 */
		if (strlen($form['date']) < 2) {
		    $longDate = -1;
		} else {
		    $longDate = strtotime($form['date']);
		}
		if ($longDate == -1 || $longDate === false) {
		    $error[] = 'form[error][date][invalid]';
		}
	    }

	    if (empty($form['subject'])) {
		$error[] = 'form[error][subject][missing]';
	    }

	    if (empty($form['comment'])) {
		$error[] = 'form[error][comment][missing]';
	    }

	    if (empty($error)) {
		list ($ret, $markup) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'misc.markup');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $lockId) = GalleryCoreApi::acquireWriteLock(array($comment->getId()));
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $comment) = $comment->refresh();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if ($markup == 'html') {
		    /* Strip malicious content if html markup allowed */
		    $form['subject'] = GalleryUtilities::htmlSafe($form['subject'], true);
		    $form['comment'] = GalleryUtilities::htmlSafe($form['comment'], true);
		}

		$comment->setCommenterId($commenter->getId());
		$comment->setHost($form['host']);
		$comment->setSubject($form['subject']);
		$comment->setComment($form['comment']);
		$comment->setDate($longDate);
		$ret = $comment->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$ret = GalleryCoreApi::releaseLocks($lockId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		$status['changed'] = 1;
		$redirect['view'] = 'comment.CommentChangeConfirmation';
		$redirect['itemId'] = (int)$itemId;
	    }
	} else if (isset($form['action']['preview'])) {
	    /* Verify the input parameters */
	    if (empty($form['commenterName'])) {
		$error[] = 'form[error][commenterName][missing]';
	    } else {
		list ($ret, $commenter) =
		    GalleryCoreApi::fetchUserByUsername($form['commenterName']);
		if ($ret) {
		    $error[] = 'form[error][commenterName][invalid]';
		}
	    }

	    $longDate = null;
	    if (empty($form['date'])) {
		$error[] = 'form[error][date][missing]';
	    } else {
		/*
		 * Verify that the date is at least 2 characters long.
		 * This is to get around an apparent bug in strtotime
		 * which seems to read single characters as valid dates
		 * (with the exception of 'j')
		 */
		if (strlen($form['date']) < 2) {
		    $longDate = -1;
		} else {
		    $longDate = strtotime($form['date']);
		}
		if ($longDate == -1 || $longDate === false) {
		    $error[] = 'form[error][date][invalid]';
		}
	    }

	    if (empty($form['subject'])) {
		$error[] = 'form[error][subject][missing]';
	    }

	    if (empty($form['comment'])) {
		$error[] = 'form[error][comment][missing]';
	    }

	    /* Fall through back to the current view */
	} else if (isset($form['action']['cancel'])) {
	    /* Where to go on a cancel?  View the item.*/
	    $redirect['view'] = 'core.ShowItem';
	    $redirect['itemId'] = (int)$itemId;
	}

	if (!empty($redirect)) {
	    /* Redirect back to the confirmation page by default */
	    $results['return'] = 1;
	    $results['redirect'] = $redirect;
	} else {
	    /* Stay on the same page */
	    $results['delegate']['view'] = 'comment.EditComment';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view shows a form to edit a comment
 *
 * @package Comment
 * @subpackage UserInterface
 *
 */
class EditCommentView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;
	$platform =& $gallery->getPlatform();

	/* Load our item */
	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Make sure we have permission to edit a comment */
	$ret = GalleryCoreApi::assertHasItemPermission($item->getId(), 'comment.edit');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$commentId = GalleryUtilities::getRequestVariables('commentId');

	/* Load the specific comment */
	list ($ret, $comment) = GalleryCoreApi::loadEntitiesById($commentId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Make sure the comment is associated with the item */
	if ($comment->getParentId() != $item->getId()) {
	    return array(GalleryCoreApi::error(ERROR_BAD_PARAMETER, __FILE__, __LINE__), null);
	}

	/* Associate the commenter's info */
	$commenterId = $comment->getCommenterId();
	list ($ret, $commenter) = GalleryCoreApi::loadEntitiesById($commenterId);
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'EditComment') {
	    $form['commenterName'] = $commenter->getUserName();
	    $form['host'] = $comment->getHost();
	    $form['comment'] = $comment->getComment();
	    $form['subject'] = $comment->getSubject();
	    $form['formName'] = 'EditComment';

	    /* XXX: this needs internationalization */
	    $form['date'] = $platform->strftime('%D %T', $comment->getDate());
	}

	$EditComment['comment'] = (array)$comment;
	$EditComment['itemId'] = $item->getId();

	$template->setVariable('EditComment', $EditComment);
	$template->setVariable('controller', 'comment.EditComment');

	return array(null,
		     array('body' => 'modules/comment/templates/EditComment.tpl'));
    }
}
?>
