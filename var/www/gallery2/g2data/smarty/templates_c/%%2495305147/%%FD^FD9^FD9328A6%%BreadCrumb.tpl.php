<?php /* Smarty version 2.6.10, created on 2012-07-13 15:00:25
         compiled from gallery:modules/core/templates/blocks/BreadCrumb.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'gallery:modules/core/templates/blocks/BreadCrumb.tpl', 18, false),array('modifier', 'default', 'gallery:modules/core/templates/blocks/BreadCrumb.tpl', 19, false),array('modifier', 'markup', 'gallery:modules/core/templates/blocks/BreadCrumb.tpl', 19, false),)), $this); ?>
<div class="<?php echo $this->_tpl_vars['class']; ?>
">
  <?php unset($this->_sections['parent']);
$this->_sections['parent']['name'] = 'parent';
$this->_sections['parent']['loop'] = is_array($_loop=$this->_tpl_vars['theme']['parents']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['parent']['show'] = true;
$this->_sections['parent']['max'] = $this->_sections['parent']['loop'];
$this->_sections['parent']['step'] = 1;
$this->_sections['parent']['start'] = $this->_sections['parent']['step'] > 0 ? 0 : $this->_sections['parent']['loop']-1;
if ($this->_sections['parent']['show']) {
    $this->_sections['parent']['total'] = $this->_sections['parent']['loop'];
    if ($this->_sections['parent']['total'] == 0)
        $this->_sections['parent']['show'] = false;
} else
    $this->_sections['parent']['total'] = 0;
if ($this->_sections['parent']['show']):

            for ($this->_sections['parent']['index'] = $this->_sections['parent']['start'], $this->_sections['parent']['iteration'] = 1;
                 $this->_sections['parent']['iteration'] <= $this->_sections['parent']['total'];
                 $this->_sections['parent']['index'] += $this->_sections['parent']['step'], $this->_sections['parent']['iteration']++):
$this->_sections['parent']['rownum'] = $this->_sections['parent']['iteration'];
$this->_sections['parent']['index_prev'] = $this->_sections['parent']['index'] - $this->_sections['parent']['step'];
$this->_sections['parent']['index_next'] = $this->_sections['parent']['index'] + $this->_sections['parent']['step'];
$this->_sections['parent']['first']      = ($this->_sections['parent']['iteration'] == 1);
$this->_sections['parent']['last']       = ($this->_sections['parent']['iteration'] == $this->_sections['parent']['total']);
?>
  <?php if (! $this->_sections['parent']['last']): ?>
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']),'arg3' => "highlightId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index_next']]['id'])), $this);?>
"
     class="BreadCrumb-<?php echo smarty_function_counter(array('name' => 'BreadCrumb'), $this);?>
">
    <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
</a>
  <?php else: ?>
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['id']),'arg3' => "highlightId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
"
     class="BreadCrumb-<?php echo smarty_function_counter(array('name' => 'BreadCrumb'), $this);?>
">
    <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['parents'][$this->_sections['parent']['index']]['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
</a>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['separator'] )): ?> <?php echo $this->_tpl_vars['separator']; ?>
 <?php endif; ?>
  <?php endfor; endif; ?>

  <?php if (( $this->_tpl_vars['theme']['pageType'] == 'admin' || $this->_tpl_vars['theme']['pageType'] == 'module' )): ?>
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id'])), $this);?>
" class="BreadCrumb-<?php echo smarty_function_counter(array('name' => 'BreadCrumb'), $this);?>
">
     <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item']['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
</a>
  <?php else: ?>
  <span class="BreadCrumb-<?php echo smarty_function_counter(array('name' => 'BreadCrumb'), $this);?>
">
     <?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['theme']['item']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['theme']['item']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['theme']['item']['pathComponent'])))) ? $this->_run_mod_handler('markup', true, $_tmp, 'strip') : smarty_modifier_markup($_tmp, 'strip')); ?>
</span>
  <?php endif; ?>
</div>