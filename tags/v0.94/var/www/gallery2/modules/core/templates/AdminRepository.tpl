{*
 * $Revision: 1.6 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Repository"} </h2>
</div>

{if !empty($status.error)}
<div class="gbBlock"><h2 class="giError">
  {$status.error}
  {g->text text="Please make sure that your internet connection is set up properly or try again later."}
</h2></div>
{/if}

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
  {if isset($status.indexUpdated)}
    {g->text text="The repository index has been successfully updated."}
  {elseif isset($status.noUpgradeAvailable)}
    {g->text text="All plugins are already up-to-date."}
  {/if}
</h2></div>
{/if}

<div class="gbTabBar">
  {if ($AdminRepository.mode == 'commonTasks')}
    <span class="giSelected o"><span>
	{g->text text="Common Tasks"}
    </span></span>
  {else}
    <span class="o"><span>
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminRepository"
		       arg3="mode=commonTasks"}">{g->text text="Common Tasks"}</a>
    </span></span>
  {/if}

  {if ($AdminRepository.mode == 'browseModules')}
    <span class="giSelected o"><span>
      {g->text text="Modules"}
    </span></span>
  {else}
    <span class="o"><span>
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminRepository"
		       arg3="mode=browseModules"}">{g->text text="Modules"}</a>
    </span></span>
  {/if}

  {if ($AdminRepository.mode == 'browseThemes')}
    <span class="giSelected o"><span>
      {g->text text="Themes"}
    </span></span>
  {else}
    <span class="o"><span>
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminRepository"
		       arg3="mode=browseThemes"}">{g->text text="Themes"}</a>
    </span></span>
  {/if}
</div>

{if ($AdminRepository.mode == 'commonTasks')}
<div class="gbBlock">
  <h3>{g->text text="Warning: Experimental feature!"}</h3>
  <p class="giDescription">
    {g->text text="The repository features are currently experimental, and no actual repository has been set up yet, so none of these features will work at this time."}
  </p>
  <h3>{g->text text="Update Index"}</h3>
  <p class="giDescription">
    {g->text text="The Gallery repository contains the latest modules and themes extensively tested by the Gallery team. The repository index contains information about available plugins, such as the latest versions, available languages and compatibility. The index must be synchronized periodically with the Gallery server so you are informed about any available updates. No personal information is sent to the Gallery server during updating. On slower connections the process might take a minute or two."}
  </p>
  {if isset($indexMetaData)}
  <p class="giDescription">
    {capture assign="updateDate"}{g->date style="datetime" timestamp=$indexMetaData.timestamp}{/capture}
    {g->text text="As of the last update on %s, the repository contains %s modules and %s themes. Its contents can be viewed on the Modules and Themes tabs." arg1=$updateDate arg2=$indexMetaData.moduleCount arg3=$indexMetaData.themeCount}
  </p>
  {else}
  <p class="giDescription">
    {g->text text="The index has never been updated. Click on the Update button to see what updates are available."}
  </p>
  {/if}
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit" name="{g->formVar var="form[action][update]"}" value="{g->text text="Update"}"/>
</div>

  {if isset($indexMetaData)}
    {if $coreUpgradeAvailable}
<div class="gbBlock">
  <h3>{g->text text="Upgrade Gallery"}</h3>
  <p class="giDescription">
    {g->text text="A new version of Gallery is available, but it cannot be upgraded through this interface. Upgrading it might make some of your current plugins stop working, but others that rely on the features of the new version may become available. Here are the recommended steps for upgrading:"}
  </p>
  <p>
    <ol>
      <li>{g->text text="Review plugin compatibility (on the Themes and Modules tabs)"}</a></li>
      <li>{g->text text="%sDownload%s Gallery core" arg1="<a href=\"http://codex.gallery2.org/index.php/Download\">" arg2="</a>"}</li>
      <li>{g->text text="Read the %supgrade instructions%s and perform the upgrade" arg1="<a href=\"http://codex.gallery2.org/index.php/CoreUpgradeInstructions\">" arg2="</a>"}</li>
    </ol>
  </p>
</div>
    {else}
<div class="gbBlock">
  <h3>{g->text text="Gallery Up-To-Date"}</h3>
  <p class="giDescription">
    {g->text text="Gallery cannot be upgraded through this interface. When a new version becomes available, upgrade instructions will be presented here."}
</div>
    {/if}

