{*
 * $Revision: 1.4 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div id="gsSidebar" class="inner gcBorder1 gcBackground3" style="overflow: auto;">
  <a href="javascript:return true;" id="hideSidebarTab"
      onclick="MM_changeProp('gsSidebarCol','','style.display','none','DIV');
            MM_changeProp('showSidebarTab','','style.display','block','DIV');
            return false;">
      <img src="{$theme.themeUrl}/images/tab_close_sidebar.gif" alt="Hide album options"/></a>
  {* Show the sidebar blocks chosen for this theme *}
  {foreach from=$theme.params.sidebarBlocks item=block}
    {g->block type=$block.0 params=$block.1 class="gbBlock"}
  {/foreach}
  {g->block type="core.NavigationLinks" class="gbBlock"}
</div>
<!--[if lte IE 6.5]><iframe></iframe><![endif]-->
