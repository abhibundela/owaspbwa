{*
 * $Revision: 1.11 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
<div class="gbBlock gcBackground1">
  <h2> {g->text text="Comment change confirmation"} </h2>
</div>

{if !empty($status)}
<div class="gbBlock"><h2 class="giSuccess">
  {if isset($status.added)}
    {g->text text="Comment added successfully"}
  {/if}
  {if isset($status.deleted)}
    {g->text text="Comment deleted successfully"}
  {/if}
  {if isset($status.saved)}
    {g->text text="Comment modified successfully"}
  {/if}
</h2></div>
{/if}
