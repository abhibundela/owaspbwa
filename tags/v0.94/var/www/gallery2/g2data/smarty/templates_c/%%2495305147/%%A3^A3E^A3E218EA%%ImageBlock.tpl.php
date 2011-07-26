<?php /* Smarty version 2.6.10, created on 2011-04-17 15:13:55
         compiled from gallery:modules/imageblock/templates/blocks/ImageBlock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/imageblock/templates/blocks/ImageBlock.tpl', 7, false),)), $this); ?>
<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "imageblock.LoadImageBlock",'blocks' => ((is_array($_tmp=@$this->_tpl_vars['blocks'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'maxSize' => ((is_array($_tmp=@$this->_tpl_vars['maxSize'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'itemId' => ((is_array($_tmp=@$this->_tpl_vars['itemId'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'linkTarget' => ((is_array($_tmp=@$this->_tpl_vars['linkTarget'])) ? $this->_run_mod_handler('default', true, $_tmp, null) : smarty_modifier_default($_tmp, null)),'useDefaults' => ((is_array($_tmp=@$this->_tpl_vars['useDefaults'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true)),'showHeading' => ((is_array($_tmp=@$this->_tpl_vars['showHeading'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true)),'showTitle' => ((is_array($_tmp=@$this->_tpl_vars['showTitle'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true)),'showDate' => ((is_array($_tmp=@$this->_tpl_vars['showDate'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true)),'showViews' => ((is_array($_tmp=@$this->_tpl_vars['showViews'])) ? $this->_run_mod_handler('default', true, $_tmp, false) : smarty_modifier_default($_tmp, false)),'showOwner' => ((is_array($_tmp=@$this->_tpl_vars['showOwner'])) ? $this->_run_mod_handler('default', true, $_tmp, false) : smarty_modifier_default($_tmp, false))), $this);?>


<?php if (! empty ( $this->_tpl_vars['ImageBlockData'] )): ?>
<div class="<?php echo $this->_tpl_vars['class']; ?>
">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/imageblock/templates/ImageBlock.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php endif; ?>