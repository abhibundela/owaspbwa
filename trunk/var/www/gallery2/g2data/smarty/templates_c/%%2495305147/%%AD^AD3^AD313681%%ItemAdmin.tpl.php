<?php /* Smarty version 2.6.10, created on 2011-04-17 15:15:38
         compiled from gallery:modules/core/templates/ItemAdmin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:modules/core/templates/ItemAdmin.tpl', 25, false),)), $this); ?>
<form action="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
" method="post" enctype="<?php echo $this->_tpl_vars['ItemAdmin']['enctype']; ?>
" id="itemAdminForm">
  <div>
    <?php echo $this->_reg_objects['g'][0]->hiddenFormVars(array(), $this);?>

    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'controller'), $this);?>
" value="<?php echo $this->_tpl_vars['controller']; ?>
"/>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[formName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['formName']; ?>
"/>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'itemId'), $this);?>
" value="<?php echo $this->_tpl_vars['ItemAdmin']['item']['id']; ?>
"/>
  </div>

  <table width="100%" cellspacing="0" cellpadding="0">
    <tr valign="top">
    <td id="gsSidebarCol"><div id="gsSidebar" class="gcBorder1">
      <?php if (! $this->_tpl_vars['ItemAdmin']['isRootAlbum'] || ! empty ( $this->_tpl_vars['ItemAdmin']['thumbnail'] )): ?>
      <div class="gbBlock">
	<?php if (empty ( $this->_tpl_vars['ItemAdmin']['thumbnail'] )): ?>
	  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Thumbnail'), $this);?>

	<?php else: ?>
	  <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['ItemAdmin']['item'],'image' => $this->_tpl_vars['ItemAdmin']['thumbnail'],'maxSize' => 130), $this);?>

	<?php endif; ?>
	<h3> <?php echo ((is_array($_tmp=$this->_tpl_vars['ItemAdmin']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
 </h3>
      </div>
      <?php endif; ?>

      <div class="gbBlock">
	<h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Options'), $this);?>
 </h2>
	<ul>
	  <?php $_from = $this->_tpl_vars['ItemAdmin']['subViewChoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['choiceName'] => $this->_tpl_vars['choiceParams']):
?>
	    <li class="gbAdminLink <?php echo $this->_reg_objects['g'][0]->linkId(array('urlParams' => $this->_tpl_vars['choiceParams']), $this);?>
">
	    <?php if (isset ( $this->_tpl_vars['choiceParams']['active'] )): ?>
	      <?php echo $this->_tpl_vars['choiceName']; ?>

	    <?php else: ?>
	      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('params' => $this->_tpl_vars['choiceParams']), $this);?>
"> <?php echo $this->_tpl_vars['choiceName']; ?>
 </a>
	    <?php endif; ?>
	    </li>
	  <?php endforeach; endif; unset($_from); ?>
	</ul>
      </div>

      <?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.NavigationLinks",'class' => 'gbBlock','navigationLinks' => $this->_tpl_vars['ItemAdmin']['navigationLinks']), $this);?>

    </div></td>

    <td>
      <div id="gsContent" class="gcBorder1">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['ItemAdmin']['viewBodyFile']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['ItemAdmin']['viewL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      </div>
    </td>
  </tr></table>
</form>