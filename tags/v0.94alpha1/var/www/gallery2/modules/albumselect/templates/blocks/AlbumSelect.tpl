{*
 * $Revision: 1.8 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{g->callback type="albumselect.LoadAlbumData" stripTitles="true" truncateTitles="20" createTextTree="true"}

{if isset($block.albumselect)}
<div class="{$class}">
  <select onchange="if (this.value) {ldelim} var newLocation = new String('{g->url arg1="view=core.ShowItem" arg2="itemId=__ID__"}').replace('__ID__', this.value); this.options[0].selected = true; location.href = newLocation; {rdelim}">
    <option value="">
      {g->text text="&laquo; Jump to Album &raquo;"}
    </option>
    {foreach from=$block.albumselect.LoadAlbumData.albumSelect.tree item=node}
      {assign var="title" value=$block.albumselect.LoadAlbumData.albumSelect.titles[$node.id]}
      <option value="{$node.id}">
	{$title}
      </option>
    {/foreach}
  </select>
</div>
{/if}
