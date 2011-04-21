<?php /* Smarty version 2.6.14, created on 2011-04-21 08:16:52
         compiled from tiki-admin_modules.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'tiki-admin_modules.tpl', 42, false),array('modifier', 'escape', 'tiki-admin_modules.tpl', 74, false),array('modifier', 'truncate', 'tiki-admin_modules.tpl', 270, false),)), $this); ?>


<h1><a class="pagetitle" href="tiki-admin_modules.php">Admin Modules</a>

  
      <?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
Modules+Admin" target="tikihelp" class="tikihelp" title="admin modules">
<img border='0' src='img/icons/help.gif' alt="help" /></a><?php endif; ?>



      <?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-admin_modules.tpl" target="tikihelp" class="tikihelp" title="View template: admin modules template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='edit' /></a><?php endif; ?></h1>


<p>
<a class="linkbut" href="#assign">assign module</a>
<a class="linkbut" href="#leftmod">left modules</a>
<a class="linkbut" href="#rightmod">right modules</a>
<a class="linkbut" href="#editcreate">edit/create</a>
<a class="linkbut" href="tiki-admin_modules.php?clear_cache=1">clear cache</a>
</p>

<div class="simplebox">

<b>Note 1</b>: if you allow your users to configure modules then assigned
modules won't be reflected in the screen until you configure them
from MyTiki->modules.<br />
<b>Note 2</b>: If you assign modules to groups make sure that you
have turned off the option 'display modules to all groups always'
from Admin->General

</div>
<h2>User Modules</h2>
<table class="normal">
<tr>
<td class="heading">name</td>
<td class="heading">title</td>
<td class="heading">action</td>
</tr>
<?php echo smarty_function_cycle(array('print' => false,'values' => "even,odd"), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['user_modules']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
"><?php echo $this->_tpl_vars['user_modules'][$this->_sections['user']['index']]['name']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['user_modules'][$this->_sections['user']['index']]['title']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array(), $this);?>
"><a class="link" href="tiki-admin_modules.php?um_edit=<?php echo $this->_tpl_vars['user_modules'][$this->_sections['user']['index']]['name']; ?>
#editcreate">edit</a>
             <a class="link" href="tiki-admin_modules.php?edit_assign=<?php echo $this->_tpl_vars['user_modules'][$this->_sections['user']['index']]['name']; ?>
#assign">assign</a>
             <a class="link" href="tiki-admin_modules.php?um_remove=<?php echo $this->_tpl_vars['user_modules'][$this->_sections['user']['index']]['name']; ?>
">delete</a></td>
</tr>
<?php endfor; else: ?>
<tr><td colspan="6" class="odd">
<b>No records found</b>
</td></tr>
<?php endif; ?>
</table>
<br />
<a name="assign"></a>
<?php if ($this->_tpl_vars['assign_name'] == ''): ?>
<h2>Assign new module</h2>
<?php else: ?>
<h2>Edit this assigned module: <?php echo $this->_tpl_vars['assign_name']; ?>
</h2>
<a href="tiki-admin_modules.php" class="linkbut">Assign new module</a>
<?php endif;  if ($this->_tpl_vars['preview'] == 'y'): ?>
<br />Preview<br />
<?php echo $this->_tpl_vars['preview_data']; ?>

<?php endif; ?>
<form method="post" action="tiki-admin_modules.php#assign">
<table class="normal">
<tr><td class="formcolor">Module Name</td><td class="formcolor">
<select name="assign_name">
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['all_modules']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['all_modules'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['assign_name'] == $this->_tpl_vars['all_modules'][$this->_sections['ix']['index']] || $this->_tpl_vars['assign_selected'] == $this->_tpl_vars['all_modules'][$this->_sections['ix']['index']]): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['all_modules'][$this->_sections['ix']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select>
</td></tr>
<!--<tr><td>Title</td><td><input type="text" name="assign_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['assign_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>-->
<tr><td class="formcolor">Position</td><td class="formcolor">
<select name="assign_position">
<option value="l" <?php if ($this->_tpl_vars['assign_position'] == 'l'): ?>selected="selected"<?php endif; ?>>left</option>
<option value="r" <?php if ($this->_tpl_vars['assign_position'] == 'r'): ?>selected="selected"<?php endif; ?>>right</option>
</select>
</td></tr>
<tr><td class="formcolor">Order</td><td class="formcolor">
<select name="assign_order">
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['orders'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['assign_order'] == $this->_tpl_vars['orders'][$this->_sections['ix']['index']]): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['orders'][$this->_sections['ix']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select>
</td></tr>
<tr><td class="formcolor">Cache Time (secs)</td><td class="formcolor"><input type="text" name="assign_cache" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['assign_cache'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="formcolor">Rows</td><td class="formcolor"><input type="text" name="assign_rows" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['assign_rows'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="formcolor">Parameters</td><td class="formcolor"><input type="text" name="assign_params" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['assign_params'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="formcolor">Groups</td><td class="formcolor">
<select multiple="multiple" name="groups[]">
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['groups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['groups'][$this->_sections['ix']['index']]['selected'] == 'y'): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td></tr>
<?php if ($this->_tpl_vars['user_assigned_modules'] == 'y'): ?>
<tr><td class="formcolor">Visibility</td><td class="formcolor">
<select name="assign_type">
<option value="d" <?php if ($this->_tpl_vars['assign_type'] == 'd'): ?>selected="selected"<?php endif; ?>>Displayed for the eligible users with no personal assigned modules</option>
<option value="D" <?php if ($this->_tpl_vars['assign_type'] == 'D'): ?>selected="selected"<?php endif; ?>>Displayed now for all eligible users even with personal assigned modules</option>
<option value="P" <?php if ($this->_tpl_vars['assign_type'] == 'P'): ?>selected="selected"<?php endif; ?>>Displayed now, can't be unassigned</option>
<option value="h" <?php if ($this->_tpl_vars['assign_type'] == 'h'): ?>selected="selected"<?php endif; ?>>Not displayed until a user chooses it</option>
</select>
</td></tr>
<?php endif; ?>
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="preview" value="preview"><input type="submit" name="assign" value="assign"></td></tr>
</table>
</form>
<br />
<h2>Assigned Modules</h2>
<a name="leftmod"></a>
<table class="normal">
<caption>Left Modules</caption>
<tr>
<td class="heading">name</td>
<td class="heading">order</td>
<td class="heading">cache</td>
<td class="heading">rows</td>
<td class="heading">parameters</td>
<td class="heading">groups</td>
<td class="heading">action</td>
</tr>
<?php echo smarty_function_cycle(array('print' => false,'values' => "even,odd"), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['left']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['name']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['ord']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['cache_time']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['rows']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['params']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['left'][$this->_sections['user']['index']]['module_groups']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array(), $this);?>
">
             <a class="link" href="tiki-admin_modules.php?edit_assign=<?php echo ((is_array($_tmp=$this->_tpl_vars['left'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#assign">edit</a>
             <a class="link" href="tiki-admin_modules.php?modup=<?php echo ((is_array($_tmp=$this->_tpl_vars['left'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#leftmod">up</a>
             <a class="link" href="tiki-admin_modules.php?moddown=<?php echo ((is_array($_tmp=$this->_tpl_vars['left'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#leftmod">down</a>
             <a class="link" href="tiki-admin_modules.php?unassign=<?php echo ((is_array($_tmp=$this->_tpl_vars['left'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#leftmod">x</a></td>
</tr>
<?php endfor; else: ?>
<tr><td colspan="6">
<b>No records found</b>
</td></tr>
<?php endif; ?>
</table>
<br />
<br />
<a name="rightmod"></a>
<table class="normal">
<caption>Right Modules</caption>
<tr>
<td class="heading">name</td>
<td class="heading">order</td>
<td class="heading">cache</td>
<td class="heading">rows</td>
<td class="heading">parameters</td>
<td class="heading">groups</td>
<td class="heading">action</td>
</tr>
<?php echo smarty_function_cycle(array('print' => false,'values' => "even,odd"), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['right']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['name']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['ord']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['cache_time']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['rows']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['params']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['right'][$this->_sections['user']['index']]['module_groups']; ?>
</td>
<td class="<?php echo smarty_function_cycle(array(), $this);?>
">
             <a class="link" href="tiki-admin_modules.php?edit_assign=<?php echo ((is_array($_tmp=$this->_tpl_vars['right'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#assign">edit</a>
             <a class="link" href="tiki-admin_modules.php?modup=<?php echo ((is_array($_tmp=$this->_tpl_vars['right'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#rightmod">up</a>
             <a class="link" href="tiki-admin_modules.php?moddown=<?php echo ((is_array($_tmp=$this->_tpl_vars['right'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#rightmod">down</a>
             <a class="link" href="tiki-admin_modules.php?unassign=<?php echo ((is_array($_tmp=$this->_tpl_vars['right'][$this->_sections['user']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
#rightmod">x</a></td>
</tr>
<?php endfor; else: ?>
<tr><td colspan="6">
<b>No records found</b>
</td></tr>
<?php endif; ?>
</table>
<br />
<a name="editcreate"></a>
<?php if ($this->_tpl_vars['um_name'] == ''): ?>
<h2>Create new user module</h2>
<?php else: ?>
<h2>Edit this user module: <?php echo $this->_tpl_vars['um_name']; ?>
</h2>
<?php endif; ?>
<table class="normal"><tr><td valign="top" class="odd">
	<?php if ($this->_tpl_vars['wysiwyg'] == 'n'): ?>
		<a class="linkbut" href="tiki-admin_modules.php?wysiwyg=y#editcreate">Use wysiwyg editor</a>
	<?php else: ?>
		<a class="linkbut" href="tiki-admin_modules.php?wysiwyg=n#editcreate">Use normal editor</a>
	<?php endif;  if ($this->_tpl_vars['um_name'] != ''): ?>
<a href="tiki-admin_modules.php#editcreate">Create new user module</a>
<?php endif; ?>
<form name='editusr' method="post" action="tiki-admin_modules.php">
<table>
<tr><td class="form">Name</td><td><input type="text" name="um_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['um_name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="form">Title</td><td><input type="text" name="um_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['um_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr><td class="form">Data</td><td>


<textarea id='usermoduledata' name="um_data" rows="10" cols="40"><?php echo ((is_array($_tmp=$this->_tpl_vars['um_data'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>

<?php if ($this->_tpl_vars['wysiwyg'] == 'y'): ?>
	<script type="text/javascript" src="lib/htmlarea/htmlarea.js"></script>
	<script type="text/javascript" src="lib/htmlarea/htmlarea-lang-en.js"></script>
	<script type="text/javascript" src="lib/htmlarea/dialog.js"></script>
	<style type="text/css">
		@import url(lib/htmlarea/htmlarea.css);
	</style>
	<script defer='defer'>(new HTMLArea(document.forms['editusr']['um_data'])).generate();</script>
<?php endif; ?>

</td></tr>
<tr><td class="form">Must be wiki parsed</td><td class="form"><input type="checkbox" name="um_parse" value="y" <?php if ($this->_tpl_vars['um_parse'] == 'y'): ?>checked="checked"<?php endif; ?>/></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="um_update" value="create/edit" /></td></tr>
</table>
</form>
</td><td valign="top" class="even">
<h3>Objects that can be included</h3>
<table>
<tr>
  <td class="form">
    Available polls:
  </td>
  <td>
    <select name="polls" id='list_polls'>
	<option value="<?php echo '{'; ?>
poll<?php echo '}'; ?>
">--Random active poll--</option>
	<option value="<?php echo '{'; ?>
poll id=current<?php echo '}'; ?>
">--Random current poll--</option> 
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['polls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
poll id=<?php echo $this->_tpl_vars['polls'][$this->_sections['ix']['index']]['pollId'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['polls'][$this->_sections['ix']['index']]['title']; ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_polls');">use poll</a>
  </td>
</tr>

<tr>
  <td class="form">
   Random image from:
  </td>
  <td>
   <select name="galleries" id='list_galleries'>
   <option value="<?php echo '{'; ?>
gallery id=-1 showgalleryname=1<?php echo '}'; ?>
">All galleries</option>
   <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['galleries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
   <option value="<?php echo '{'; ?>
gallery id=<?php echo $this->_tpl_vars['galleries'][$this->_sections['ix']['index']]['galleryId']; ?>
 showgalleryname=0<?php echo '}'; ?>
"><?php echo $this->_tpl_vars['galleries'][$this->_sections['ix']['index']]['name']; ?>
</option>
   <?php endfor; endif; ?>
  </td>
  <td class="form">
   <a class="link" href="javascript:setUserModuleFromCombo('list_galleries');">use gallery</a>
  </td>
</tr>


<tr>
  <td class="form">
    Dynamic content blocks:
  </td>
  <td>
    <select name="contents" id='list_contents'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['contents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
content id=<?php echo $this->_tpl_vars['contents'][$this->_sections['ix']['index']]['contentId'];  echo '}'; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['contents'][$this->_sections['ix']['index']]['description'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...", true) : smarty_modifier_truncate($_tmp, 20, "...", true)); ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_contents');">use dynamic  content</a>
  </td>
</tr>
<tr>
  <td class="form">
    RSS modules:
  </td>
  <td>
    <select name="rsss" id='list_rsss'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['rsss']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
rss id=<?php echo $this->_tpl_vars['rsss'][$this->_sections['ix']['index']]['rssId'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['rsss'][$this->_sections['ix']['index']]['name']; ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_rsss');">use rss module</a>
  </td>
</tr>

<tr>
  <td class="form">
    Menus:
  </td>
  <td>
    <select name="menus" id='list_menus'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['menus']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
menu id=<?php echo $this->_tpl_vars['menus'][$this->_sections['ix']['index']]['menuId'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['menus'][$this->_sections['ix']['index']]['name']; ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_menus');">use menu</a>
  </td>
</tr>

<?php if ($this->_tpl_vars['feature_phplayers'] == 'y'): ?>
<tr>
  <td class="form">
    phpLayersMenus:
  </td>
  <td>
    <select name="phpmenus" id='list_phpmenus'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['menus']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
phplayers id=<?php echo $this->_tpl_vars['menus'][$this->_sections['ix']['index']]['menuId'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['menus'][$this->_sections['ix']['index']]['name']; ?>
</option>
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_phpmenus');">use phplayermenu</a>
  </td>
</tr>
                                                                                                                                                                           
<?php endif; ?>
<tr>
  <td class="form">
    Banner zones:
  </td>
  <td>
    <select name="banners" id='list_banners'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['banners']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
banner zone=<?php echo $this->_tpl_vars['banners'][$this->_sections['ix']['index']]['zone'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['banners'][$this->_sections['ix']['index']]['zone']; ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_banners');">use banner zone</a>
  </td>
</tr>

<tr>
  <td class="form">
    Wiki Structures:
  </td>
  <td>
    <select name=wiki"structures" id='list_wikistructures'>
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['wikistructures']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    <option value="<?php echo '{'; ?>
wikistructure id=<?php echo $this->_tpl_vars['wikistructures'][$this->_sections['ix']['index']]['page_ref_id'];  echo '}'; ?>
"><?php echo $this->_tpl_vars['wikistructures'][$this->_sections['ix']['index']]['pageName']; ?>
</option>   
    <?php endfor; endif; ?>
    </select>
  </td>
  <td class="form">
    <a class="link" href="javascript:setUserModuleFromCombo('list_wikistructures');">use wiki structure</a>
  </td>
</tr>


</table>
</td></tr></table>