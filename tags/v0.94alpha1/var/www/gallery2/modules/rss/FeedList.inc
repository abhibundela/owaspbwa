<?php
/*
 * $RCSfile: FeedList.inc,v $
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
 * @version $Revision: 1.2 $ $Date: 2006/02/09 17:25:09 $
 * @package RSS
 * @author Jonatan Heyman <http://heyman.info>
 * @author Pierre-Luc Paour
 * @author Daniel Grund <http://www.photogrund.nl>
 */

GalleryCoreApi::requireOnce('modules/rss/classes/RssMapHelper.class');

/**
 * This view will show a list of feeds.
 *
 * @package RSS
 * @subpackage UserInterface
 */
class FeedListView extends GalleryView {
    /**
     * @see GalleryView::loadTemplate
     */
    function loadTemplate(&$template, &$form) {
	global $gallery;

	list ($ret, $feeds) = RssMapHelper::fetchFeedsForItem();
	if ($ret) {
	    return array($ret->wrap(__FILE__, __LINE__), null);
	}

	$FeedList['feeds'] = $feeds;

	$template->setVariable('FeedList', $FeedList);
	$template->setVariable('view', 'rss.FeedList');
	return array(null,
	    array('body' => 'modules/rss/templates/FeedList.tpl'));
    }
}
?>
