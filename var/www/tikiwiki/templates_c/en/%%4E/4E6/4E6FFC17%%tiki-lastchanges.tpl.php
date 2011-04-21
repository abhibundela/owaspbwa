<?php /* Smarty version 2.6.14, created on 2011-04-21 08:20:38
         compiled from tiki-lastchanges.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-lastchanges.tpl', 15, false),array('modifier', 'tiki_short_datetime', 'tiki-lastchanges.tpl', 45, false),array('modifier', 'truncate', 'tiki-lastchanges.tpl', 46, false),array('modifier', 'times', 'tiki-lastchanges.tpl', 87, false),array('function', 'cycle', 'tiki-lastchanges.tpl', 42, false),array('block', 'tr', 'tiki-lastchanges.tpl', 63, false),)), $this); ?>
<h1><a href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
" class="pagetitle">Last Changes</a></h1>
<a class="linkbut" href="tiki-lastchanges.php?days=1">Today</a>
<a class="linkbut" href="tiki-lastchanges.php?days=2">Last 2 days</a>
<a class="linkbut" href="tiki-lastchanges.php?days=3">Last 3 days</a>
<a class="linkbut" href="tiki-lastchanges.php?days=5">Last 5 days</a>
<a class="linkbut" href="tiki-lastchanges.php?days=7">Last week</a>
<a class="linkbut" href="tiki-lastchanges.php?days=14">Last 2 Weeks</a>
<a class="linkbut" href="tiki-lastchanges.php?days=31">Last month</a>
<a class="linkbut" href="tiki-lastchanges.php?days=0">All</a>
<br /><br />
<table class="findtable">
<tr><td class="findtable">Find</td>
   <td class="findtable">
   <form method="get" action="tiki-lastchanges.php">
     <input type="text" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
     <input type="submit" value="find" name="search" />
     <input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
     <input type="hidden" name="days" value="0" />
   </form>
   </td>
<?php if ($this->_tpl_vars['findwhat'] != ""): ?>
   <td>
   <a href="tiki-lastchanges.php" class="wiki">Search by Date</a>
   </td>
<?php endif; ?>   
</tr>
</table>
<br />
<?php if ($this->_tpl_vars['findwhat'] != ""): ?>
Found "<b><?php echo $this->_tpl_vars['findwhat']; ?>
</b>" in <?php echo $this->_tpl_vars['cant_records']; ?>
 LastChanges 
<?php endif; ?>
<div align="left">
<table class="normal">
<tr>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'lastModif_desc'): ?>lastModif_asc<?php else: ?>lastModif_desc<?php endif; ?>">Date</a></td>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'pageName_desc'): ?>pageName_asc<?php else: ?>pageName_desc<?php endif; ?>">Page</a></td>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'action_desc'): ?> action_asc<?php else: ?>action_desc<?php endif; ?>">Action</a></td>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'user_desc'): ?>user_asc<?php else: ?>user_desc<?php endif; ?>">User</a></td>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'ip_desc'): ?>ip_asc<?php else: ?>ip_desc<?php endif; ?>">Ip</a></td>
<td class="heading" bgcolor="#bbbbbb"><a class="tableheading" href="tiki-lastchanges.php?days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'comment_desc'): ?>comment_asc<?php else: ?>comment_desc<?php endif; ?>">Comment</a></td>
</tr>
<?php echo smarty_function_cycle(array('values' => "odd,even",'print' => false), $this);?>

<?php unset($this->_sections['changes']);
$this->_sections['changes']['name'] = 'changes';
$this->_sections['changes']['loop'] = is_array($_loop=$this->_tpl_vars['lastchanges']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['changes']['show'] = true;
$this->_sections['changes']['max'] = $this->_sections['changes']['loop'];
$this->_sections['changes']['step'] = 1;
$this->_sections['changes']['start'] = $this->_sections['changes']['step'] > 0 ? 0 : $this->_sections['changes']['loop']-1;
if ($this->_sections['changes']['show']) {
    $this->_sections['changes']['total'] = $this->_sections['changes']['loop'];
    if ($this->_sections['changes']['total'] == 0)
        $this->_sections['changes']['show'] = false;
} else
    $this->_sections['changes']['total'] = 0;
if ($this->_sections['changes']['show']):

            for ($this->_sections['changes']['index'] = $this->_sections['changes']['start'], $this->_sections['changes']['iteration'] = 1;
                 $this->_sections['changes']['iteration'] <= $this->_sections['changes']['total'];
                 $this->_sections['changes']['index'] += $this->_sections['changes']['step'], $this->_sections['changes']['iteration']++):
$this->_sections['changes']['rownum'] = $this->_sections['changes']['iteration'];
$this->_sections['changes']['index_prev'] = $this->_sections['changes']['index'] - $this->_sections['changes']['step'];
$this->_sections['changes']['index_next'] = $this->_sections['changes']['index'] + $this->_sections['changes']['step'];
$this->_sections['changes']['first']      = ($this->_sections['changes']['iteration'] == 1);
$this->_sections['changes']['last']       = ($this->_sections['changes']['iteration'] == $this->_sections['changes']['total']);
?>
<tr class="<?php echo smarty_function_cycle(array(), $this);?>
">
<td>&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['lastModif'])) ? $this->_run_mod_handler('tiki_short_datetime', true, $_tmp) : smarty_modifier_tiki_short_datetime($_tmp)); ?>
&nbsp;</td>
<td>&nbsp;<a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="tablename" title="<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...", true) : smarty_modifier_truncate($_tmp, 20, "...", true)); ?>
</a> 
<?php if ($this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']): ?>
(<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">hist</a> v<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
</span>)
&nbsp;<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;preview=<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
"
 title="view">v</a>&nbsp;
<?php if ($this->_tpl_vars['tiki_p_rollback'] == 'y'): ?>
<a class="link" href="tiki-rollback.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;version=<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
" title="rollback">b</a>&nbsp;
<?php endif; ?>
<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;diff=<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
" title="compare">c</a>&nbsp;
<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;diff2=<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
" title="diff">d</a>&nbsp;
<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;source=<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['version']; ?>
" title="source">s</a>
<?php elseif ($this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['versionlast']): ?>
(<a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">hist</a>)
<?php endif; ?>

</td>

<td><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['action'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
<td>&nbsp;<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['user']; ?>
&nbsp;</td>
<td>&nbsp;<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['ip']; ?>
&nbsp;</td>
<td>&nbsp;<?php echo $this->_tpl_vars['lastchanges'][$this->_sections['changes']['index']]['comment']; ?>
&nbsp;</td>

</tr>
<?php endfor; else: ?>
<tr><td class="even" colspan="6">
<b>No records found</b>
</td></tr>
<?php endif; ?>
</table>
<br />
<div class="mini" align="center">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-lastchanges.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">prev</a>]&nbsp;
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;[<a class="prevnext" href="tiki-lastchanges.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['next_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">next</a>]
<?php endif;  if ($this->_tpl_vars['direct_pagination'] == 'y'): ?>
<br />
<?php unset($this->_sections['foo']);
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['cant_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
 $this->assign('selector_offset', ((is_array($_tmp=$this->_sections['foo']['index'])) ? $this->_run_mod_handler('times', true, $_tmp, $this->_tpl_vars['maxRecords']) : smarty_modifier_times($_tmp, $this->_tpl_vars['maxRecords']))); ?>
<a class="prevnext" href="tiki-lastchanges.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;days=<?php echo $this->_tpl_vars['days']; ?>
&amp;offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">
<?php echo $this->_sections['foo']['index_next']; ?>
</a>&nbsp;
<?php endfor; endif;  endif; ?>
</div>
</div>