{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-last_created_blogs.tpl,v 1.9.10.1 2005/02/23 19:43:32 michael_davey Exp $ *}

{if $feature_blogs eq 'y'}
{if $nonums eq 'y'}
{eval var="{tr}Last `$module_rows` Created blogs{/tr}" assign="tpl_module_title"}
{else}
{eval var="{tr}Last Created blogs{/tr}" assign="tpl_module_title"}
{/if}
{tikimodule title=$tpl_module_title name="last_created_blogs" flip=$module_params.flip decorations=$module_params.decorations}
  <table  border="0" cellpadding="0" cellspacing="0">
    {section name=ix loop=$modLastCreatedBlogs}
      <tr>
        {if $nonums != 'y'}<td class="module" valign="top">{$smarty.section.ix.index_next})</td>{/if}
        <td class="module">&nbsp;
          <a class="linkmodule" href="tiki-view_blog.php?blogId={$modLastCreatedBlogs[ix].blogId}">
            {$modLastCreatedBlogs[ix].title}
          </a>
        </td>
      </tr>
    {/section}
  </table>
{/tikimodule}
{/if}
