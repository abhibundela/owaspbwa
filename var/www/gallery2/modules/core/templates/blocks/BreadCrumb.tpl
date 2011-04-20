{*
 * $Revision: 1.4 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{*
 * Go through each breadcrumb and display it as a link.
 *
 * G2 uses the highlight id to figure out which page to draw when you follow the
 * breadcrumbs back up the album tree.  Don't make the last item a link.
 *}
<div class="{$class}">
  {section name=parent loop=$theme.parents}
  {if !$smarty.section.parent.last}
  <a href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$theme.parents[parent].id`"
		   arg3="highlightId=`$theme.parents[parent.index_next].id`"}"
     class="BreadCrumb-{counter name="BreadCrumb"}">
    {$theme.parents[parent].title|default:$theme.parents[parent].pathComponent|markup:strip}</a>
  {else}
  <a href="{g->url arg1="view=core.ShowItem" arg2="itemId=`$theme.parents[parent].id`"
		   arg3="highlightId=`$theme.item.id`"}"
     class="BreadCrumb-{counter name="BreadCrumb"}">
    {$theme.parents[parent].title|default:$theme.parents[parent].pathComponent|markup:strip}</a>
  {/if}
  {if isset($separator)} {$separator} {/if}
  {/section}

  {if ($theme.pageType == 'admin' || $theme.pageType == 'module')}
  <a href="{g->url arg1="view=core.ShowItem"
		   arg2="itemId=`$theme.item.id`"}" class="BreadCrumb-{counter name="BreadCrumb"}">
     {$theme.item.title|default:$theme.item.pathComponent|markup:strip}</a>
  {else}
  <span class="BreadCrumb-{counter name="BreadCrumb"}">
     {$theme.item.title|default:$theme.item.pathComponent|markup:strip}</span>
  {/if}
</div>
