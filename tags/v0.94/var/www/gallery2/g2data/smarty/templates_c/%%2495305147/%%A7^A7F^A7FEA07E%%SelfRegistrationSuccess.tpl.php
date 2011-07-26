<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:24
         compiled from gallery:modules/register/templates/SelfRegistrationSuccess.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Registration successful'), $this);?>
 </h2>
</div>

<div class="gbBlock">
<?php if ($this->_tpl_vars['SelfRegistrationSuccess']['pending']): ?>
  <h2 class="giTitle">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your registration was successful."), $this);?>

  </h2>
  <p class="giDescription">
    <?php if ($this->_tpl_vars['SelfRegistrationSuccess']['sentConfirmationEmail']): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You will shortly receive an email containing a link. You have to click this link to confirm and activate your account.  This procedure is necessary to prevent account abuse."), $this);?>

    <?php else: ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your registration will be processed and your account activated soon."), $this);?>

    <?php endif; ?>
  </p>
<?php else: ?>
  <h2 class="giTitle">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your registration was successful and your account has been activated."), $this);?>

  </h2>
  <p class="giDescription">
      <?php ob_start(); ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.UserAdmin",'arg2' => "subView=core.UserLogin",'forceFullUrl' => true), $this);?>
">
      <?php $this->_smarty_vars['capture']['loginLink'] = ob_get_contents(); ob_end_clean(); ?>
      <?php ob_start(); ?>
      </a>
      <?php $this->_smarty_vars['capture']['loginLinkEnd'] = ob_get_contents(); ob_end_clean(); ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You can now %slogin%s to your account with your username and password.",'arg1' => $this->_smarty_vars['capture']['loginLink'],'arg2' => $this->_smarty_vars['capture']['loginLinkEnd']), $this);?>

  </p>
<?php endif; ?>
</div>