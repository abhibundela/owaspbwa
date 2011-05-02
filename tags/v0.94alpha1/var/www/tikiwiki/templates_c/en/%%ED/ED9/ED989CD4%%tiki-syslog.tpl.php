<?php /* Smarty version 2.6.14, created on 2011-04-21 08:16:06
         compiled from tiki-syslog.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-syslog.tpl', 22, false),array('modifier', 'tiki_long_datetime', 'tiki-syslog.tpl', 45, false),array('modifier', 'tiki_short_time', 'tiki-syslog.tpl', 45, false),array('modifier', 'truncate', 'tiki-syslog.tpl', 47, false),array('function', 'cycle', 'tiki-syslog.tpl', 40, false),)), $this); ?>
<h1><a class="pagetitle" href="tiki-syslog.php">SysLog</a>

<?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="http://tikiwiki.org/tiki-index.php?page=Syslog" target="tikihelp" class="tikihelp" title="Tikiwiki.org help: system logs">
<img border='0' src='img/icons/help.gif' alt="help" /></a><?php endif; ?>

<?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-syslog.tpl" target="tikihelp" class="tikihelp" title="View tpl: system logs tpl">
<img src="img/icons/info.gif" border="0" height="16" width="16" alt='edit tpl' /></a><?php endif; ?>
</h1>

<?php if ($this->_tpl_vars['tikifeedback']): ?>
<br /><?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['tikifeedback']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?><div class="simplebox <?php if ($this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['num'] > 0): ?> highlight<?php endif; ?>"><?php echo $this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['mes']; ?>
</div><?php endfor; endif;  endif; ?>

<br /><br />
<div align="center">
<table class="findtable">
<tr><td class="findtable">Find</td>
<td class="findtable">
<form method="get" action="tiki-syslog.php">
<input type="text" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="submit" value="find" name="search" />
<input type="text" name="max" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['maxRecords'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size= 4" /> rows
<input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>
</td></tr></table>

<div class="simplebox">
<table class="normal">
<tr>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logid_<?php if ($this->_tpl_vars['sort_mode'] == 'logid_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">Id</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logtype_<?php if ($this->_tpl_vars['sort_mode'] == 'logtype_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">Type</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logtime_<?php if ($this->_tpl_vars['sort_mode'] == 'logtime_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">Time</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=loguser_<?php if ($this->_tpl_vars['sort_mode'] == 'loguser_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">User</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logmessage_<?php if ($this->_tpl_vars['sort_mode'] == 'logmessage_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">Message</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logip_<?php if ($this->_tpl_vars['sort_mode'] == 'logip_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">IP</a></td>
<td class="heading"><a href="tiki-syslog.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;max=<?php echo $this->_tpl_vars['maxRecords']; ?>
&amp;offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=logclient_<?php if ($this->_tpl_vars['sort_mode'] == 'logclient_desc'): ?>asc<?php else: ?>desc<?php endif; ?>" class="tableheading">Client</a></td>
</tr>
<?php echo smarty_function_cycle(array('values' => "odd,even",'print' => false), $this);?>

<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr class="<?php echo smarty_function_cycle(array(), $this);?>
">
<td><?php echo $this->_tpl_vars['list'][$this->_sections['ix']['index']]['logId']; ?>
</td>
<td><?php echo $this->_tpl_vars['list'][$this->_sections['ix']['index']]['logtype']; ?>
</td>
<td><span title="<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logtime'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logtime'])) ? $this->_run_mod_handler('tiki_short_time', true, $_tmp) : smarty_modifier_tiki_short_time($_tmp)); ?>
</span></td>
<td><?php echo $this->_tpl_vars['list'][$this->_sections['ix']['index']]['loguser']; ?>
</td>
<td title="<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logmessage'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logmessage'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 60) : smarty_modifier_truncate($_tmp, 60)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
<td><?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logip'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
<td><span title="<?php echo ((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logclient'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['list'][$this->_sections['ix']['index']]['logclient'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 24, "...") : smarty_modifier_truncate($_tmp, 24, "...")))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</span></td>
</tr>
<?php endfor; endif; ?>
</table>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-pagination.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>