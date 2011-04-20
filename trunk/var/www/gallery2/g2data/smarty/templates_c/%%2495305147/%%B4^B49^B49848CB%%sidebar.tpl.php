<?php /* Smarty version 2.6.10, created on 2011-04-17 15:13:55
         compiled from gallery:themes/matrix/templates/sidebar.tpl */ ?>
<div id="gsSidebar" class="gcBorder1">
    <?php $_from = $this->_tpl_vars['theme']['params']['sidebarBlocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
    <?php echo $this->_reg_objects['g'][0]->block(array('type' => $this->_tpl_vars['block']['0'],'params' => $this->_tpl_vars['block']['1'],'class' => 'gbBlock'), $this);?>

  <?php endforeach; endif; unset($_from); ?>
  <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.NavigationLinks",'class' => 'gbBlock'), $this);?>

</div>