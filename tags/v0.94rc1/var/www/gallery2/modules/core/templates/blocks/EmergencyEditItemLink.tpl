{*
 * $Revision: 1.3 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if !isset($item)} {assign var="item" value=$theme.item} {/if}
{g->callback type="core.ShouldShowEmergencyEditItemLink"
	     permissions=$permissions|default:$theme.permissions
	     checkSidebarBlocks=$checkSidebarBlocks|default:false
	     checkAlbumBlocks=$checkAlbumBlocks|default:false
	     checkPhotoBlocks=$checkPhotoBlocks|default:false}

{if ($block.core.ShouldShowEmergencyEditItemLink)}
<div class="{$class}">
  <a href="{g->url arg1="view=core.ItemAdmin" arg2="subView=core.ItemEdit"
		   arg3="itemId=`$item.id`" arg4="return=true"}"> {g->text text="Edit"} </a>
</div>
{/if}
