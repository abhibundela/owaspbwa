{*
 * $Revision: 1.9 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Gallery Themes"} </h2>
</div>

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
  {if isset($status.activated)}
    {g->text text="Successfully activated theme %s" arg1=$status.activated}
  {/if}
  {if isset($status.deactivated)}
    {g->text text="Successfully deactivated theme %s" arg1=$status.deactivated}
  {/if}
  {if isset($status.installed)}
    {g->text text="Successfully installed theme %s" arg1=$status.installed}
  {/if}
  {if isset($status.uninstalled)}
    {g->text text="Successfully uninstalled theme %s" arg1=$status.uninstalled}
  {/if}
  {if isset($status.upgraded)}
    {g->text text="Successfully upgraded theme %s" arg1=$status.upgraded}
  {/if}
  {if isset($status.savedTheme)}
    {g->text text="Successfully saved theme settings"}
  {/if}
  {if isset($status.savedDefaults)}
    {g->text text="Successfully saved default album settings"}
  {/if}
  {if isset($status.restoredTheme)}
    {g->text text="Restored theme settings"}
  {/if}
</h2></div>
{/if}

<div class="gbTabBar">
  {if ($AdminThemes.mode == 'config')}
    <span class="giSelected o"><span>
	{g->text text="All Themes"}
    </span></span>
  {else}
    <span class="o"><span>
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminThemes"
		       arg3="mode=config"}">{g->text text="All Themes"}</a>
    </span></span>
  {/if}

  {if ($AdminThemes.mode == 'defaults')}
    <span class="giSelected o"><span>
      {g->text text="Defaults"}
    </span></span>
  {else}
    <span class="o"><span>
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminThemes"
		       arg3="mode=defaults"}">{g->text text="Defaults"}</a>
    </span></span>
  {/if}

  {foreach from=$AdminThemes.themes key=themeId item=theme}
  {if $theme.active}
    {if ($AdminThemes.mode == 'editTheme') && ($AdminThemes.themeId == $themeId)}
      <span class="giSelected o"><span>
	{g->text text=$theme.name l10Domain=$theme.l10Domain}
      </span></span>
    {else}
      <span class="o"><span>
	<a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminThemes"
			 arg3="mode=editTheme" arg4="themeId=$themeId"}">{g->text text=$theme.name l10Domain=$theme.l10Domain}</a>
      </span></span>
    {/if}
  {/if}
  {/foreach}
</div>

{if ($AdminThemes.mode == 'config')}
<div class="gbBlock">
  <table class="gbDataTable"><tr>
    <th> &nbsp; </th>
    <th> {g->text text="Theme Name"} </th>
    <th> {g->text text="Version"} </th>
    <th> {g->text text="Installed"} </th>
    <th> {g->text text="Description"} </th>
    <th> {g->text text="Actions"} </th>
  </tr>

  {foreach from=$AdminThemes.themes key=themeId item=theme}
    <tr class="{cycle values="gbEven,gbOdd"}">
      <td>
	{if $theme.state == 'install'}
	<img src="{g->url href="modules/core/data/module-install.gif"}" width="13" height="13"
	 alt="{g->text text="Status: Not Installed"}" />
	{/if}
	{if $theme.state == 'active'}
	<img src="{g->url href="modules/core/data/module-active.gif"}" width="13" height="13"
	 alt="{g->text text="Status: Active"}" />
	{/if}
	{if $theme.state == 'inactive'}
	<img src="{g->url href="modules/core/data/module-inactive.gif"}" width="13" height="13"
	 alt="{g->text text="Status: Inactive"}" />
	{/if}
	{if $theme.state == 'upgrade'}
	<img src="{g->url href="modules/core/data/module-upgrade.gif"}" width="13" height="13"
	 alt="{g->text text="Status: Upgrade Required (Inactive)"}" />
	{/if}
	{if $theme.state == 'incompatible'}
	<img src="{g->url href="modules/core/data/module-incompatible.gif"}" width="13" height="13"
	 alt="{g->text text="Status: Incompatible Theme (Inactive)"}" />
	{/if}
      </td>

      <td{if ($themeId == $AdminThemes.defaultThemeId)} style="font-weight: bold"{/if}>
	{g->text text=$theme.name l10Domain=$theme.l10Domain}
      </td>

      <td align="center"{if
       ($themeId == $AdminThemes.defaultThemeId)} style="font-weight: bold"{/if}>
	{$theme.version}
      </td>

      <td align="center"{if
       ($themeId == $AdminThemes.defaultThemeId)} style="font-weight: bold"{/if}>
	{$theme.installedVersion}
      </td>

      <td{if ($themeId == $AdminThemes.defaultThemeId)} style="font-weight: bold"{/if}>
	{g->text text=$theme.description l10Domain=$theme.l10Domain}
	{if $theme.state == 'incompatible'}
	  <br/>
	  <span class="giError">
	    {g->text text="Incompatible theme!"}
	    {if $theme.api.required.core != $theme.api.provided.core}
	      <br/>
	      {g->text text="Core API Required: %s (available: %s)"
		       arg1=$theme.api.required.core arg2=$theme.api.provided.core}
	    {/if}
	    {if $theme.api.required.theme != $theme.api.provided.theme}
	      <br/>
	      {g->text text="Theme API Required: %s (available: %s)"
		       arg1=$theme.api.required.theme arg2=$theme.api.provided.theme}
	    {/if}
	  </span>
	{/if}
      </td>

      <td{if ($themeId == $AdminThemes.defaultThemeId)} style="font-weight: bold"{/if}>
	{if ($themeId == $AdminThemes.defaultThemeId)}
	  {g->text text="(default)"}
	{/if}
	{if (!empty($theme.action))}
	  {foreach name=actions from=$theme.action item=action}{strip}
	    {if !$smarty.foreach.actions.first}
	      <br/>
	    {/if}
	    <a href="{g->url params=$action.params}">
	      {$action.text}
	    </a>
	  {/strip}{/foreach}
	{else}
	  &nbsp;
	{/if}
      </td>
    </tr>
  {/foreach}
  </table>
