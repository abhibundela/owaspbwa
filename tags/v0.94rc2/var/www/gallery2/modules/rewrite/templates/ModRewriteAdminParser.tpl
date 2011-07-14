{*
 * $Revision: 1.2 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock">
  <h3> {g->text text="Embedded Setup"} </h3>

  <p class="giDescription">
    {g->text text="For URL Rewrite to work in an embedded environment you need to set up an extra htaccess file to hold the mod_rewrite rules."}
  </p>

  <table class="gbDataTable"><tr>
    <td>
      {g->text text="Htaccess path:"}
    </td><td>
      <input type="text" size="60" name="{g->formVar var="form[embeddedHtaccess]"}" value="{$form.embeddedHtaccess}" id="embeddedHtaccess"/><br/>
    </td>
  </tr><tr>
    <td>
      {g->text text="Public path:"}
    </td><td>
      {$AdminParser.host}<input type="text" size="40" name="{g->formVar var="form[embeddedLocation]"}" value="{$form.embeddedLocation}" id="embeddedLocation"/><br/>
    </td>
  </tr></table>
</div>
