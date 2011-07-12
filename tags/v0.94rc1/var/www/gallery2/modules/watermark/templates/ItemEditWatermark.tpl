{*
 * $Revision: 1.20 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{* Load up the WZ_DragDrop library *}
<script type="text/javascript" src="{g->url href="lib/wz_dragdrop/wz_dragdrop.js"}"></script>

<script type="text/javascript">
  // <![CDATA[
  var watermarkUrlMap = new Array;
  {foreach from=$ItemEditWatermark.watermarks item=watermark}
  watermarkUrlMap[{$watermark.id}] = new Array;
  watermarkUrlMap[{$watermark.id}]['url'] = '{g->url htmlEntities=false
    arg1="view=core.DownloadItem" arg2="itemId=`$watermark.id`"}';
  watermarkUrlMap[{$watermark.id}]['width'] = {$watermark.width};
  watermarkUrlMap[{$watermark.id}]['height'] = {$watermark.height};
  watermarkUrlMap[{$watermark.id}]['xPercent'] = {if
    $watermark.id==$form.watermarkId}{$form.xPercent}{else}{$watermark.xPercentage}{/if};
  watermarkUrlMap[{$watermark.id}]['yPercent'] = {if
    $watermark.id==$form.watermarkId}{$form.yPercent}{else}{$watermark.yPercentage}{/if};
  {/foreach}

  {literal}
  function calculatePercentages() {
    var orig = dd.elements.watermark_original, floater = dd.elements.watermark_floater;
    document.getElementById("xPercent").value = 100.0 * (floater.x - orig.x) / (orig.w - floater.w);
    document.getElementById("yPercent").value = 100.0 * (floater.y - orig.y) / (orig.h - floater.h);
  }

  function chooseWatermark(id) {
    var orig = dd.elements.watermark_original, floater = dd.elements.watermark_floater,
	newImage = watermarkUrlMap[id], scale = 1.0;
    floater.swapImage(newImage['url']);
    if (newImage['width'] > (0.9 * orig.w)) {
      scale = (0.9 * orig.w) / newImage['width'];
    }
    if (newImage['height'] > (0.9 * orig.h)) {
      scale = Math.min(scale, (0.9 * orig.h) / newImage['height']);
    }
    floater.resizeTo(newImage['width'] * scale, newImage['height'] * scale);
    moveToLocation(newImage['xPercent'], newImage['yPercent']);
    verifyBounds();
  }

  function moveToLocation(xPct, yPct) {
    var orig = dd.elements.watermark_original, floater = dd.elements.watermark_floater;
    floater.moveTo(orig.x + Math.round(xPct / 100 * (orig.w - floater.w)),
		   orig.y + Math.round(yPct / 100 * (orig.h - floater.h)));
  }

  function my_DragFunc() {
    verifyBounds();
  }

  // Keep from dragging the watermark off the image
  function verifyBounds() {
    var orig = dd.elements.watermark_original, floater = dd.elements.watermark_floater,
	newX = floater.x, newY = floater.y;
    if (floater.x < orig.x) {
      newX = orig.x;
    } else if (floater.x + floater.w > orig.x + orig.w) {
      newX = orig.x + orig.w - floater.w;
    }

    if (floater.y < orig.y) {
      newY = orig.y;
    } else if (floater.y + floater.h > orig.y + orig.h) {
      newY = orig.y + orig.h - floater.h;
    }

    if (newX != floater.x || newY != floater.y) {
      floater.moveTo(newX, newY);
    }
  }
  {/literal}
  // ]]>
</script>

<div class="gbBlock">
  <h3> {g->text text="Watermark"} </h3>

  <p class="giDescription">
    {g->text text="You can choose a watermark to apply to this image.  Watermarks do not affect the original image, so they they can be applied to resizes and thumbnails without damaging the original."}
  </p>
</div>

{if empty($ItemEditWatermark.watermarks)}
<div class="gbBlock">
  <h3> {g->text text="You have no watermarks"} </h3>

  <p class="giDescription">
    {g->text text="You must first upload some watermark images so that you can apply them to your image."}
    <a href="{g->url arg1="view=core.UserAdmin" arg2="subView=watermark.UserWatermarks"}">
      {g->text text="Upload some watermarks now."}
    </a>
  </p>
</div>
{else}
<div class="gbBlock">
  <h3> {g->text text="Step 1.  Choose which watermark you want to use"} </h3>

  <select name="{g->formVar var="form[watermarkId]"}" onchange="chooseWatermark(this.value)"
   id="watermarkList">
    {foreach from=$ItemEditWatermark.watermarks item=watermark}
    <option value="{$watermark.id}"{if
     ($form.watermarkId == $watermark.id)} selected="selected"{/if}>{$watermark.name}</option>
    {/foreach}
  </select>
</div>

<div class="gbBlock">
  <h3> {g->text text="Step 2.  Place the watermark on your image."} </h3>

  {g->image name="watermark_original" maxSize=400 style="display: block"
	    item=$ItemEditWatermark.item forceRawImage=true
	    image=$ItemEditWatermark.derivative|default:$ItemEditWatermark.item}
  <img name="watermark_floater"
   src="{g->url arg1="view=core.DownloadItem" arg2="itemId=`$form.watermarkId`"}"
   width="{$ItemEditWatermark.watermarks[$form.watermarkId].width}"
   height="{$ItemEditWatermark.watermarks[$form.watermarkId].height}"
   alt="{g->text text="watermark"}" style="position: absolute"/>
</div>

<div class="gbBlock">
  <h3> {g->text text="Step 3.  Choose which versions of the image you'd like to watermark"} </h3>

  {if isset($form.error.versions.missing)}
  <div class="giError">
    {g->text text="You must choose something to watermark!"}
  </div>
  {/if}

  <input type="checkbox"{if isset($form.whichDerivative.preferred)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][preferred]"}"/>
  {g->text text="Full size (won't damage the original file)"}
  <br/>

  <input type="checkbox"{if isset($form.whichDerivative.resize)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][resize]"}"/>
  {g->text text="Resizes"}
  <br/>

  <input type="checkbox"{if isset($form.whichDerivative.thumbnail)} checked="checked"{/if}
   name="{g->formVar var="form[whichDerivative][thumbnail]"}"/>
  {g->text text="Thumbnail"}
  <br/>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit" onclick="calculatePercentages(); return true"
   name="{g->formVar var="form[action][save]"}" value="{g->text text="Apply Watermark"}"/>
  <input type="submit" class="inputTypeSubmit"
   name="{g->formVar var="form[action][remove]"}" value="{g->text text="Remove Watermark"}"/>
</div>

<input type="hidden" id="xPercent"
 name="{g->formVar var="form[xPercent]"}" value="{$form.xPercent}"/>
<input type="hidden" id="yPercent"
 name="{g->formVar var="form[yPercent]"}" value="{$form.yPercent}"/>

{g->addToTrailer}
<script type="text/javascript">{literal}
// <![CDATA[
SET_DHTML("watermark_original"+NO_DRAG, "watermark_floater"+CURSOR_MOVE);
dd.elements.watermark_floater.moveTo(dd.elements.watermark_original.x,
				     dd.elements.watermark_original.y);
dd.elements.watermark_floater.setZ(dd.elements.watermark_original.z+1);
chooseWatermark(document.getElementById("watermarkList").value);
// ]]>
{/literal}</script>
{/g->addToTrailer}
{/if}
