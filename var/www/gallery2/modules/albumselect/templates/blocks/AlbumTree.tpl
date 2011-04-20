{*
 * $Revision: 1.10 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{g->callback type="albumselect.LoadAlbumData" albumTree=true stripTitles="true"}

{if isset($block.albumselect)}
<div class="{$class}">
  <div class="dtree">
    {assign var="params" value=$block.albumselect.LoadAlbumData.albumTree.params}
    {assign var="albumTree" value=$block.albumselect.LoadAlbumData.albumTree.albumTreeName}
    {if $params.treeExpandCollapse and !$params.treeCloseSameLevel}
      <p>
	<a href="javascript: {$albumTree}.openAll()"
	 onclick="this.blur()">{g->text text="Expand"}</a>
	|
	<a href="javascript: {$albumTree}.closeAll()"
	 onclick="this.blur()">{g->text text="Collapse"}</a>
      </p>
    {/if}

    <script type="text/javascript">
      // <![CDATA[
      function albumSelect_goToNode(nodeId) {ldelim}
        document.location = new String('{g->url arg1="view=core.ShowItem" arg2="itemId=__ID__" htmlEntities=false}').replace('__ID__', nodeId);
      {rdelim}

      var {$albumTree} = new dTree('{$albumTree}');
      var {$albumTree}_images = '{g->url href="modules/albumselect/images/"}'
      {$albumTree}.icon = {ldelim}
	  root            : {$albumTree}_images + 'base.gif',
	  folder          : {$albumTree}_images + 'folder.gif',
	  folderOpen      : {$albumTree}_images + 'imgfolder.gif',
	  node            : {$albumTree}_images + 'imgfolder.gif',
	  empty           : {$albumTree}_images + 'empty.gif',
	  line            : {$albumTree}_images + 'line.gif',
	  join            : {$albumTree}_images + 'join.gif',
	  joinBottom      : {$albumTree}_images + 'joinbottom.gif',
	  plus            : {$albumTree}_images + 'plus.gif',
	  plusBottom      : {$albumTree}_images + 'plusbottom.gif',
	  minus           : {$albumTree}_images + 'minus.gif',
	  minusBottom     : {$albumTree}_images + 'minusbottom.gif',
	  nlPlus          : {$albumTree}_images + 'nolines_plus.gif',
	  nlMinus         : {$albumTree}_images + 'nolines_minus.gif'
      {rdelim};
      {$albumTree}.config.useLines = {if $params.treeLines}true{else}false{/if};
      {$albumTree}.config.useIcons = {if $params.treeIcons}true{else}false{/if};
      {$albumTree}.config.useCookies = {if $params.treeCookies}true{else}false{/if};
      {$albumTree}.config.closeSameLevel = {if $params.treeCloseSameLevel}true{else}false{/if};
      {$albumTree}.add(0, -1, " {$block.albumselect.LoadAlbumData.albumTree.titlesForJs.root}",
		    '{g->url}');
      {foreach from=$block.albumselect.LoadAlbumData.albumTree.tree item=node}
	{assign var="title" value=$block.albumselect.LoadAlbumData.albumTree.titlesForJs[$node.id]}
	{$albumTree}.add({$node.nodeId}, {$node.parentNode}, "{$title}", 'javascript:albumSelect_goToNode({$node.id})');
      {/foreach}
      document.write({$albumTree});
      // ]]>
    </script>
  </div>
</div>
{/if}
