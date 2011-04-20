{*
 * $Revision: 1.5 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="{$class}">
  {if !empty($showDate)}
  <span class="date summary">
    {capture name=childTimestamp}{g->date timestamp=$item.originationTimestamp}{/capture}
    {g->text text="Date: %s" arg1=$smarty.capture.childTimestamp}
  </span>
  {/if}

  {if !empty($showOwner)}
  <span class="owner summary">
    {g->text text="Owner: %s" arg1=$item.owner.fullName|default:$item.owner.userName}
  </span>
  {/if}

  {if !empty($showSize) && $item.canContainChildren && $item.childCount > 0}
  <span class="size summary">
    {g->text one="Size: %d item"
	     many="Size: %d items"
	     count=$item.childCount
	     arg1=$item.childCount}
    {if $item.descendentCount > $item.childCount}
    {g->text one="(%d item total)"
	     many="(%d items total)"
	     count=$item.descendentCount
	     arg1=$item.descendentCount}
    {/if}
  </span>
  {/if}

  {if !empty($showViewCount) && $item.viewCount > 0}
  <span class="viewCount summary">
    {g->text text="Views: %d" arg1=$item.viewCount}
  </span>
  {/if}

  {if !empty($showSummaries)}
  {foreach from=$item.itemSummaries key=name item=summary}
  <span class="summary-{$name} summary">
    {$summary}
  </span>
  {/foreach}
  {/if}
</div>
