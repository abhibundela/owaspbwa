{*
 * $Revision: 1.5 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Confirm module uninstall"} </h2>
</div>

<div class="gbBlock">
  <h3>
    {capture name=moduleName}<b>{$AdminModulesVerifyUninstall.module.name}</b>{/capture}
    {g->text text="Do you really want to uninstall the %s module?" arg1=$smarty.capture.moduleName}
  </h3>
  <p class="giDescription">
    {g->text text="This will also remove any permissions and clean up any data created by this module."}
  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden"
   name="{g->formVar var="moduleId"}" value="{$AdminModulesVerifyUninstall.module.id}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][uninstall]"}" value="{g->text text="Uninstall"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][cancel]"}" value="{g->text text="Cancel"}"/>
</div>