</div>
{/if}

{if ($AdminThemes.mode == 'defaults')}
<div class="gbBlock">
  <h3> {g->text text="Defaults"} </h3>

  <p class="giDescription">
    {g->text text="These are default display settings for albums in your gallery.  They can be overridden in each album."}
  </p>

  <table class="gbDataTable"><tr>
    <td>
      {g->text text="Default sort order"}
    </td><td>
      <select name="{g->formVar var="form[default][orderBy]"}" onchange="pickOrder()">
	{html_options options=$AdminThemes.orderByList selected=$form.default.orderBy}
      </select>
      <select name="{g->formVar var="form[default][orderDirection]"}">
	{html_options options=$AdminThemes.orderDirectionList
		      selected=$form.default.orderDirection}
      </select>
      {g->text text="with"}
      <select name="{g->formVar var="form[default][presort]"}">
	{html_options options=$AdminThemes.presortList selected=$form.default.presort}
      </select>
      <script type="text/javascript">
	// <![CDATA[
	function pickOrder() {ldelim}
	  var list = '{g->formVar var="form[default][orderBy]"}';
	  var frm = document.getElementById('siteAdminForm');
	  var index = frm.elements[list].selectedIndex;
	  list = '{g->formVar var="form[default][orderDirection]"}';
	  frm.elements[list].disabled = (index == 0) ?1:0;
	  list = '{g->formVar var="form[default][presort]"}';
	  frm.elements[list].disabled = (index == 0) ?1:0;
	{rdelim}
	pickOrder();
	// ]]>
      </script>
    </td>
  </tr>
  <tr>
    <td>
      {g->text text="Default theme"}
    </td><td>
      <select name="{g->formVar var="form[default][theme]"}">
	{html_options options=$AdminThemes.themeList selected=$form.default.theme}
      </select>
     </td>
  </tr>
  <tr>
    <td>
      {g->text text="New albums"}
    </td><td>
      <select name="{g->formVar var="form[default][newAlbumsUseDefaults]"}">
	{html_options options=$AdminThemes.newAlbumsUseDefaultsList
		      selected=$form.default.newAlbumsUseDefaults}
      </select>
    </td>
  </tr></table>
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden" name="{g->formVar var="mode"}" value="defaults"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][saveDefaults]"}" value="{g->text text="Save"}"/>
</div>
{/if}

