{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-users_rank.tpl,v 1.1.2.6 2006/01/18 19:44:00 fmathias Exp $ *}

{if $feature_score eq 'y'}
{tikimodule title="<a href=\"tiki-list_users.php\">{tr}Top users{/tr}</a>" name="users_rank" flip=$module_params.flip decorations=$module_params.decorations}
<table border="0" cellpadding="0" cellspacing="0">
{section loop=$users_rank name=u}
  <tr>
    <td class="module">{$users_rank[u].position})&nbsp;</td>
    <td class="module">{$users_rank[u].score}</td>
    <td class="module">&nbsp;{$users_rank[u].login|userlink}</td>
  </tr>
{/section}
</table>
{/tikimodule}
{/if}