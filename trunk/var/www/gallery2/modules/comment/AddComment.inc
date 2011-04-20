<?php
/*
 * $RCSfile: AddComment.inc,v $
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
 * @version $Revision: 1.42 $ $Date: 2006/01/30 22:52:14 $
 * @package Comment
 * @subpackage UserInterface
 * @author Bharat Mediratta <bharat@menalto.com>
 */

/**
 * Add a comment to an item.
 *
 * @package Comment
 * @subpackage UserInterface
 *
 */
class AddCommentController extends GalleryController {
    /**
     * ValidationPlugin instances to use when handling this request.  Only used by test code.
     *
     * @var array $_plugins (array of GalleryValidationPlugin)
     * @access private
     */
    var $_pluginInstances;

    /**
     * @see GalleryController::handleRequest()
     */
    function handleRequest($form) {
	global $gallery;

	$itemId = GalleryUtilities::getRequestVariables('itemId');

	/* Make sure we have permission to add a comment */
	$ret = GalleryCoreApi::assertHasItemPermission($itemId, 'comment.add');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$redirect = $status = $error = array();
	if (isset($form['action']['add'])) {
	    GalleryCoreApi::requireOnce(
		'modules/comment/classes/GalleryCommentHelper.class');

	    if (empty($form['subject'])) {
		$error[] = 'form[error][subject][missing]';
	    }
	    if (empty($form['comment'])) {
		$error[] = 'form[error][comment][missing]';
	    }

	    list ($ret, $useValidationPlugins) = GalleryCommentHelper::useValidationPlugins();
	    if ($ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }
	    if (empty($error) && $useValidationPlugins) {
		if (isset($this->_pluginInstances)) {
		    $pluginInstances = $this->_pluginInstances;
		} else {
		    list ($ret, $pluginInstances) =
			GalleryCoreApi::getAllFactoryImplementationIds('GalleryValidationPlugin');
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    foreach (array_keys($pluginInstances) as $pluginId) {
			list ($ret, $pluginInstances[$pluginId]) =
			    GalleryCoreApi::newFactoryInstanceById('GalleryValidationPlugin',
								   $pluginId);
			if ($ret) {
			    return array($ret->wrap(__FILE__, __LINE__), null);
			}
		    }
		}

		/* Let each plugin do its verification */
		foreach ($pluginInstances as $plugin) {
		    list ($ret, $pluginErrors, $continue) = $plugin->performValidation($form);
		    if ($ret) {
			return array($ret->wrap(__FILE__, __LINE__), null);
		    }

		    $error = array_merge($error, $pluginErrors);
		    if (!$continue) {
			break;
		    }
		}
	    }

	    if (empty($error)) {
		/* Add the comment */
		list ($ret, $comment) =
		    GalleryCoreApi::newFactoryInstance('GalleryEntity', 'GalleryComment');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		if (!isset($comment)) {
		    return array(GalleryCoreApi::error(ERROR_MISSING_OBJECT, __FILE__, __LINE__),
				 null);
		}

		$ret = $comment->create($itemId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		list ($ret, $markup) =
		    GalleryCoreApi::getPluginParameter('module', 'core', 'misc.markup');
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if ($markup == 'html') {
		    /* Strip malicious content if html markup allowed */
		    $form['subject'] = GalleryUtilities::htmlSafe($form['subject'], true);
		    $form['comment'] = GalleryUtilities::htmlSafe($form['comment'], true);
		}

		$comment->setCommenterId($gallery->getActiveUserId());
		$comment->setHost(GalleryUtilities::getRemoteHostAddress());
		$comment->setSubject($form['subject']);
		$comment->setComment($form['comment']);
		$comment->setDate(time());

		$ret = $comment->save();
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}

		/* Send the user to a confirmation page, for now */
		$redirect['view'] = 'comment.CommentChangeConfirmation';
		$redirect['itemId'] = (int)$itemId;
		$status['added'] = 1;
	    }
	} else if (isset($form['action']['preview'])) {
	    if (empty($form['subject'])) {
		$error[] = 'form[error][subject][missing]';
	    }

	    if (empty($form['comment'])) {
		$error[] = 'form[error][comment][missing]';
	    }

	    /* Fall through back to the current view */
	} else if (isset($form['action']['cancel'])) {
	    /* Where to go on a cancel?  Back to viewing the item. */
	    $redirect['view'] = 'core.ShowItem';
	    $redirect['itemId'] = (int)$itemId;
	}

	/* Prepare our results */
	if (!empty($redirect)) {
	    $results['return'] = 1;
	    $results['redirect'] = $redirect;
	} else {
	    $results['delegate']['view'] = 'comment.AddComment';
	}
	$results['status'] = $status;
	$results['error'] = $error;

	return array(null, $results);
    }
}

/**
 * This view will show a form to add a new comment to an item
 *
 * @package Comment
 * @subpackage UserInterface
 *
 */
class AddCommentView extends GalleryView {

    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	GalleryCoreApi::requireOnce('modules/comment/classes/GalleryCommentHelper.class');

	/* Load our item */
	list ($ret, $item) = $this->_getItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	/* Make sure we have permission to add a comment */
	$ret = GalleryCoreApi::assertHasItemPermission($item->getId(), 'comment.add');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	if ($form['formName'] != 'AddComment') {
	    $form['formName'] = 'AddComment';
	    $form['subject'] = $form['comment'] = '';
	} else {
	    foreach (array('subject', 'comment') as $key) {
		if (empty($form[$key])) {
		    $form[$key] = '';
		}
	    }
	}

	/* Check if we should use validation plugins for new comments */
	$plugins = array();
	list ($ret, $useValidationPlugins) = GalleryCommentHelper::useValidationPlugins();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}
	if ($useValidationPlugins) {
	    list ($ret, $allPluginIds) =
		GalleryCoreApi::getAllFactoryImplementationIds('GalleryValidationPlugin');
	    if ( $ret) {
		return array($ret->wrap(__FILE__, __LINE__), null);
	    }

	    /* Let each plugin load its template data */
	    foreach (array_keys($allPluginIds) as $pluginId) {
		list ($ret, $plugin) =
		    GalleryCoreApi::newFactoryInstanceById('GalleryValidationPlugin', $pluginId);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		list ($ret, $data['file'], $data['l10Domain']) = $plugin->loadTemplate($form);
		if ($ret) {
		    return array($ret->wrap(__FILE__, __LINE__), null);
		}
		if (isset($data['file'])) {
		    $plugins[] = $data;
		}
	    }
	}

	$template->setVariable('AddComment',
	    array('itemId' => $item->getId(),
		  'host' => GalleryUtilities::getRemoteHostAddress(),
		  'plugins' => $plugins));
	$template->setVariable('controller', 'comment.AddComment');

	list($ret, $module) = GalleryCoreApi::loadPlugin('module', 'comment');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$template->title($module->translate('Add Comment'));

	return array(null,
		     array('body' => 'modules/comment/templates/AddComment.tpl'));
    }

    /**
     * @see GalleryView::getViewDescription()
     */
    function getViewDescription() {
	list ($ret, $module) = GalleryCoreApi::loadPlugin('module', 'comment');
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	return array(null, $module->translate('add comment'));
    }
}
?>
