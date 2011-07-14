{*
 * $Revision: 1.5 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="MIME Maintenance"} </h2>
</div>

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
  {if isset($status.saved)}
    {g->text text="MIME entry saved successfully"}
  {/if}
  {if isset($status.deleted)}
    {g->text text="MIME entry deleted"}
  {/if}
</h2></div>
{/if}

<div class="gbBlock">
  <table class="gbDataTable" width="100%"><tr>
    <th> {g->text text="MIME Types"} </th>
    <th> {g->text text="Extensions"} </th>
    <th> {g->text text="Viewable"} </th>
    <th> {g->text text="Actions"} </th>
  </tr>
  {foreach from=$MimeAdmin key=mime item=type}
  <tr class="{cycle values="gbEven,gbOdd"}">
    <td>
      {$mime}
    </td><td>
      {$type.ext}
    </td><td align="center">
      {if $type.viewable == 1}
	<img src="{g->url href="modules/mime/data/mime_viewable.gif"}"
	 width="13" height="13" alt="{g->text text="Viewable"}" title="{g->text text="Viewable"}"/>
      {else}
	&nbsp;
      {/if}
    </td><td align="center">
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=mime.MimeEdit"
       arg3="mimeType=`$mime`"}">
	<img src="{g->url href="modules/mime/data/b_edit.png"}"
	 width="16" height="16" alt="{g->text text="edit"}" title="{g->text text="edit"}"/>
      </a>
      &nbsp;
      <input type="image" src="{g->url href="modules/mime/data/b_drop.png"}"
       name="{g->formVar var="form[action][delete]"}" value="{$mime}"
       alt="{g->text text="delete"}" title="{g->text text="delete"}"/>
    </td>
  </tr>
  {/foreach}
  </table>
</div>

<div class="gbBlock gcBackground1">
  <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=mime.MimeEdit"}">
    {g->text text="Add new MIME type"}
  </a>
</div>
