<?php /* Smarty version 2.6.10, created on 2011-04-17 15:18:59
         compiled from gallery:modules/core/templates/ItemAddConfirmation.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Upload Complete'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <h3>
    <?php if (isset ( $this->_tpl_vars['ItemAddConfirmation']['count'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "Successfully added %d file.",'many' => "Successfully added %d files.",'count' => $this->_tpl_vars['ItemAddConfirmation']['count'],'arg1' => $this->_tpl_vars['ItemAddConfirmation']['count']), $this);?>

    <?php else: ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "No files added."), $this);?>

    <?php endif; ?>
  </h3>

  <?php $_from = $this->_tpl_vars['ItemAddConfirmation']['status']['addedFiles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['entry']):
?>
    <?php if ($this->_tpl_vars['entry']['exists']): ?>
    <?php ob_start(); ?>
    <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['entry']['id'])), $this);?>
">
      <?php echo $this->_tpl_vars['entry']['fileName']; ?>

    </a>
    <?php $this->_smarty_vars['capture']['itemLink'] = ob_get_contents(); ob_end_clean(); ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Added %s",'arg1' => $this->_smarty_vars['capture']['itemLink']), $this);?>

    <?php else: ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Failed to add %s",'arg1' => $this->_tpl_vars['entry']['fileName']), $this);?>

    <?php endif; ?>
    <br/>
    <?php if (! empty ( $this->_tpl_vars['entry']['warnings'] )): ?>
      <div class="giWarning">
      <?php $_from = $this->_tpl_vars['entry']['warnings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['warning']):
?>
	<?php echo $this->_tpl_vars['warning']; ?>
 <br/>
      <?php endforeach; endif; unset($_from); ?>
      </div>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>

<div class="gbBlock">
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ItemAdmin",'arg2' => "subView=core.ItemAdd",'arg3' => "itemId=".($this->_tpl_vars['ItemAdmin']['item']['id'])), $this);?>
">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add more files'), $this);?>

  </a>
</div>