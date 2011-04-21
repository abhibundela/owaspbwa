<?php /* Smarty version 2.6.14, created on 2011-04-21 08:15:55
         compiled from tiki-admin_external_wikis.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-admin_external_wikis.tpl', 14, false),array('modifier', 'times', 'tiki-admin_external_wikis.tpl', 58, false),array('function', 'cycle', 'tiki-admin_external_wikis.tpl', 31, false),)), $this); ?>
<h1><a class="pagetitle" href="tiki-admin_external_wikis.php">Admin external wikis</a>
  
      <?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
External+Wikis" target="tikihelp" class="tikihelp" title="admin External Wikis"><img src="img/icons/help.gif" border="0" height="16" width="16" alt='help' /></a>
<?php endif; ?>

      <?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-admin_external_wikis.tpl" target="tikihelp" class="tikihelp" title="View template: tiki admin external wikis template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='Edit template' /></a>
<?php endif; ?></h1>

<h2>Create/Edit External Wiki</h2>
<form action="tiki-admin_external_wikis.php" method="post">
<input type="hidden" name="extwikiId" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['extwikiId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<table class="normal">
<tr><td class="formcolor">name:</td><td class="formcolor"><input type="text" maxlength="255" size="10" name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="formcolor">URL (use $page to be replaced by the page name in the URL example: http://www.example.com/tiki-index.php?page=$page):</td><td class="formcolor"><input type="text" maxlength="255" size="40" name="extwiki" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['extwiki'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td  class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="save" value="Save" /></td></tr>
</table>
</form>

<h2>External Wiki</h2>
<!-- second table -->

<table class="normal">
<tr>
<td class="heading"><a class="tableheading" href="tiki-admin_external_wikis.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'name_desc'): ?>name_asc<?php else: ?>name_desc<?php endif; ?>">name</a></td>
<td class="heading"><a class="tableheading" href="tiki-admin_external_wikis.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'extwiki_desc'): ?>extwiki_asc<?php else: ?>extwiki_desc<?php endif; ?>">extwiki</a></td>
<td class="heading">action</td>
</tr>
<?php echo smarty_function_cycle(array('values' => "odd,even",'print' => false), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['channels']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
<tr>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['channels'][$this->_sections['user']['index']]['name']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['channels'][$this->_sections['user']['index']]['extwiki']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array(), $this);?>
">
   &nbsp;&nbsp;<a title="delete" class="link" href="tiki-admin_external_wikis.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;remove=<?php echo $this->_tpl_vars['channels'][$this->_sections['user']['index']]['extwikiId']; ?>
" 
><img src="img/icons2/delete.gif" border="0" height="16" width="16" alt='delete' /></a>&nbsp;&nbsp;
   <a title="edit" class="link" href="tiki-admin_external_wikis.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;extwikiId=<?php echo $this->_tpl_vars['channels'][$this->_sections['user']['index']]['extwikiId']; ?>
"><img src="img/icons/edit.gif" border="0" width="20" height="16"  alt='edit' /></a>
</td>
</tr>
<?php endfor; else: ?>
<tr><td class="odd" colspan="3">No records found</td></tr>
<?php endif; ?>
</table>
<div align="center">
<div class="mini">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-admin_external_wikis.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">prev</a>]&nbsp;
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;[<a class="prevnext" href="tiki-admin_external_wikis.php?find=<?php echo $this->_tpl_vars['find']; ?>
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
<a class="prevnext" href="tiki-admin_external_wikis.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">
<?php echo $this->_sections['foo']['index_next']; ?>
</a>&nbsp;
<?php endfor; endif;  endif; ?>
</div>
</div>