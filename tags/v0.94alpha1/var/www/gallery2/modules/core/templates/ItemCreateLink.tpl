{*
 * $Revision: 1.25 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Link an Item"} </h2>
</div>

{if isset($status.linked)}
<div class="gbBlock"><h2 class="giSuccess">
  {g->text one="Successfully linked %d item" many="Successfully linked %d items"
	   count=$status.linked.count arg1=$status.linked.count}
</h2></div>
{/if}

<div class="gbBlock">
{if empty($ItemCreateLink.peers)}
  <p class="giDescription">
    {g->text text="This album contains no items to link."}
  </p>
{else}
  <h3> {g->text text="Source"} </h3>

  <p class="giDescription">
    {g->text text="Choose the items you want to link"}
    {if ($ItemCreateLink.numPages > 1) }
      {g->text text="(page %d of %d)"
	       arg1=$ItemCreateLink.page
	       arg2=$ItemCreateLink.numPages}
    {/if}
  </p>
  
  {if !empty($form.error.sources.empty)}
  <div class="giError">
    <h2>{g->text text="No sources chosen"}</h2>
  </div>
  {/if}

  <script type="text/javascript">
    // <![CDATA[
    function setCheck(val) {ldelim}
      var frm = document.getElementById('itemAdminForm');
      {foreach from=$ItemCreateLink.peers item=peer}
	frm.elements['g2_form[selectedIds][{$peer.id}]'].checked = val;
      {/foreach}
    {rdelim}
    function invertCheck(val) {ldelim}
      var frm = document.getElementById('itemAdminForm');
      {foreach from=$ItemCreateLink.peers item=peer}
	frm.elements['g2_form[selectedIds][{$peer.id}]'].checked =
	    !frm.elements['g2_form[selectedIds][{$peer.id}]'].checked;
      {/foreach}
    {rdelim}
    // ]]>
  </script>
  
  <table>
    <colgroup width="60"/>
    {foreach from=$ItemCreateLink.peers item=peer}
    <tr>
      <td align="center">
	{if isset($peer.thumbnail)}
	  <a href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$peer.id`"}">
	    {g->image item=$peer image=$peer.thumbnail maxSize=50 class="giThumbnail"}
	  </a>
	{else}
	  &nbsp;
	{/if}
      </td><td>
	<input type="checkbox" id="cb_{$peer.id}"{if $peer.selected} checked="checked"{/if}
	 name="{g->formVar var="form[selectedIds][`$peer.id`]"}"/>
      </td><td>
	<label for="cb_{$peer.id}">
	  {$peer.title|default:$peer.pathComponent}
	</label>
     </td>
    </tr>
    {/foreach}
  </table>

  <input type="button" class="inputTypeButton" onclick="setCheck(1)"
   name="{g->formVar var="form[action][checkall]"}" value="{g->text text="Check All"}"/>
  <input type="button" class="inputTypeButton" onclick="setCheck(0)"
   name="{g->formVar var="form[action][checknone]"}" value="{g->text text="Check None"}"/>
  <input type="button" class="inputTypeButton" onclick="invertCheck()"
   name="{g->formVar var="form[action][invert]"}" value="{g->text text="Invert"}"/>

  {if ($ItemCreateLink.page > 1)}
    <input type="submit" class="inputTypeSubmit"
     name="{g->formVar var="form[action][previous]"}" value="{g->text text="Previous Page"}"/>
  {/if}
  {if ($ItemCreateLink.page < $ItemCreateLink.numPages)}
    <input type="submit" class="inputTypeSubmit"
     name="{g->formVar var="form[action][next]"}" value="{g->text text="Next Page"}"/>
  {/if}
</div>

<div class="gbBlock">
  <h3> {g->text text="Destination"} </h3>

  <p class="giDescription">
    {g->text text="Choose a new album for the link"}
  </p>

  <select name="{g->formVar var="form[destination]"}">
    {foreach from=$ItemCreateLink.albumTree item=album}
      <option value="{$album.data.id}">
	{"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"|repeat:$album.depth}--
	{$album.data.title|default:$album.data.pathComponent}
      </option>
    {/foreach}
  </select>

  {if !empty($form.error.destination.empty)}
  <div class="giError">
    {g->text text="No destination chosen"}
  </div>
  {/if}  
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden" name="{g->formVar var="page"}" value="{$ItemCreateLink.page}"/>
  <input type="hidden"
   name="{g->formVar var="form[numPerPage]"}" value="{$ItemCreateLink.numPerPage}"/>
  {foreach from=$ItemCreateLink.selectedIds item=selectedId}
    <input type="hidden" name="{g->formVar var="form[selectedIds][$selectedId]"}" value="on"/>
  {/foreach}

  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][link]"}" value="{g->text text="Link"}"/>
  {if $ItemCreateLink.canCancel}
    <input type="submit" class="inputTypeSubmit"
     name="{g->formVar var="form[action][cancel]"}" value="{g->text text="Cancel"}"/>
  {/if}
{/if}
</div>
