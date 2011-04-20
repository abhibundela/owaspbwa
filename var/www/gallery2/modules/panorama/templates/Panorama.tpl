{*
 * $Revision: 1.8 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gsContent">
  <div class="gbBlock gcBackground1">
    <h2> {g->text text=$Panorama.item.title|default:$Panorama.item.pathComponent} </h2>
  </div>

  <div class="gbBlock">
    {assign var="totalHeight" value=$Panorama.image.height+17}
    <object width="{$Panorama.width}" height="{$totalHeight}"
	    classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93">
    <!--[if !IE]>-->
    <object width="{$Panorama.width}" height="{$totalHeight}"
	    archive="{$Panorama.moduleUrl}/java/Metamorphose.jar" classid="java:Metamorphose">
    <!--<![endif]-->
      <param name="code" value="Metamorphose"/>
      <param name="archive" value="{$Panorama.moduleUrl}/java/Metamorphose.jar"/>
      <param name="BackgroundColor" value="#666666"/>
      <param name="PanoramaRect" value="0,0,{$Panorama.width},{$Panorama.image.height}"/>
      <param name="ScrollerRect" value="0,{$Panorama.image.height},{$Panorama.width},17"/>
      <param name="ScrollerThumb" value="{$Panorama.moduleUrl}/images/slider.png"/>
      <param name="PanoramaTile" value="{$Panorama.imageUrl}"/>
      <param name="PanoramaSize" value="{$Panorama.image.width},{$Panorama.image.height}"/>
    <!--[if !IE]>-->
    </object>
    <!--<![endif]-->
    </object>
  </div>
</div>