<div class="gbBlock">
  <h3>{g->text text="Upgrade All Plugins"}</h3>
  <p class="giDescription">
    {g->text text="Gallery can automatically upgrade your themes and modules to the latest available versions. No new plugins will be downloaded."}
  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" name="{g->formVar var="form[action][upgradeAll]"}" value="{g->text text="Upgrade All"}"/>
</div>
  {/if}
{/if}

{if ($AdminRepository.mode == 'browseThemes' || $AdminRepository.mode == 'browseModules')}
<div class="gbBlock">
  {if !isset($browseData)}
  <p class="giDescription">
    {g->text text="Once the repository index has been downloaded, a list of available plugins will be presented. It can be downloaded by clicking on the Update button on the Common Tasks tab."}
  </p>
  {else}
  <p class="giDescription">
    {g->text text="The following plugins are available. Click on the action beside the plugin you're interested in to see what's available in the repository."}
    {if $coreUpgradeAvailable}
      {if $showIncompatible}
        {g->text text="Incompatible plugins are marked with an exclamation icon."}
      {else}
	{capture name="listLink"}<a href="{g->url arg1="view=core.SiteAdmin"
	  arg2="subView=core.AdminRepository" arg3="mode=`$AdminRepository.mode`"
	  arg4="coreApi=`$latestCoreApiVersion`" arg5="themeApi=`$latestThemeApiVersion`"
	  arg6="moduleApi=`$latestModuleApiVersion`" arg7="showIncompatible=true"}">{/capture}
	{g->text text="A new core module version is available. There may be plugins that are incompatible with the installed core module, which are not shown here. You can view a %scomplete list%s of plugins, including incompatible ones, which are marked with a red icon." arg1=$smarty.capture.listLink arg2="</a>"}
      {/if}
    {/if}
  </p>
  <table class="gbDataTable">
    {assign var="group" value=""}
    {foreach from=$browseData key=pluginId item=plugin}
      {if $group != $plugin.groupLabel}
	{if !empty($group)}
	  <tr><td> &nbsp; </td></tr>
	{/if}
	<tr>
	  <th colspan="6"><h2>{$plugin.groupLabel}</h2></th>
	</tr><tr>
	  <th> &nbsp; </th>
    {if $plugin.type == 'themes'}
	  <th> {g->text text="Theme Name"} </th>
    {else}
	  <th> {g->text text="Module Name"} </th>
    {/if}
	  <th> {g->text text="Latest"} </th>
	  <th> {g->text text="Installed"} </th>
	  <th> {g->text text="Description"} </th>
	  <th> {g->text text="Actions"} </th>
	</tr>
      {/if}
      {assign var="group" value=$plugin.groupLabel}

      <tr class="{cycle values="gbEven,gbOdd"}">
	<td>
	  {if !$plugin.isCompatible}
	  <img src="{g->url href="modules/core/data/module-incompatible.gif"}" width="13"
	       height="13" alt="{g->text text="Incompatible Plugin"}" />
	  {/if}
	</td>

	<td>
	  {$plugin.name}
	</td>

	<td align="center">
	  {$plugin.repositoryVersion}
	</td>

	<td align="center">
	  {$plugin.localVersion}
	</td>

	<td>
	  {$plugin.description}
	  {if !$plugin.isCompatible}
	    <br/>
	    <span class="giError">
	      {g->text text="Incompatible plugin!"}
	      {if $plugin.api.required.core != $plugin.api.provided.core}
		<br/>
		{g->text text="Core API Required: %s (available: %s)"
			 arg1=$plugin.api.required.core arg2=$plugin.api.provided.core}
	      {/if}
	      {if $plugin.api.required.plugin != $plugin.api.provided.plugin}
		<br/>
		{g->text text="Plugin API Required: %s (available: %s)"
			 arg1=$plugin.api.required.plugin arg2=$plugin.api.provided.plugin}
	      {/if}
	    </span>
	  {/if}
	</td>

	<td>
	  {if !empty($plugin.action) && $plugin.isCompatible}
	    {strip}
		<a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminRepository" arg3="mode=download" arg4="pluginType=`$plugin.type`" arg5="pluginId=`$pluginId`"}">
		  {$plugin.action}
		</a>
	    {/strip}
	  {else}
	    &nbsp;
	  {/if}
	</td>
      </tr>
    {/foreach}
  </table>
  {/if}
</div>
{/if}
