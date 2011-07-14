{*
 * $Revision: 1.31 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{foreach from=$ImageBlockData.blocks item=block}
<div class="one-image">
  {if !empty($block.title)}
    <h3> {g->text text=$block.title} </h3>
  {/if}

  {capture name="link"}
  <a href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$block.id`" forceFullUrl=$ImageBlockData.forceFullUrl}" {strip}
    {if !empty($ImageBlockData.linkTarget)}
      target="{$ImageBlockData.linkTarget}"
    {/if}{/strip}>
  {/capture}
  {if $block.item.canContainChildren}
    {assign var=frameType value="albumFrame"}
  {else}
    {assign var=frameType value="itemFrame"}
  {/if}
  {if array_key_exists('maxSize', $ImageBlockData)}
    {assign var=maxSize value=$ImageBlockData.maxSize}
  {elseif isset($ImageBlockData.$frameType) && $ImageBlockData.$frameType != 'none'}
    {assign var=maxSize value=120}
  {else}
    {assign var=maxSize value=150}
  {/if}
  {assign var=imageItem value=$block.item}
  {if isset($block.forceItem)}{assign var=imageItem value=$block.thumb}{/if}
  {if isset($ImageBlockData.$frameType)}
    {g->container type="imageframe.ImageFrame" frame=$ImageBlockData.$frameType
		  width=$block.thumb.width height=$block.thumb.height maxSize=$maxSize}
      {$smarty.capture.link}
	{g->image item=$imageItem image=$block.thumb id="%ID%" class="%CLASS%" maxSize=$maxSize forceFullUrl=$ImageBlockData.forceFullUrl}
      </a>
    {/g->container}
  {else}
    {$smarty.capture.link}
      {g->image item=$imageItem image=$block.thumb class="giThumbnail" maxSize=$maxSize forceFullUrl=$ImageBlockData.forceFullUrl}
    </a>
  {/if}

  {if isset($ImageBlockData.show.title) && isset($block.item.title)}
    <h4 class="giDescription">
      {$block.item.title|markup}
    </h4>
  {/if}

  {if isset($ImageBlockData.show.date) ||
      isset($ImageBlockData.show.views) ||
      isset($ImageBlockData.show.owner)}
    <p class="giInfo">
      {if isset($ImageBlockData.show.date)}
      <span class="summary">
	{g->text text="Date:"} {g->date timestamp=$block.item.originationTimestamp}
      </span>
      {/if}

      {if isset($ImageBlockData.show.views)}
      <span class="summary">
	{g->text text="Views: %d" arg1=$block.viewCount}
      </span>
      {/if}

      {if isset($ImageBlockData.show.owner)}
      <span class="summary">
	{g->text text="Owner: %s" arg1=$block.owner.fullName|default:$block.owner.userName}
      </span>
      {/if}
    </p>
  {/if}
 </div>
{/foreach}

