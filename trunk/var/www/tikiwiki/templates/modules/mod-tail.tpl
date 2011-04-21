{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-tail.tpl,v 1.4.10.2 2005/02/23 14:59:54 michael_davey Exp $ *}

{if $feature_tail eq 'y'}
{popup_init src="lib/overlib.js"}
{tikimodule title="$tailtitle" name="tail" flip=$module_params.flip decorations=$module_params.decorations}
{section name=ix loop=$tail}
<div class="module">{$tail[ix]}</div>
{/section}
{/tikimodule}
{/if}
