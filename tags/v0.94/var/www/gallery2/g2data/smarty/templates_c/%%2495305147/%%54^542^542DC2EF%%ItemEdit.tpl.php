<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:12
         compiled from gallery:modules/core/templates/ItemEdit.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Edit %s",'arg1' => $this->_tpl_vars['ItemEdit']['itemTypeNames']['0']), $this);?>
 </h2>
</div>

<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'editPlugin'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemEdit']['editPlugin']; ?>
"/>
<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[serialNumber]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['serialNumber']; ?>
"/>

<?php if (! empty ( $this->_tpl_vars['status'] ) || ! empty ( $this->_tpl_vars['form']['error'] )): ?>
<div class="gbBlock">
  <?php if (! empty ( $this->_tpl_vars['status'] )): ?>
  <h2 class="giSuccess">
    <?php if (! empty ( $this->_tpl_vars['status']['editMessage'] )): ?>
      <?php echo $this->_tpl_vars['status']['editMessage']; ?>

    <?php endif; ?>
    <?php if (! empty ( $this->_tpl_vars['status']['warning'] )): ?>
    <div class="giWarning">
      <?php $_from = $this->_tpl_vars['status']['warning']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['warning']):
?>
	<?php echo $this->_tpl_vars['warning']; ?>

      <?php endforeach; endif; unset($_from); ?>
    </div>
    <?php endif; ?>
  </h2>
  <?php endif; ?>
  <?php if (! empty ( $this->_tpl_vars['form']['error'] )): ?>
  <h2 class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There was a problem processing your request."), $this);?>

  </h2>
  <?php endif; ?>
</div>
<?php endif; ?>

<div class="gbTabBar">
  <?php $_from = $this->_tpl_vars['ItemEdit']['plugins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['plugin']):
?>
    <?php if ($this->_tpl_vars['plugin']['isSelected']): ?>
      <span class="giSelected o"><span>
	<?php echo $this->_tpl_vars['plugin']['title']; ?>

      </span></span>
    <?php else: ?>
      <span class="o"><span>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ItemAdmin",'arg2' => "subView=core.ItemEdit",'arg3' => "itemId=".($this->_tpl_vars['ItemAdmin']['item']['id']),'arg4' => "editPlugin=".($this->_tpl_vars['plugin']['id'])), $this);?>
">
	  <?php echo $this->_tpl_vars['plugin']['title']; ?>

	</a>
      </span></span>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['ItemEdit']['pluginFile']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['ItemEdit']['pluginL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>