{*
 * $Revision: 1.36 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {$actionText} {$pluginName} </h2>
</div>

{if isset($form.packages.empty)}
<div class="giError">
  {g->text text="No packages have been selected."}
</div>
{/if}

{if isset($upgradeData.isBaseDownloadable)}
<div class="gbBlock">
  <h3>{g->text text="Base Files"}</h3>
  <p class="giDescription">
    {g->text text="The base files required for the theme to work and the English (US) translation will be downloaded."}
    <input type="hidden" name="{g->formVar var="form[downloadBaseFiles]"}" value="true" />
  </p>
</div>
{/if}

{if isset($upgradeData.isBaseUpgradeable)}
<div class="gbBlock">
  <h3>{g->text text="Upgrade Base Files"}</h3>
  <p class="giDescription">
    {g->text text="You can upgrade the base module files."}
  </p>
  <p>
    <input type="checkbox" name="{g->formVar var="form[upgradeBaseFiles]"}" />{g->text text="Upgrade base files"}
    {if isset($upgradeData.upgradeablePackages.test)}
    <input type="hidden" name="{g->formVar var="form[downloadTest]"}" value="true" />
    {/if}
  </p>
</div>
{/if}

{if !$upgradeData.isBaseDownloadable && !$upgradeData.isBaseUpgradeable}
<div class="gbBlock">
  <h3>{g->text text="Base Files Up-To-Date"}</h3>
  <p class="giDescription">
    {g->text text="This plugin's base files are up-to-date."}
  </p>
</div>
{/if}

{if isset($upgradeData.upgradeableLanguages)}
<div class="gbBlock">
  <h3>{g->text text="Updated Translations"}</h3>
  <p class="giDescription">
    {g->text text="Below is a list of translations that have been updated since they were last downloaded. Select the ones you want to download."}
  </p>
  <p>
    {capture name="formVariableName"}{g->formVar var="form[upgradeLanguages]"}{/capture} 
    {html_checkboxes name="`$smarty.capture.formVariableName`" separator="<br />"
      values=$upgradeData.upgradeableLanguages.codes
      output=$upgradeData.upgradeableLanguages.names}
  </p>
</div>
{/if}

{if isset($upgradeData.downloadableLanguages)}
<div class="gbBlock">
  <h3>{g->text text="Additional Languages"}</h3>
  <p class="giDescription">
    {g->text text="Below is a list of languages this plugin has been translated to. Select the ones you want included in addition to the ones already downloaded."}
  </p>
  <p>
    {capture name="formVariableName"}{g->formVar var="form[downloadLanguages]"}{/capture} 
    {html_checkboxes name="`$smarty.capture.formVariableName`" separator="<br />"
      values=$upgradeData.downloadableLanguages.codes
      output=$upgradeData.downloadableLanguages.names}
  </p>
</div>
{/if}

{if isset($upgradeData.downloadablePackages.test)}
<div class="gbBlock">
  <h3>{g->text text="Unit Tests"}</h3>
  <p class="giDescription">
    {g->text text="This plugin has unit tests available for download, which are useful for Gallery developers. All of the module's features are available without the tests."}
  </p>
  <p>
    <input type="checkbox" name="{g->formVar var="form[downloadTest]"}" value="true" />{g->text text="Download unit tests"}
  </p>
</div>
{/if}

<div class="gbBlock gcBackground1">
  <input type="submit" name="{g->formVar var="form[action][download]"}" value="{$actionText}"/>
  <input type="submit" name="{g->formVar var="form[action][cancel]"}" value="{g->text text="Cancel"}"/>
  <input type="hidden" name="{g->formVar var="form[pluginType]"}" value="{$pluginType}" />
  <input type="hidden" name="{g->formVar var="form[pluginId]"}" value="{$pluginId}" />
  <input type="hidden" name="{g->formVar var="mode"}" value="download" />
</div>