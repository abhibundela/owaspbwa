<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:24
         compiled from gallery:modules/register/templates/AdminEmail.tpl */ ?>
<?php echo $this->_reg_objects['g'][0]->text(array('text' => "New user registration:"), $this);?>


<?php echo $this->_reg_objects['g'][0]->text(array('text' => "    Username: %s",'arg1' => $this->_tpl_vars['username']), $this);?>

<?php echo $this->_reg_objects['g'][0]->text(array('text' => "   Full name: %s",'arg1' => $this->_tpl_vars['name']), $this);?>

<?php echo $this->_reg_objects['g'][0]->text(array('text' => "       Email: %s",'arg1' => $this->_tpl_vars['email']), $this);?>


<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Activate or delete this user here'), $this);?>

<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=register.AdminSelfRegistration",'forceFullUrl' => true,'htmlEntities' => false), $this);?>
