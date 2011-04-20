{*
 * $Revision: 1.43 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<form action="{g->url}" method="post" id="siteAdminForm"
      enctype="{$SiteAdmin.enctype|default:"application/x-www-form-urlencoded"}">
  <div>
    {g->hiddenFormVars}
    <input type="hidden" name="{g->formVar var="controller"}" value="{$controller}"/>
    <input type="hidden" name="{g->formVar var="form[formName]"}" value="{$form.formName}" />
  </div>

  <table width="100%" cellspacing="0" cellpadding="0">
    <tr valign="top">
    <td id="gsSidebarCol"><div id="gsSidebar" class="gcBorder1">
      <div class="gbBlock">
	<h2> {g->text text="Admin Options"} </h2>
	<ul>
	  {foreach from=$SiteAdmin.subViewGroups item=group}
	  <li> <span>{$group.0.groupLabel}</span>
	    <ul>
	      {foreach from=$group item=choice}
		<li class="gbAdminLink {g->linkId urlParams=$choice}">
		{if ($SiteAdmin.subViewName == $choice.view)}
		  {$choice.name}
		{else}
		  <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=`$choice.view`"}">
		    {$choice.name}
		  </a>
		{/if}
	      </li>
	      {/foreach}
	    </ul>
	  </li>
	  {/foreach}
	</ul>
      </div>

      {g->block type="core.NavigationLinks" class="gbBlock"
		navigationLinks=$SiteAdmin.navigationLinks}
    </div></td>

    <td>
      <div id="gsContent" class="gcBorder1">
	{include file="gallery:`$SiteAdmin.viewBodyFile`" l10Domain=$SiteAdmin.viewL10Domain}
      </div>
    </td>
  </tr></table>
</form>
