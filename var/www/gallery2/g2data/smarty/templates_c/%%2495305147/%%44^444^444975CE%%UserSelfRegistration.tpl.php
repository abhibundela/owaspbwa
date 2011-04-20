<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:09
         compiled from gallery:modules/register/templates/UserSelfRegistration.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Register As New User'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Username'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <input type="text" size="32" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[userName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['userName']; ?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['userName']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a username'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['userName']['exists'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Username '%s' already exists",'arg1' => $this->_tpl_vars['form']['userName']), $this);?>

  </div>
  <?php endif; ?>

  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Full Name'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <input type="text" size="32" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[fullName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['fullName']; ?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['fullName']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter your full name'), $this);?>

  </div>
  <?php endif; ?>

  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Email Address'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <input type="text" size="32" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[email]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['email']; ?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['email']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter an email address'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['email']['invalid'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid email address'), $this);?>

  </div>
  <?php endif; ?>

  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Password'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <input type="password" size="32" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[password1]"), $this);?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['password1']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a password'), $this);?>

  </div>
  <?php endif; ?>

  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Verify Password'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <input type="password" size="32" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[password2]"), $this);?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['password2']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter the password a second time'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['password2']['mismatch'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'The passwords you entered did not match'), $this);?>

  </div>
  <?php endif; ?>
</div>

<?php $_from = $this->_tpl_vars['UserSelfRegistration']['plugins']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['plugin']):
?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['plugin']['file']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['plugin']['l10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][create]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Register'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>