{*
 * $Revision: 1.3 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if $callCount == 1}
<script type="text/javascript" src="{g->url href="lib/javascript/AutoComplete.js"}"></script>
<script type="text/javascript" src="{g->url href="lib/javascript/XmlHttp.js"}"></script>
{/if}
<script type="text/javascript">
  // <![CDATA[
  autoCompleteAttach('{$element}', '{$url}');
  // ]]>
</script>

