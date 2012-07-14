<?php /* Smarty version 2.6.10, created on 2012-07-13 15:00:37
         compiled from gallery:modules/core/templates/UserAdmin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/core/templates/UserAdmin.tpl', 8, false),)), $this); ?>
<form action="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
" method="post" id="userAdminForm"
      enctype="<?php echo ((is_array($_tmp=@$this->_tpl_vars['UserAdmin']['enctype'])) ? $this->_run_mod_handler('default', true, $_tmp, "application/x-www-form-urlencoded") : smarty_modifier_default($_tmp, "application/x-www-form-urlencoded")); ?>
">
  <div>
    <?php echo $this->_reg_objects['g'][0]->hiddenFormVars(array(), $this);?>

    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'controller'), $this);?>
" value="<?php echo $this->_tpl_vars['controller']; ?>
"/>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[formName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['formName']; ?>
"/>
  </div>

  <table width="100%" cellspacing="0" cellpadding="0">
    <tr valign="top">
      <td id="gsSidebarCol"><div id="gsSidebar" class="gcBorder1">
	<div class="gbBlock">
	  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'User Options'), $this);?>
 </h2>
	  <ul>
	    <?php $_from = $this->_tpl_vars['UserAdmin']['subViewChoices']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['choice']):
?>
	      <li class="gbAdminLink <?php echo $this->_reg_objects['g'][0]->linkId(array('urlParams' => $this->_tpl_vars['choice']), $this);?>
">
	      <?php if (( $this->_tpl_vars['UserAdmin']['subViewName'] == $this->_tpl_vars['choice']['view'] )): ?>
		<?php echo $this->_tpl_vars['choice']['name']; ?>

	      <?php else: ?>
		<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.UserAdmin",'arg2' => "subView=".($this->_tpl_vars['choice']['view'])), $this);?>
">
		  <?php echo $this->_tpl_vars['choice']['name']; ?>

		</a>
	      <?php endif; ?>
	      </li>
	    <?php endforeach; endif; unset($_from); ?>
	  </ul>
	</div>

	<?php echo $this->_reg_objects['g'][0]->block(array('type' => "core.NavigationLinks",'class' => 'gbBlock','navigationLinks' => $this->_tpl_vars['UserAdmin']['navigationLinks']), $this);?>

      </div></td>

      <td>
	<div id="gsContent" class="gcBorder1">
	  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['UserAdmin']['viewBodyFile']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['UserAdmin']['viewL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</div>
      </td>
    </tr>
  </table>
</form>