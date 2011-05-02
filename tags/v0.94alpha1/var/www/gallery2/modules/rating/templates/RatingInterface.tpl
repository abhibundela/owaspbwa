{*
 * $Revision: 1.3 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if $RatingSummary.firstCall}
{include file="gallery:modules/rating/templates/RatingImagePreload.tpl"}
{/if}

<div class="giRatingUI">
	{foreach from=$RatingSummary.ratingValues item=ratingValue}{if $RatingData.canRate}<a
		href="javascript:rateItem({$RatingData.itemId}, {$ratingValue},
		'{g->url arg1="view=rating.RatingCallback" arg2="command=rate"
			arg3="itemId=`$RatingData.itemId`"
			arg4="rating=$ratingValue" forJavascript=true}')"
		onMouseOver="updateStarDisplay({$RatingData.itemId}, {$ratingValue}); return true"
		onMouseOut="resetStarDisplay({$RatingData.itemId}); return true">{/if}<img
		src="{g->url href="modules/rating/images/transparent.gif"}"
		id="rating.star.{$RatingData.itemId}.{$ratingValue}" class="giRatingUnit"
		alt=""
		title="Click a star to rate this item!"/>{if $RatingData.canRate}</a>{/if}{/foreach}
	<div class="giRatingAverageContainer">
		<div class="giRatingAverage" id="rating.averagePercent.{$RatingData.itemId}"
			style="width:{$RatingData.averagePercent}%"></div></div>
	<div class="giRatingVotes">{capture assign="voteSpan"}<span
		id="rating.votes.{$RatingData.itemId}">{$RatingData.votes}</span>{/capture}{g->text
		text="%s votes" arg1=$voteSpan}</div>
	<span class="giRatingHidden"
		id="rating.rating.{$RatingData.itemId}">{$RatingData.rating}</span>
	<span class="giRatingHidden"
		id="rating.userRating.{$RatingData.itemId}">{$RatingData.userRating}</span>
</div>

<script type="text/javascript">
// <![CDATA[
resetStarDisplay({$RatingData.itemId});
// ]]>
</script>
