<?php /* Smarty version 2.6.14, created on 2011-04-21 08:07:43
         compiled from tiki-change_password.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-change_password.tpl', 6, false),)), $this); ?>
<h1>Change password enforced</h1>
<form method="post" action="tiki-change_password.php" >
<table class="normal">
<tr>
  <td class="formcolor">User:</td>
  <td class="formcolor"><input type="text" name="user" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['user'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>  
<tr>
  <td class="formcolor">Old password:</td>
  <td class="formcolor"><input type="password" name="oldpass" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['oldpass'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr>     
<tr>
  <td class="formcolor">New password:</td>
  <td class="formcolor"><input type="password" name="pass" /></td>
</tr>  
<tr>
  <td class="formcolor">Again please:</td>
  <td class="formcolor"><input type="password" name="pass2" /></td>
</tr>  
<tr>
  <td class="formcolor">&nbsp;</td>
  <td class="formcolor"><input type="submit" name="change" value="change" /></td>
</tr>  
</table>
</form>