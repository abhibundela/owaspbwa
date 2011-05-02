<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:39
         compiled from gallery:modules/core/templates/UserPreferences.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/core/templates/UserPreferences.tpl', 61, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Account Settings'), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Account settings saved successfully'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <div>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Username'), $this);?>
 </h4>
    <p class="giDescription">
      <?php echo $this->_tpl_vars['user']['userName']; ?>

    </p>
  </div>

  <div>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Full Name'), $this);?>
 </h4>
    <input type="text" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[fullName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['fullName']; ?>
"/>
  </div>

  <div>
    <h4>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "E-mail Address"), $this);?>

      <span class="giSubtitle">
      <?php if (! isset ( $this->_tpl_vars['UserAdmin']['isSiteAdmin'] )): ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required, password required for change)"), $this);?>

      <?php else: ?>
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "(suggested, password required for change)"), $this);?>

      <?php endif; ?>
      </span>
    </h4>

    <input type="text" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[email]"), $this);?>
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
  </div>

  <?php if ($this->_tpl_vars['UserPreferences']['translationsSupported']): ?>
  <div>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Language'), $this);?>
 </h4>

    <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[language]"), $this);?>
">
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['UserPreferences']['languageList'],'selected' => $this->_tpl_vars['form']['language']), $this);?>

    </select>
  </div>
  <?php endif; ?>

  <div>
    <h4>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Current Password'), $this);?>

      <span class="giSubtitle">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required to change the e-mail address)"), $this);?>

      </span>
    </h4>

    <input type="password" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[currentPassword]"), $this);?>
"/>

    <?php if (isset ( $this->_tpl_vars['form']['error']['currentPassword']['missing'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter your current password to change the e-mail address"), $this);?>

    </div>
    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['form']['error']['currentPassword']['incorrect'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Incorrect password'), $this);?>

    </div>
    <?php endif; ?>
  </div>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][undo]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>