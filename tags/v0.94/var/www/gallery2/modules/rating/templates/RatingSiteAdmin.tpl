{*
 * $Revision: 1.2 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
	<h2> {g->text text="Rating Settings"} </h2>
</div>

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
	{if isset($status.saved)}
		{g->text text="Settings saved successfully"}
	{/if}
</h2></div>
{/if}

<div class="gbBlock">
	<p style="line-height: 2.5em; margin-left: 1em">
	<input type="checkbox" id="allowAlbumRating"
		{if $form.allowAlbumRating} checked="checked"{/if}
		name="{g->formVar var="form[allowAlbumRating]"}"/>
	<label for="allowAlbumRating">
		{g->text text="Allow users to rate entire albums, in addition to individual items."}
	</label>
  	</p>
</div>

<div class="gbBlock gcBackground1">
	<input type="submit" class="inputTypeSubmit" name="{g->formVar var="form[action][save]"}"
		value="{g->text text="Save"}"/>
	<input type="submit" class="inputTypeSubmit" name="{g->formVar var="form[action][reset]"}"
		value="{g->text text="Reset"}"/>
</div>
