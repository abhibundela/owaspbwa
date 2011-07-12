{*
 * $Revision: 1.8 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{* Load up the WZ_DragDrop library *}
<script type="text/javascript" src="{g->url href="lib/wz_dragdrop/wz_dragdrop.js"}"></script>

<script type="text/javascript">
  // <![CDATA[
  function moveToOriginalLocation() {ldelim}
    var orig = dd.elements.background, floater = dd.elements.floater, scale = 1.0;
    if ({$watermark.width} > (0.9 * orig.w)) {ldelim}
      scale = (0.9 * orig.w) / {$watermark.width};
    {rdelim}
    if ({$watermark.height} > (0.9 * orig.h)) {ldelim}
      scale = Math.min(scale, (0.9 * orig.h) / {$watermark.height});
    {rdelim}
    floater.resizeTo({$watermark.width} * scale,
		     {$watermark.height} * scale);
    floater.moveTo(orig.x + Math.round({$form.xPercentage|default:0} / 100 * (orig.w - floater.w)),
		   orig.y + Math.round({$form.yPercentage|default:0} / 100 * (orig.h - floater.h)));
  {rdelim}

  {literal}
  function calculatePercentages() {
    var orig = dd.elements.background, floater = dd.elements.floater;
    document.getElementById("xPercentage").value =
      100.0 * (floater.x - orig.x) / (orig.w - floater.w);
    document.getElementById("yPercentage").value =
      100.0 * (floater.y - orig.y) / (orig.h - floater.h);
  }

  function my_DragFunc() {
    verifyBounds();
  }

  // Keep from dragging the watermark off the image
  function verifyBounds() {
    var orig = dd.elements.background, floater = dd.elements.floater,
	newX = floater.x, newY = floater.y;
    if (floater.x < orig.x) {
      newX = orig.x;
    } else if (floater.x + floater.w > orig.x + orig.w) {
      newX = orig.x + (orig.w - floater.w);
    }

    if (floater.y < orig.y) {
      newY = orig.y;
    } else if (floater.y + floater.h > orig.y + orig.h) {
      newY = orig.y + (orig.h - floater.h);
    }

    if (newX != floater.x || newY != floater.y) {
      floater.moveTo(newX, newY);
    }
  }
  {/literal}
  // ]]>
</script>

<input type="hidden" name="{g->formVar var="watermarkId"}" value="{$watermark.id}"/>
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Edit A Watermark"} </h2>
</div>

{if !empty($form.error)}
<div class="gbBlock"><h2 class="giError">
  {g->text text="There was a problem processing your request."}
</h2></div>
{/if}

{if isset($status.add)}
<div class="gbBlock"><h2 class="giSuccess">
  {g->text text="New image added successfully"}
</h2></div>
{/if}

<div class="gbBlock">
  <h3> {g->text text="Watermark Name"} </h3>

  <p class="giDescription">
    {g->text text="Give this watermark a name so that you can identify it in a list."}
  </p>

  <input type="text" size="40" name="{g->formVar var="form[name]"}" value="{$form.name}"/>

 {if isset($form.error.name.missing)}
 <div class="giError">
   {g->text text="You must provide a name"}
 </div>
 {/if}
 {if isset($form.error.name.duplicate)}
 <div class="giError">
   {g->text text="Name already used by another watermark"}
 </div>
 {/if}
</div>

<div class="gbBlock">
  <h3> {g->text text="Placement"} </h3>

  <p class="giDescription">
    {g->text text="Place your watermark on the canvas below in the location where you'd like it to appear when you watermark newly uploaded photos.  You'll be able to edit individual photos to move the watermark later on, if you choose."}
  </p>

  <div id="background" class="gcBackground1"
   style="width: 400px; height: 300px; border-width: 1px; margin: 5px 0 10px 5px">
    <img name="floater"
     src="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$watermark.id`"}"
     width="{$watermark.width}" height="{$watermark.height}" alt="" style="position: absolute"/>
  </div>

  <input type="hidden" id="xPercentage"
   name="{g->formVar var="form[xPercentage]"}" value="{$form.xPercentage}"/>
  <input type="hidden" id="yPercentage"
   name="{g->formVar var="form[yPercentage]"}" value="{$form.yPercentage}"/>
</div>

<div class="gbBlock">
  <h3> {g->text text="Choose which versions of the image you'd like to watermark"} </h3>

  {if isset($form.error.whichDerivative.missing)}
  <p class="giError">
    {g->text text="You must choose something to watermark!"}
  </p>
  {/if}

  <input type="checkbox"{if isset($form.whichDerivative.preferred)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][preferred]"}"/>
  {g->text text="Full size (won't damage the original file)"}
  <br/>

  <input type="checkbox"{if isset($form.whichDerivative.resizes)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][resizes]"}"/>
  {g->text text="Resizes"}
  <br/>

  <input type="checkbox"{if isset($form.whichDerivative.thumbnail)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][thumbnail]"}"/>
  {g->text text="Thumbnail"}
  <br/>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit" onclick="calculatePercentages(); return true"
   name="{g->formVar var="form[action][save]"}" value="{g->text text="Save"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][cancel]"}" value="{g->text text="Cancel"}"/>
</div>

{g->addToTrailer}
<script type="text/javascript">{literal}
// <![CDATA[
SET_DHTML("background"+NO_DRAG, "floater"+CURSOR_MOVE);
moveToOriginalLocation();
verifyBounds();
// ]]>
{/literal}</script>
{/g->addToTrailer}
