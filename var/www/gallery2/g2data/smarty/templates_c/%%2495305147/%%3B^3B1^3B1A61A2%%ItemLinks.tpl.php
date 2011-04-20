<?php /* Smarty version 2.6.10, created on 2011-04-17 15:13:55
         compiled from gallery:modules/core/templates/blocks/ItemLinks.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/core/templates/blocks/ItemLinks.tpl', 7, false),array('modifier', 'lower', 'gallery:modules/core/templates/blocks/ItemLinks.tpl', 20, false),)), $this); ?>
<?php $this->assign('lowercase', ((is_array($_tmp=@$this->_tpl_vars['lowercase'])) ? $this->_run_mod_handler('default', true, $_tmp, false) : smarty_modifier_default($_tmp, false)));  if (( isset ( $this->_tpl_vars['links'] ) || isset ( $this->_tpl_vars['theme']['itemLinks'] ) )): ?>
  <?php if (empty ( $this->_tpl_vars['item'] )):  $this->assign('item', $this->_tpl_vars['theme']['item']);  endif; ?>
  <?php if (! isset ( $this->_tpl_vars['links'] )):  $this->assign('links', $this->_tpl_vars['theme']['itemLinks']);  endif; ?>
  <?php if (! isset ( $this->_tpl_vars['useDropdown'] )):  $this->assign('useDropdown', true);  endif; ?>

  <?php if ($this->_tpl_vars['useDropdown'] && count ( $this->_tpl_vars['links'] ) > 1): ?>
  <div class="<?php echo $this->_tpl_vars['class']; ?>
">
    <select onchange="<?php echo 'if (this.value) { newLocation = this.value; this.options[0].selected = true; location.href= newLocation; }'; ?>
">
      <option label="<?php if ($this->_tpl_vars['item']['canContainChildren']):  echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; album actions &raquo;"), $this); else:  echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; item actions &raquo;"), $this); endif; ?>" value=""><?php if ($this->_tpl_vars['item']['canContainChildren']):  echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; album actions &raquo;"), $this); else:  echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; item actions &raquo;"), $this); endif; ?></option>
      <?php $_from = $this->_tpl_vars['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
	<?php if ($this->_tpl_vars['lowercase']):  $this->assign('linkText', ((is_array($_tmp=$this->_tpl_vars['link']['text'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)));  else: ?>
		       <?php $this->assign('linkText', $this->_tpl_vars['link']['text']);  endif; ?>
	<option label="<?php echo $this->_tpl_vars['linkText']; ?>
" value="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['link']['params']), $this);?>
"><?php echo $this->_tpl_vars['linkText']; ?>
</option>
      <?php endforeach; endif; unset($_from); ?>
    </select>
  </div>
  <?php elseif (count ( $this->_tpl_vars['links'] ) > 0): ?>
  <div class="<?php echo $this->_tpl_vars['class']; ?>
">
    <?php $_from = $this->_tpl_vars['links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['link']['params']), $this);?>
" class="gbAdminLink <?php echo $this->_reg_objects['g'][0]->linkid(array('urlParams' => $this->_tpl_vars['link']['params']), $this);?>
"><?php if ($this->_tpl_vars['lowercase']):  echo ((is_array($_tmp=$this->_tpl_vars['link']['text'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp));  else:  echo $this->_tpl_vars['link']['text'];  endif; ?></a>
    <?php endforeach; endif; unset($_from); ?>
  </div>
  <?php endif;  endif; ?>