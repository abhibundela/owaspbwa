<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:16
         compiled from gallery:modules/core/templates/ItemAdd.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add Items'), $this);?>
 </h2>
</div>

<?php if (( ! $this->_tpl_vars['ItemAdd']['hasToolkit'] )): ?>
<div class="gbBlock giWarning">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You don't have any Graphics Toolkit activated that can handle JPEG images.  If you add images, you will probably not have any thumbnails."), $this);?>

  <?php ob_start(); ?>
    <?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminModules"), $this);?>

  <?php $this->_smarty_vars['capture']['url'] = ob_get_contents(); ob_end_clean(); ?>
  <?php if ($this->_tpl_vars['ItemAdd']['isAdmin']): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Visit the <a href=\"%s\">Modules</a> page to activate a Graphics Toolkit.",'arg1' => $this->_smarty_vars['capture']['url']), $this);?>

  <?php endif; ?>
</div>
<?php endif; ?>

<div class="gbTabBar">
  <?php $_from = $this->_tpl_vars['ItemAdd']['plugins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['plugin']):
?>
    <?php if ($this->_tpl_vars['plugin']['isSelected']): ?>
      <span class="giSelected o"><span>
	<?php echo $this->_tpl_vars['plugin']['title']; ?>

      </span></span>
    <?php else: ?>
      <span class="o"><span>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ItemAdmin",'arg2' => "subView=core.ItemAdd",'arg3' => "itemId=".($this->_tpl_vars['ItemAdmin']['item']['id']),'arg4' => "addPlugin=".($this->_tpl_vars['plugin']['id'])), $this);?>
"><?php echo $this->_tpl_vars['plugin']['title']; ?>
</a>
      </span></span>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>

<input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'addPlugin'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemAdd']['addPlugin']; ?>
"/>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['ItemAdd']['pluginFile']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['ItemAdd']['pluginL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>