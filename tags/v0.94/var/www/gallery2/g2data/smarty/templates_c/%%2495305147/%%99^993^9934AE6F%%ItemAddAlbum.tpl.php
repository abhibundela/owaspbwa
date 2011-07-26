<?php /* Smarty version 2.6.10, created on 2011-04-17 15:15:38
         compiled from gallery:modules/core/templates/ItemAddAlbum.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Add Sub-Album"), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <h4>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Name'), $this);?>

    <span class="giSubtitle"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "(required)"), $this);?>
 </span>
  </h4>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The name of this album on your hard disk.  It must be unique in this album.  Only use alphanumeric characters, underscores or dashes.  You will be able to rename it later."), $this);?>

  </p>

  <?php echo '';  $_from = $this->_tpl_vars['ItemAdmin']['parents']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['parent']):
 echo '';  echo $this->_tpl_vars['parent']['pathComponent'];  echo '/';  endforeach; endif; unset($_from);  echo '';  echo $this->_tpl_vars['ItemAdmin']['item']['pathComponent'];  echo '/'; ?>


  <input type="text" size="10"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[pathComponent]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['pathComponent']; ?>
"/>

  <script type="text/javascript">
    document.getElementById('itemAdminForm')['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[pathComponent]"), $this);?>
'].focus();
  </script>

  <?php if (! empty ( $this->_tpl_vars['form']['error']['pathComponent']['invalid'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your name contains invalid characters.  Please enter another."), $this);?>

  </div>
  <?php endif; ?>
  <?php if (! empty ( $this->_tpl_vars['form']['error']['pathComponent']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a name for this album."), $this);?>

  </div>
  <?php endif; ?>
  <?php if (! empty ( $this->_tpl_vars['form']['error']['pathComponent']['collision'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The name you entered is already in use.  Please enter another."), $this);?>

  </div>
  <?php endif; ?>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Title'), $this);?>
 </h4>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This is the album title."), $this);?>

  </p>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => 'title','firstMarkupBar' => true)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <input type="text" id="title" size="40"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[title]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['title']; ?>
"/>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Summary'), $this);?>
 </h4>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This is the album summary."), $this);?>

  </p>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => 'summary')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <input type="text" id="summary" size="40"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[summary]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['summary']; ?>
"/>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Keywords'), $this);?>
 </h4>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Keywords are not visible, but are searchable."), $this);?>

  </p>

  <textarea rows="2" cols="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[keywords]"), $this);?>
"><?php echo $this->_tpl_vars['form']['keywords']; ?>
</textarea>

  <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Description'), $this);?>
 </h4>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This is the long description of the album."), $this);?>

  </p>

  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/core/templates/MarkupBar.tpl", 'smarty_include_vars' => array('viewL10domain' => 'modules_core','element' => 'description')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

  <textarea id="description" rows="4" cols="60"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[description]"), $this);?>
"><?php echo $this->_tpl_vars['form']['description']; ?>
</textarea>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][create]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create'), $this);?>
"/>
</div>