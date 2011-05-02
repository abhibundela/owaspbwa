{*
 * $Revision: 1.2 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{strip}
{if $child.canContainChildren}
  {assign var="riClass" value=riAlbum}
{else}
  {assign var="riClass" value=riItem}
{/if}
{capture name="riTitle"}{$child.title|default:$child.pathComponent|markup} ({g->date
					      timestamp=$child.originationTimestamp}){/capture}
{if isset($child.thumbnail)}
  {g->image item=$child image=$child.thumbnail maxSize=100
	    class=$riClass title=$smarty.capture.riTitle}
{else}
  <div class="{$riClass}">{$smarty.capture.riTitle}</div>
{/if}
{/strip}
