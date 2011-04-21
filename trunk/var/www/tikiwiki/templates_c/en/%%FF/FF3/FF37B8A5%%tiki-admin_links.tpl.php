<?php /* Smarty version 2.6.14, created on 2011-04-21 08:16:57
         compiled from tiki-admin_links.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'tiki-admin_links.tpl', 29, false),array('modifier', 'escape', 'tiki-admin_links.tpl', 38, false),)), $this); ?>
<h1><a href="tiki-admin_links.php" class="pagetitle">Featured links</a>
  
      <?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
FeaturedLinksAdmin" target="tikihelp" class="tikihelp" title="admin featured links">
<img src="img/icons/help.gif" border="0" height="16" width="16" alt='help' /></a><?php endif; ?>

      <?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-admin_links.tpl" target="tikihelp" class="tikihelp" title="View template: admin featured links template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='Edit template' /></a><?php endif; ?></h1>

<div class="rbox" name="tip">
<div class="rbox-title" name="tip">Tip</div>  
<div class="rbox-data" name="tip">To use these links, you must assign the featured_links <a class="rbox-link" href="tiki-admin_modules.php"> module</a>.</div>
</div>
<br />


<a class="linkbut" href="tiki-admin_links.php?generate=1">Generate positions by hits</a>
<h2>List of featured links</h2>
<table class="normal">
<tr>
<td class="heading">url</td>
<td class="heading">title</td>
<td class="heading">hits</td>
<td class="heading">position</td>
<td class="heading">type</td>
<td class="heading">action</td>
</tr>
<?php echo smarty_function_cycle(array('values' => "odd,even",'print' => false), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
"><?php echo $this->_tpl_vars['links'][$this->_sections['user']['index']]['url']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['links'][$this->_sections['user']['index']]['title']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['links'][$this->_sections['user']['index']]['hits']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['links'][$this->_sections['user']['index']]['position']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['links'][$this->_sections['user']['index']]['type']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array(), $this);?>
">
 <a title="delete" class="link" href="tiki-admin_links.php?remove=<?php echo ((is_array($_tmp=$this->_tpl_vars['links'][$this->_sections['user']['index']]['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><img border="0" alt="remove" src="img/icons2/delete.gif" /></a>
 <a title="edit" class="link" href="tiki-admin_links.php?editurl=<?php echo ((is_array($_tmp=$this->_tpl_vars['links'][$this->_sections['user']['index']]['url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><img src="img/icons/edit.gif" border="0" width="20" height="16"  alt='edit' /></a>
</td>
</tr>
<?php endfor; else: ?>
<tr><td colspan="6">
<b>No records found</b>
</td></tr>
<?php endif; ?>
</table>
<br />
<?php if ($this->_tpl_vars['editurl'] == 'n'): ?>
<h2>Add Featured Link</h2>
<?php else: ?>
<h2>Edit this Featured Link: <?php echo $this->_tpl_vars['title']; ?>
</h2>
<a href="tiki-admin_links.php">Create new Featured Link</a>
<?php endif; ?>
<form action="tiki-admin_links.php" method="post">
<table class="normal">
<?php if ($this->_tpl_vars['editurl'] == 'n'): ?>
<tr class="formcolor"><td>URL</td><td><input type="text" name="url" /></td></tr>
<?php else: ?>
<tr class="formcolor"><td>URL</td><td><?php echo $this->_tpl_vars['editurl']; ?>

<input type="hidden" name="url" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['editurl'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</td></tr>
<?php endif; ?>
<tr class="formcolor"><td>Title</td><td><input type="text" name="title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr class="formcolor"><td>Position</td><td><input type="text" size="3" name="position" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['position'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /> (0 disables the link)</td></tr>
<tr class="formcolor"><td>Link type</td><td>
<select name="type">
<option value="r" <?php if ($this->_tpl_vars['type'] == 'r'): ?>selected="selected"<?php endif; ?>>replace current page</option>
<option value="f" <?php if ($this->_tpl_vars['type'] == 'f'): ?>selected="selected"<?php endif; ?>>framed</option>
<option value="n" <?php if ($this->_tpl_vars['type'] == 'n'): ?>selected="selected"<?php endif; ?>>open new window</option>
</select>
</td></tr>
<tr class="formcolor"><td>&nbsp;</td><td><input type="submit" name="add" value="save" /></td></tr>
</table>
</form>