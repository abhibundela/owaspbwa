{*
 * $Revision: 1.1 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Fotokasten Settings"} </h2>
</div>

{if isset($status.saved)}
<div class="gbBlock"><h2 class="giSuccess">
  {g->text text="Settings saved successfully"}
</h2></div>
{/if}

<div class="gbBlock">
  <p class="giDescription">
    {g->text text="Enter affiliate settings."}
  </p>
  <table class="gbDataTable">
    <tr><td>
      <label for="affiliateId">
	{g->text text="Affiliate Id:"}
      </label>
    </td><td>
      <input type="text" id="affiliateId" size="6"
       name="{g->formVar var="form[affiliateId]"}" value="{$form.affiliateId}"/>

      {if isset($form.error.affiliateId)}
	<span class="giError"> {g->text text="Enter a numeric id"} </span>
      {/if}
    </td></tr><tr><td>
      <label for="affiliateIdPass">
	{g->text text="Affiliate Password:"}
      </label>
    </td><td>
      <input type="text" id="affiliateIdPass" size="34"
       name="{g->formVar var="form[affiliateIdPass]"}" value="{$form.affiliateIdPass}"/>

      {if isset($form.error.affiliateIdPass)}
	<span class="giError"> {g->text text="Missing value"} </span>
      {/if}
    </td></tr>
  </table>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][save]"}" value="{g->text text="Save"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][reset]"}" value="{g->text text="Reset"}"/>
  <input type="button" class="inputTypeButton" value="{g->text text="Use Defaults"}"
   onclick="document.getElementById('affiliateId').value='1927';document.getElementById('affiliateIdPass').value='f12a65d90445f95b90e5fd30c75ee74e'"/>
</div>
