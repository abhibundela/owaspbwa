{*
 * $Revision: 1.5 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{assign var="lowercase" value=$lowercase|default:false}
{* if we have more than one link, use a dropdown if $useDropdown is set *}
{* one link, just show it as a link *}
{if (isset($links) || isset($theme.itemLinks))}
  {if empty($item)}{assign var="item" value=$theme.item}{/if}
  {if !isset($links)}{assign var="links" value=$theme.itemLinks}{/if}
  {if !isset($useDropdown)}{assign var="useDropdown" value=true}{/if}

  {if $useDropdown && count($links) > 1}
  <div class="{$class}">
    <select onchange="{literal}if (this.value) { newLocation = this.value; this.options[0].selected = true; location.href= newLocation; }{/literal}">
      <option label="{if $item.canContainChildren}{g->text text="&laquo; album actions &raquo;"}{else}{g->text text="&laquo; item actions &raquo;"}{/if}" value="">{if $item.canContainChildren}{g->text text="&laquo; album actions &raquo;"}{else}{g->text text="&laquo; item actions &raquo;"}{/if}</option>
      {foreach from=$links item=link}
	{if $lowercase}{assign var="linkText" value=$link.text|lower}{else}
		       {assign var="linkText" value=$link.text}{/if}
	<option label="{$linkText}" value="{g->url params=$link.params}">{$linkText}</option>
      {/foreach}
    </select>
  </div>
  {elseif count($links) > 0}
  <div class="{$class}">
    {foreach from=$links item=link}
      <a href="{g->url params=$link.params}" class="gbAdminLink {g->linkid
	 urlParams=$link.params}">{if $lowercase}{$link.text|lower}{else}{$link.text}{/if}</a>
    {/foreach}
  </div>
  {/if}
{/if}
