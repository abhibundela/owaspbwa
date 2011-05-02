<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:48
         compiled from gallery:modules/members/templates/AdminMembers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/members/templates/AdminMembers.tpl', 23, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Members Settings'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
   <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This will select who can see the members list and member profiles."), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[canViewMembersModule]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['Members']['memberViews'],'selected' => $this->_tpl_vars['form']['canViewMembersModule']), $this);?>

  </select>
</div>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This will select if email addresses are displayed in the member profiles.  Administrators will always be able to see email addresses."), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[canViewEmailAddress]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['Members']['emailViews'],'selected' => $this->_tpl_vars['form']['canViewEmailAddress']), $this);?>

  </select>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>