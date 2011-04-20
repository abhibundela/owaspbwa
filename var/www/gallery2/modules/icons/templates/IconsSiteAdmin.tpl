{*
 * $Revision: 1.1 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Icon Settings"} </h2>
</div>

{if isset($status.saved)}
<div class="gbBlock"><h2 class="giSuccess">
  {g->text text="Settings saved successfully"}
</h2></div>
{/if}

<div class="gbBlock">
{if empty($IconsSiteAdmin.iconpacks)}
  <p class="giDescription">
    {g->text text="No icon packs are available."}
  </p>
{else}
  <p class="giDescription">
    {g->text text="Select an icon pack to use for this Gallery."}
  </p><p>
    <select name="{g->formVar var="form[iconpack]"}">
      {html_options options=$IconsSiteAdmin.iconpacks selected=$form.iconpack}
    </select>
  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][save]"}" value="{g->text text="Save"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][reset]"}" value="{g->text text="Reset"}"/>
{/if}
</div>
