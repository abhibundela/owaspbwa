<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:57
         compiled from gallery:modules/core/templates/AdminCreateUser.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/core/templates/AdminCreateUser.tpl', 63, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create A New User'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <div>
    <h4>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Username'), $this);?>

      <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
    </h4>

    <input type="text" size="32"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[userName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['userName']; ?>
"/>
    <script type="text/javascript">
      document.getElementById('siteAdminForm')['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[userName]"), $this);?>
'].focus();
    </script>

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
  </div>

  <div>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Full Name'), $this);?>
 </h4>

    <input type="text" size="32"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[fullName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['fullName']; ?>
"/>
  </div>

  <div>
    <h4>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Email Address'), $this);?>

      <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
    </h4>

    <input type="text" size="32"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[email]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['email']; ?>
"/>

    <?php if (isset ( $this->_tpl_vars['form']['error']['email']['missing'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter an email address'), $this);?>

    </div>
    <?php endif; ?>
  </div>

  <div>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Language'), $this);?>
 </h4>

    <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[language]"), $this);?>
">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCreateUser']['languageList'],'selected' => $this->_tpl_vars['form']['language']), $this);?>

    </select>
  </div>

  <div>
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
  </div>

  <div>
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
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][create]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create User'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>