{*
 * $Revision: 1.1 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div style="display: none">
{foreach from=$theme.children key=i item=it}
  {if isset($it.image)}
    {if isset($it.renderItem)}
      <a id="img_{$it.imageIndex}" href="{g->url arg1="view=core.ShowItem"
          arg2="itemId=`$it.id`" arg3="renderId=`$it.image.id`"}"></a>
    {else}
      <a id="img_{$it.imageIndex}" href="{g->url arg1="view=core.DownloadItem"
       arg2="itemId=`$it.image.id`" arg3="serialNumber=`$it.image.serialNumber`"}"></a>
    {/if}
    <span id="title_{$it.imageIndex}">{$it.title|markup}</span>
    <select id="links_{$it.imageIndex}">
     {foreach from=$it.itemLinks item=link}
      <option label="{$link.text}" value="{g->url params=$link.params}">{$link.text}</option>
     {/foreach}
    </select>
  {/if}
{/foreach}
</div>

<div id="imagearea" class="gcBackground1"><div id="image"></div></div>

<div id="titlebar" class="gcBackground2 gcBorder2">
  <div id="tools_left">
    <img id="opts" src="{$theme.themeUrl}/images/tool.png" width="18" height="18"
     onclick="options_onoff()" alt="{g->text text="Options"}" title="{g->text text="Options"}"
  /><img id="slide_poz" src="{$theme.themeUrl}/images/poz.png" width="18" height="18"
     onclick="slide_onoff()"
     alt="{g->text text="Pause Slideshow"}" title="{g->text text="Pause Slideshow"}"
  /><img id="slide_fwd" src="{$theme.themeUrl}/images/fwd.png" width="18" height="18"
     onclick="slide_onoff()"
     alt="{g->text text="Start Slideshow"}" title="{g->text text="Start Slideshow"}"
  /><img id="slide_rev" src="{$theme.themeUrl}/images/rev.png" width="18" height="18"
     onclick="slide_onoff()"
     alt="{g->text text="Start Slideshow"}" title="{g->text text="Start Slideshow"}"
  /><img id="slide_rand" src="{$theme.themeUrl}/images/rand.png" width="18" height="18"
     onclick="slide_onoff()"
     alt="{g->text text="Start Slideshow"}" title="{g->text text="Start Slideshow"}"
  /></div>
  <div id="tools_right">
    <img id="full_size" src="{$theme.themeUrl}/images/full.png" width="18" height="18"
     onclick="image_zoom(1)" alt="{g->text text="Full Size"}" title="{g->text text="Full Size"}"
  /><img id="fit_size" src="{$theme.themeUrl}/images/fit.png" width="18" height="18"
     onclick="image_zoom(0)" alt="{g->text text="Fit Size"}" title="{g->text text="Fit Size"}"
  /><img id="prev_off" src="{$theme.themeUrl}/images/prev-off.png" width="18" height="18"
     alt="{g->text text="No Previous Image"}" title="{g->text text="No Previous Image"}"
  /><img id="prev_img" src="{$theme.themeUrl}/images/prev.png" width="18" height="18"
     onclick="image_prev()"
     alt="{g->text text="Previous Image"}" title="{g->text text="Previous Image"}"
  /><img id="next_off" src="{$theme.themeUrl}/images/next-off.png" width="18" height="18"
     alt="{g->text text="No Next Image"}" title="{g->text text="No Next Image"}"
  /><img id="next_img" src="{$theme.themeUrl}/images/next.png" width="18" height="18"
     onclick="image_next()" alt="{g->text text="Next Image"}" title="{g->text text="Next Image"}"
  /></div>
  <div id="title" class="giTitle">&nbsp;</div>

  <div id="thumbs" class="gcBackground1 gcBorder2 sliderHoriz">
    <noscript><p class="giError">
      {g->text text="Warning: This site requires javascript."}
    </p></noscript>
    {foreach from=$theme.children key=i item=it}{strip}
    {if isset($it.image)}
      <a href="" onclick="image_show({$it.imageIndex});return false">
	{if isset($it.thumbnail)}
	  {g->image item=$it image=$it.thumbnail class=hthumb}
	{else}
	  <p>{g->text text="no thumbnail"}</p>
	{/if}
      </a>
    {/if}
    {/strip}{/foreach}
  </div>
</div>

<div id="options" class="gcBorder2">
  {g->theme include="sidebar.tpl"}
</div>

<script type="text/javascript">app_init();</script>
