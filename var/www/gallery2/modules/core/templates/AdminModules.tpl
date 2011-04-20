{*
 * $Revision: 1.36 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Gallery Modules"} </h2>
</div>

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
  {if isset($status.installed)}
    {if !empty($status.autoConfigured)}
      {g->text text="Successfully installed and auto-configured module %s" arg1=$status.installed}
    {else}
      {g->text text="Successfully installed module %s" arg1=$status.installed}
    {/if}
  {/if}
  {if isset($status.configured)}
    {g->text text="Successfully configured module %s" arg1=$status.configured}
  {/if}
  {if isset($status.upgraded)}
    {g->text text="Successfully upgraded module %s" arg1=$status.upgraded}
  {/if}
  {if isset($status.activated)}
    {g->text text="Successfully activated module %s" arg1=$status.activated}
  {/if}
  {if isset($status.deactivated)}
    {g->text text="Successfully deactivated module %s" arg1=$status.deactivated}
  {/if}
  {if isset($status.uninstalled)}
    {g->text text="Successfully uninstalled module %s" arg1=$status.uninstalled}
  {/if}
</h2></div>
{/if}

<div class="gbBlock">
  <p class="giDescription">
    {g->text text="Gallery features come as separate modules.  You can download and install modules to add more features to your Gallery, or you can disable features if you don't want to use them.  In order to use a feature, you must install, configure (if necessary) and activate it.  If you don't wish to use a feature, you can deactivate it."}
  </p>

  <table class="gbDataTable">
    {assign var="group" value=""}
    {foreach from=$AdminModules.modules item=module}
      {if $group != $module.group}
	{if !empty($group)}
	  <tr><td> &nbsp; </td></tr>
	{/if}
	<tr>
	  <th colspan="6"><h2>{$module.groupLabel}</h2></th>
	</tr><tr>
	  <th> &nbsp; </th>
	  <th> {g->text text="Module Name"} </th>
	  <th> {g->text text="Version"} </th>
	  <th> {g->text text="Installed"} </th>
	  <th> {g->text text="Description"} </th>
	  <th> {g->text text="Actions"} </th>
	</tr>
      {/if}
      {assign var="group" value=$module.group}

      <tr class="{cycle values="gbEven,gbOdd"}">
	<td>
	  {if $module.state == 'install'}
	  <img src="{g->url href="modules/core/data/module-install.gif"}" width="13" height="13"
	       alt="{g->text text="Status: Not Installed"}" />
	  {/if}
	  {if $module.state == 'active'}
	  <img src="{g->url href="modules/core/data/module-active.gif"}" width="13" height="13"
	       alt="{g->text text="Status: Active"}" />
	  {/if}
	  {if $module.state == 'inactive'}
	  <img src="{g->url href="modules/core/data/module-inactive.gif"}" width="13" height="13"
	       alt="{g->text text="Status: Inactive"}" />
	  {/if}
	  {if $module.state == 'upgrade'}
	  <img src="{g->url href="modules/core/data/module-upgrade.gif"}" width="13" height="13"
	       alt="{g->text text="Status: Upgrade Required (Inactive)"}" />
	  {/if}
	  {if $module.state == 'incompatible'}
	  <img src="{g->url href="modules/core/data/module-incompatible.gif"}" width="13"
	       height="13" alt="{g->text text="Status: Incompatible Module (Inactive)"}" />
	  {/if}
	</td>

	<td>
	  {$module.name}
	</td>

	<td align="center">
	  {$module.version}
	</td>

	<td align="center">
	  {$module.installedVersion}
	</td>

	<td>
	  {$module.description}
	  {if $module.state == 'incompatible'}
	    <br/>
	    <span class="giError">
	      {g->text text="Incompatible module!"}
	      {if $module.api.required.core != $module.api.provided.core}
		<br/>
		{g->text text="Core API Required: %s (available: %s)"
			 arg1=$module.api.required.core arg2=$module.api.provided.core}
	      {/if}
	      {if $module.api.required.module != $module.api.provided.module}
		<br/>
		{g->text text="Module API Required: %s (available: %s)"
			 arg1=$module.api.required.module arg2=$module.api.provided.module}
	      {/if}
	    </span>
	  {/if}
	</td>

	<td>
	  {if (!empty($module.action))}
	    {foreach name=actions from=$module.action item=action}{strip}
	      {if !$smarty.foreach.actions.first}
		<br/>
	      {/if}
	      {if (empty($action.controller)) }
		<a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=`$action.view`"}">
		  {$action.text}
		</a>
	      {else}
		<a href="{g->url arg1="controller=`$action.controller`" arg2="moduleId=`$action.moduleId`" arg3="action=`$action.action`"}">
		  {$action.text}
		</a>
	      {/if}
	    {/strip}{/foreach}
	  {else}
	    &nbsp;
	  {/if}
	</td>
      </tr>
    {/foreach}
  </table>
</div>
