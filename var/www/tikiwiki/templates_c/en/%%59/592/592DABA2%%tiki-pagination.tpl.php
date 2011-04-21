<?php /* Smarty version 2.6.14, created on 2011-04-21 08:16:06
         compiled from tiki-pagination.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-pagination.tpl', 6, false),array('modifier', 'times', 'tiki-pagination.tpl', 18, false),)), $this); ?>
<?php if ($this->_tpl_vars['cant_pages'] > 1): ?>
<br />
<div align="center" class="mini">

<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
<a class="prevnext" href="<?php echo $_SERVER['PHP_SELF']; ?>
?offset=<?php echo $this->_tpl_vars['prev_offset'];  $_from = $this->_tpl_vars['urlquery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arg'] => $this->_tpl_vars['val']):
 if ($this->_tpl_vars['val']): ?>&amp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arg'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
=<?php echo ((is_array($_tmp=$this->_tpl_vars['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  endforeach; endif; unset($_from); ?>">[prev]</a>&nbsp;
<?php endif; ?>

Page: <?php echo $this->_tpl_vars['actual_page']; ?>
 / <?php echo $this->_tpl_vars['cant_pages']; ?>


<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;<a class="prevnext" href="<?php echo $_SERVER['PHP_SELF']; ?>
?offset=<?php echo $this->_tpl_vars['next_offset'];  $_from = $this->_tpl_vars['urlquery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arg'] => $this->_tpl_vars['val']):
 if ($this->_tpl_vars['val']): ?>&amp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arg'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
=<?php echo ((is_array($_tmp=$this->_tpl_vars['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  endforeach; endif; unset($_from); ?>">[next]</a>
<?php endif; ?>

<?php if ($this->_tpl_vars['direct_pagination'] == 'y'): ?>
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
<a class="prevnext" href="<?php echo $_SERVER['PHP_SELF']; ?>
?offset=<?php echo $this->_tpl_vars['selector_offset'];  $_from = $this->_tpl_vars['urlquery']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['arg'] => $this->_tpl_vars['val']):
 if ($this->_tpl_vars['val']): ?>&amp;<?php echo ((is_array($_tmp=$this->_tpl_vars['arg'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
=<?php echo ((is_array($_tmp=$this->_tpl_vars['val'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  endforeach; endif; unset($_from); ?>"><?php echo $this->_sections['foo']['index_next']; ?>
</a>&nbsp;
<?php endfor; endif;  endif; ?>

</div>
<?php endif; ?>
