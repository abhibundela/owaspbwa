<?php /* Smarty version 2.6.14, created on 2011-04-21 08:15:59
         compiled from tiki-admin_hotwords.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-admin_hotwords.tpl', 29, false),array('modifier', 'times', 'tiki-admin_hotwords.tpl', 78, false),)), $this); ?>
<h1><a class="pagetitle" href="tiki-admin_hotwords.php">Admin Hotwords</a>
  
      <?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
Hotwords" target="tikihelp" class="tikihelp" title="admin hotwords">
<img src="img/icons/help.gif" border="0" height="16" width="16" alt='help' /></a><?php endif; ?>

      <?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-admin_hotwords.tpl" target="tikihelp" class="tikihelp" title="View template: admin hotwords template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='edit template' /></a><?php endif; ?></h1>



<h2>Add Hotword</h2>

<form method="post" action="tiki-admin_hotwords.php">
<table class="normal">
<tr><td class="formcolor">Word</td><td class="formcolor"><input type="text" name="word" /></td></tr>
<tr><td class="formcolor">URL</td><td class="formcolor"><input type="text" name="url" /></td></tr>
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="add" value="Add" /></td></tr>
</table>
</form>

<h2>Hotwords</h2>
<div  align="center">
<table class="findtable">
<tr><td class="findtable">Find</td>
   <td class="findtable">
   <form method="get" action="tiki-admin_hotwords.php">
     <input type="text" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
     <input type="submit" value="find" name="search" />
     <input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
   </form>
   </td>
</tr>
</table>
</div>
<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="tiki-admin_hotwords.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'word_desc'): ?>word_asc<?php else: ?>word_desc<?php endif; ?>">Word</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_hotwords.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'url_desc'): ?>url_asc<?php else: ?>url_desc<?php endif; ?>">URL</a></td>
<td class="heading">action</td>
</tr>
<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['words']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['user']['show'] = true;
$this->_sections['user']['max'] = $this->_sections['user']['loop'];
$this->_sections['user']['step'] = 1;
$this->_sections['user']['start'] = $this->_sections['user']['step'] > 0 ? 0 : $this->_sections['user']['loop']-1;
if ($this->_sections['user']['show']) {
    $this->_sections['user']['total'] = $this->_sections['user']['loop'];
    if ($this->_sections['user']['total'] == 0)
        $this->_sections['user']['show'] = false;
} else
    $this->_sections['user']['total'] = 0;
if ($this->_sections['user']['show']):

            for ($this->_sections['user']['index'] = $this->_sections['user']['start'], $this->_sections['user']['iteration'] = 1;
                 $this->_sections['user']['iteration'] <= $this->_sections['user']['total'];
                 $this->_sections['user']['index'] += $this->_sections['user']['step'], $this->_sections['user']['iteration']++):
$this->_sections['user']['rownum'] = $this->_sections['user']['iteration'];
$this->_sections['user']['index_prev'] = $this->_sections['user']['index'] - $this->_sections['user']['step'];
$this->_sections['user']['index_next'] = $this->_sections['user']['index'] + $this->_sections['user']['step'];
$this->_sections['user']['first']      = ($this->_sections['user']['iteration'] == 1);
$this->_sections['user']['last']       = ($this->_sections['user']['iteration'] == $this->_sections['user']['total']);
 if ($this->_sections['user']['index'] % 2): ?>
<tr>
<td class="odd"><?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['word']; ?>
</td>
<td class="odd"><?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['url']; ?>
</td>
<td class="odd">
&nbsp;&nbsp;<a class="link" href="tiki-admin_hotwords.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;remove=<?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['word']; ?>
" 
title="delete"><img src="img/icons2/delete.gif" border="0" height="16" width="16" alt='delete' /></a>&nbsp;&nbsp;
</td>
</tr>
<?php else: ?>
<tr>
<td class="even"><?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['word']; ?>
</td>
<td class="even"><?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['url']; ?>
</td>
<td class="even">
&nbsp;&nbsp;<a class="link" href="tiki-admin_hotwords.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;remove=<?php echo $this->_tpl_vars['words'][$this->_sections['user']['index']]['word']; ?>
" 
title="delete"><img src="img/icons2/delete.gif" border="0" height="16" width="16" alt='delete' /></a>&nbsp;&nbsp;
</td>
</tr>
<?php endif;  endfor; else: ?>
<tr><td colspan="3" class="odd">No records found</td></tr>
<?php endif; ?>
</table>
<div class="mini">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" class="link" href="tiki-admin_hotwords.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">prev</a>]&nbsp;
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;[<a class="prevnext" class="link" href="tiki-admin_hotwords.php?find=<?php echo $this->_tpl_vars['find']; ?>
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
<a class="prevnext" href="tiki-admin_hotwords.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">
<?php echo $this->_sections['foo']['index_next']; ?>
</a>&nbsp;
<?php endfor; endif;  endif; ?>
</div>