{*
 * $Revision: 1.15 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock">
  <h3> {g->text text="Thumbnail"} </h3>

  <p class="giDescription">
    {g->text text="You can select which part of the photo will be used for the thumbnail.  This will have no effect on the resized or original versions of the image."}
  </p>

  {if $ItemEditPhotoThumbnail.editThumbnail.can.crop}
  <!--
  <object
    classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"
    codebase="http://java.sun.com/products/plugin/autodl/jinstall-1_4-windows-i586.cab#Version=1,4,0,0"
    width="630"
    height="480">
    <param name="code" value="{$ItemEditPhotoThumbnail.editThumbnail.appletCodeBase}"/>
    <param name="archive" value="{$ItemEditPhotoThumbnail.editThumbnail.appletJarFile}"/>
    <param name="type" value="application/x-java-applet;version=1.1.8"/>
    <param name="scriptable" value="true"/>

    <param name="image"              value="{$ItemEditPhotoThumbnail.editThumbnail.imageUrl}"/>
    <param name="image_width"        value="{$ItemEditPhotoThumbnail.editThumbnail.imageWidth}"/>
    <param name="image_height"       value="{$ItemEditPhotoThumbnail.editThumbnail.imageHeight}"/>
    <param name="crop_x"             value="{$ItemEditPhotoThumbnail.editThumbnail.cropLeft}"/>
    <param name="crop_y"             value="{$ItemEditPhotoThumbnail.editThumbnail.cropTop}"/>
    <param name="crop_width"         value="{$ItemEditPhotoThumbnail.editThumbnail.cropWidth}"/>
    <param name="crop_height"        value="{$ItemEditPhotoThumbnail.editThumbnail.cropHeight}"/>
    <param name="crop_ratio_width"   value="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioWidth}"/>
    <param name="crop_ratio_height"  value="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioHeight}"/>
    <param name="crop_orientation"   value="{$ItemEditPhotoThumbnail.editThumbnail.selectedOrientation}"/>
    <param name="crop_to_size"       value="{$ItemEditPhotoThumbnail.editThumbnail.targetThumbnailSize}"/>

    <comment>
      <embed
         type="application/x-java-applet;version=1.1.8"
         code="{$ItemEditPhotoThumbnail.editThumbnail.appletCodeBase}"
         archive="{$ItemEditPhotoThumbnail.editThumbnail.appletJarFile}"
         width="630"
         height="480"
         scriptable="true"
         image="{$ItemEditPhotoThumbnail.editThumbnail.imageUrl}"
         image_width="{$ItemEditPhotoThumbnail.editThumbnail.imageWidth}"
         image_height="{$ItemEditPhotoThumbnail.editThumbnail.imageHeight}"
         crop_x="{$ItemEditPhotoThumbnail.editThumbnail.cropLeft}"
         crop_y="{$ItemEditPhotoThumbnail.editThumbnail.cropTop}"
         crop_width="{$ItemEditPhotoThumbnail.editThumbnail.cropWidth}"
         crop_height="{$ItemEditPhotoThumbnail.editThumbnail.cropHeight}"
         crop_ratio_width="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioWidth}"
         crop_ratio_height="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioHeight}"
         crop_orientation="{$ItemEditPhotoThumbnail.editThumbnail.selectedOrientation}"
         crop_to_size="{$ItemEditPhotoThumbnail.editThumbnail.targetThumbnailSize}"/>
    </comment>
  </object>
   -->

  <applet id="ImageCrop"
          code="ImageCrop"
          width="630"
          height="480"
          codebase="{$ItemEditPhotoThumbnail.editThumbnail.appletCodeBase}"
          archive="{$ItemEditPhotoThumbnail.editThumbnail.appletJarFile}"
          scriptable="TRUE"
          mayscript="TRUE">
       <param name="type"               value="application/x-java-applet;version=1.1.2"/>
       <param name="image"              value="{$ItemEditPhotoThumbnail.editThumbnail.imageUrl}"/>
       <param name="image_width"        value="{$ItemEditPhotoThumbnail.editThumbnail.imageWidth}"/>
       <param name="image_height"       value="{$ItemEditPhotoThumbnail.editThumbnail.imageHeight}"/>
       <param name="crop_x"             value="{$ItemEditPhotoThumbnail.editThumbnail.cropLeft}"/>
       <param name="crop_y"             value="{$ItemEditPhotoThumbnail.editThumbnail.cropTop}"/>
       <param name="crop_width"         value="{$ItemEditPhotoThumbnail.editThumbnail.cropWidth}"/>
       <param name="crop_height"        value="{$ItemEditPhotoThumbnail.editThumbnail.cropHeight}"/>
       <param name="crop_ratio_width"   value="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioWidth}"/>
       <param name="crop_ratio_height"  value="{$ItemEditPhotoThumbnail.editThumbnail.cropRatioHeight}"/>
       <param name="crop_orientation"   value="{$ItemEditPhotoThumbnail.editThumbnail.selectedOrientation}"/>
       <param name="crop_to_size"       value="{$ItemEditPhotoThumbnail.editThumbnail.targetThumbnailSize}"/>
  </applet>

  <script type="text/javascript">
    // <![CDATA[
    function setAspectRatio(value) {ldelim}
      switch(value) {ldelim}
      {foreach from=$ItemEditPhotoThumbnail.editThumbnail.aspectRatioList key=index item=aspectRatio}
        case "{$index}":
          document.ImageCrop.setCropRatio({$aspectRatio.width}, {$aspectRatio.height});
          break;
      {/foreach}{literal}
      }
    }

    function setCropFields() {
      var frm = document.getElementById('itemAdminForm');
      frm.crop_x.value = document.ImageCrop.getCropX();
      frm.crop_y.value = document.ImageCrop.getCropY();
      frm.crop_width.value = document.ImageCrop.getCropWidth();
      frm.crop_height.value = document.ImageCrop.getCropHeight();
    }

    function setOrientation(orientation) {
      document.ImageCrop.setCropOrientation(orientation);
    }
    // ]]>
  {/literal}</script>

  <h2> {g->text text="Aspect Ratio: "} </h2>

  <select onchange="setAspectRatio(this.value)">
    {foreach from=$ItemEditPhotoThumbnail.editThumbnail.aspectRatioList key=index item=aspect}
      <option label="{$aspect.label}" value="{$index}"
	{if $ItemEditPhotoThumbnail.editThumbnail.selectedAspect == $index}
	  selected="selected"
	{/if}
      > {$aspect.label} </option>
    {/foreach}
  </select>

  <select onchange="setOrientation(this.value)">
    {html_options options=$ItemEditPhotoThumbnail.editThumbnail.orientationList
		  selected=$ItemEditPhotoThumbnail.editThumbnail.selectedOrientation}
  </select>

  <input type="hidden" id="crop_x" name="{g->formVar var="form[crop][x]"}"/>
  <input type="hidden" id="crop_y" name="{g->formVar var="form[crop][y]"}"/>
  <input type="hidden" id="crop_width" name="{g->formVar var="form[crop][width]"}"/>
  <input type="hidden" id="crop_height" name="{g->formVar var="form[crop][height]"}"/>

  <input type="submit" class="inputTypeSubmit" onclick="setCropFields(); return true"
   name="{g->formVar var="form[action][crop]"}" value="{g->text text="Crop"}"/>
  <input type="submit" class="inputTypeSubmit" onclick="setCropFields(); return true"
   name="{g->formVar var="form[action][reset]"}" value="{g->text text="Reset to default"}"/>

  {else}
  <b>
    {g->text text="There are no graphics toolkits enabled that support this type of photo, so we cannot crop the thumbnail."}
    {if $ItemEditPhotoThumbnail.isAdmin}
      <a href="{g->url arg1="view=core.SiteAdmin" arg2="subView=core.AdminModules"}">
	{g->text text="site admin"}
      </a>
    {/if}
  </b>
  {/if}
</div>