{if ($AdminThemes.mode == "editTheme")}
<div class="gbBlock">
  <h3>
    {g->text text="%s Theme Settings" arg1=$AdminThemes.themes[$AdminThemes.themeId].name}
  </h3>

  <p class="giDescription">
    {g->text text="These are the global settings for the theme.  They can be overridden at the album level."}
  </p>

  {if isset($AdminThemes.customTemplate)}
    {include file="gallery:`$AdminThemes.customTemplate`"
	     l10Domain=$AdminThemes.themes[$AdminThemes.themeId].l10Domain}
  {/if}

  {if !empty($AdminThemes.settings)}
    <table class="gbDataTable">
      {foreach from=$AdminThemes.settings item=setting}
	<tr class="{cycle values="gbEven,gbOdd"}">
	  <td>
	    {$setting.name}
	  </td>
	  <td>
	    {if ($setting.type == 'text-field')}
	      <input type="text" size="{$setting.typeParams.size|default:6}"
	       name="{g->formVar var="form[key][`$setting.key`]"}"
	       value="{$form.key[$setting.key]}"/>
	    {elseif ($setting.type == 'textarea')}
	      <textarea style="width:{$setting.typeParams.width|default:'400px'};height:{$setting.typeParams.height|default:'75px'};"
	       name="{g->formVar var="form[key][`$setting.key`]"}">{$form.key[$setting.key]}</textarea>
	    {elseif ($setting.type == 'single-select')}
	      <select name="{g->formVar var="form[key][`$setting.key`]"}">
		{html_options options=$setting.choices selected=$form.key[$setting.key]}
	      </select>
	    {elseif ($setting.type == 'checkbox')}
	      <input type="checkbox"{if !empty($setting.value)} checked="checked"{/if}
	       name="{g->formVar var="form[key][`$setting.key`]"}" />
	    {elseif ($setting.type == 'block-list')}
	      <table>
		<tr>
		  <td style="text-align: right;">
		    {g->text text="Available"}
		  </td>
		  <td>
		    <select id="blocksAvailableList_{$setting.key}"
			    onchange="bsw_selectToUse('{$setting.key}');">
		      <option value="">{g->text text="Choose a block"}</option>
		    </select>
		  </td>
		  <td class="bsw_BlockCommands">
		    <span id="bsw_AddButton_{$setting.key}"
			  onclick="bsw_addBlock('{$setting.key}');" class="bsw_ButtonDisabled">
		      {g->text text="Add"}
		    </span>
		  </td>
		</tr>

		<tr>
		  <td style="text-align: right; vertical-align: top;">
		    {g->text text="Selected"}
		  </td>
		  <td id="bsw_UsedBlockList_{$setting.key}">
		    <select id="blocksUsedList_{$setting.key}" size="10"
			    onchange="bsw_selectToChange('{$setting.key}');">
		      <option value=""></option> {* Dummy option so xhtml validates *}
		    </select>
		  </td>
		  <td class="bsw_BlockCommands">
		    <span style="display: block"
			  id="bsw_RemoveButton_{$setting.key}"
			  onclick="bsw_removeBlock('{$setting.key}');"
			  class="bsw_ButtonDisabled">
		      {g->text text="Remove"}
		    </span>

		    <span style="display: block"
			  id="bsw_MoveUpButton_{$setting.key}"
			  onclick="bsw_moveUp('{$setting.key}');"
			  class="bsw_ButtonDisabled">
		      {g->text text="Move Up"}
		    </span>

		    <span style="display: block"
			  id="bsw_MoveDownButton_{$setting.key}"
			  onclick="bsw_moveDown('{$setting.key}');"
			  class="bsw_ButtonDisabled">
		      {g->text text="Move Down"}
		    </span>
		  </td>
		</tr>
		<tr>
		  <td id="bsw_BlockOptions_{$setting.key}" colspan="3">
		  </td>
		</tr>
	      </table>
	      <input type="hidden"
		     id="albumBlockValue_{$setting.key}" size="60"
		     name="{g->formVar var="form[key][`$setting.key`]"}"
		     value="{$form.key[$setting.key]}"/>

	      <script type="text/javascript">
		// <![CDATA[
		var block;
		var tmp;
		{foreach from=$AdminThemes.availableBlocks key=moduleId item=blocks}
		  {foreach from=$blocks key=blockName item=block}
		    block = bsw_addAvailableBlock("{$setting.key}", "{$moduleId}.{$blockName}",
			    "{g->text text=$block.description l10Domain="modules_$moduleId"}");
		    {if !empty($block.vars)}
		      {foreach from=$block.vars key=varKey item=varInfo}
			tmp = new Array();
			{if ($varInfo.type == 'choice')}
			  {foreach from=$varInfo.choices key=choiceKey item=choiceValue}
			    tmp["{$choiceKey}"] = "{g->text text=$choiceValue
							    l10Domain="modules_$moduleId"}";
			  {/foreach}
			{/if}
			block.addVariable("{$varKey}", "{$varInfo.default}",
			  "{g->text text=$varInfo.description l10Domain="modules_$moduleId"}",
			  "{$varInfo.type}", tmp);
	                {if !empty($varInfo.overrides)}
	                {foreach from=$varInfo.overrides item=override}
	                block.addVariableOverride("{$varKey}", "{$override}");
                        {/foreach}
	                {/if}
		      {/foreach}
		    {/if}
		  {/foreach}
		{/foreach}
		{* Now initialize the form with the album block values *}
		bsw_initAdminForm("{$setting.key}", "{g->text text="Parameter"}",
						    "{g->text text="Value"}");
		// ]]>
	      </script>
	    {/if}
	  </td>
	</tr>

	{if isset($form.error.key[$setting.key].invalid)}
	<tr>
	  <td colspan="2" class="giError">
	    {$form.errorMessage[$setting.key]}
	  </td>
	</tr>
	{/if}
      {/foreach}
    </table>
  {elseif !isset($AdminThemes.customTemplate)}
    {g->text text="There are no settings for this theme"}
  {/if}
</div>

{if isset($AdminThemes.customTemplate) || !empty($AdminThemes.settings)}
<div class="gbBlock gcBackground1">
  <input type="hidden" name="{g->formVar var="themeId"}" value="{$AdminThemes.themeId}"/>
  <input type="hidden" name="{g->formVar var="mode"}" value="editTheme"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][saveTheme]"}" value="{g->text text="Save"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][undoTheme]"}" value="{g->text text="Reset"}"/>
</div>
{/if}
{/if}
