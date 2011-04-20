<?php /* Smarty version 2.6.10, created on 2011-04-17 15:14:52
         compiled from gallery:modules/core/templates/AdminUsers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'gallery:modules/core/templates/AdminUsers.tpl', 109, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'User Management'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <?php if (! empty ( $this->_tpl_vars['status'] )): ?>
  <h3 class="giSuccess">
    <?php if (isset ( $this->_tpl_vars['status']['deletedUser'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Removed user '%s'",'arg1' => $this->_tpl_vars['status']['deletedUser']), $this);?>

    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['status']['createdUser'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Created user '%s'",'arg1' => $this->_tpl_vars['status']['createdUser']), $this);?>

    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['status']['modifiedUser'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Modified user '%s'",'arg1' => $this->_tpl_vars['status']['modifiedUser']), $this);?>

    <?php endif; ?>
  </h3>
  <?php endif; ?>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('one' => "There is %d user in the system.",'many' => "There are %d total users in the system.",'count' => $this->_tpl_vars['AdminUsers']['totalUserCount'],'arg1' => $this->_tpl_vars['AdminUsers']['totalUserCount']), $this);?>

  </p>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Edit User'), $this);?>
 </h3>

  <input type="text" id="giFormUsername" size="20" autocomplete="off"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[text][userName]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['text']['userName']; ?>
"/>
  <?php $this->_tag_stack[] = array('autoComplete', array('element' => 'giFormUsername'), $this); $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
    <?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SimpleCallback",'arg2' => "command=lookupUsername",'arg3' => "prefix=__VALUE__",'htmlEntities' => false), $this);?>

  <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>


  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][editFromText]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Edit'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][deleteFromText]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Delete'), $this);?>
"/>

  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['noSuchUser'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "User '%s' does not exist.",'arg1' => $this->_tpl_vars['form']['text']['userName']), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['noUserSpecified'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a username'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['cantDeleteSelf'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You cannot delete yourself!"), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['text']['cantDeleteAnonymous'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You cannot delete the special guest user."), $this);?>

  </div>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Edit User (by list)"), $this);?>
 </h3>

  <?php if (( $this->_tpl_vars['form']['list']['maxPages'] > 1 )): ?>
    <div style="margin-bottom: 10px"><span class="gcBackground1" style="padding: 5px">
      <input type="hidden"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][page]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['page']; ?>
"/>
      <input type="hidden"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][maxPages]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['maxPages']; ?>
"/>

      <?php if (( $this->_tpl_vars['form']['list']['page'] > 1 )): ?>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminUsers",'arg3' => "form[list][page]=1"), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; first"), $this);?>
</a>
	&nbsp;
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminUsers",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['backPage'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "&laquo; back"), $this);?>
</a>
      <?php endif; ?>

      &nbsp;
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Viewing page %d of %d",'arg1' => $this->_tpl_vars['form']['list']['page'],'arg2' => $this->_tpl_vars['form']['list']['maxPages']), $this);?>

      &nbsp;

      <?php if (( $this->_tpl_vars['form']['list']['page'] < $this->_tpl_vars['form']['list']['maxPages'] )): ?>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminUsers",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['nextPage'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "next &raquo;"), $this);?>
</a>
	&nbsp;
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminUsers",'arg3' => "form[list][page]=".($this->_tpl_vars['form']['list']['maxPages'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "last &raquo;"), $this);?>
</a>
      <?php endif; ?>
    </span></div>
  <?php endif; ?>

  <table class="gbDataTable">
    <tr>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Username'), $this);?>
 </th>
      <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Action'), $this);?>
 </th>
    </tr>

    <?php $_from = $this->_tpl_vars['form']['list']['userNames']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['userId'] => $this->_tpl_vars['user']):
?>
    <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
      <td>
	<?php echo $this->_tpl_vars['user']['userName']; ?>

      </td>
      <td>
	<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminEditUser",'arg3' => "userId=".($this->_tpl_vars['userId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'edit'), $this);?>
</a>

	<?php if (( $this->_tpl_vars['user']['can']['delete'] )): ?>
	  &nbsp;
	  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminDeleteUser",'arg3' => "userId=".($this->_tpl_vars['userId'])), $this);?>
"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'delete'), $this);?>
</a>
	<?php endif; ?>
      </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>

  <?php if (! empty ( $this->_tpl_vars['form']['list']['filter'] ) || $this->_tpl_vars['form']['list']['maxPages'] > 1): ?>
    <input type="text"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[list][filter]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['list']['filter']; ?>
"/>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][filterBySubstring]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Filter'), $this);?>
"/>
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][filterClear]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Clear'), $this);?>
"/>
  <?php endif; ?>

  <?php if (( ! empty ( $this->_tpl_vars['form']['list']['filter'] ) )): ?>
    <span style="white-space: nowrap">
      &nbsp;
      <?php echo $this->_reg_objects['g'][0]->text(array('one' => "%d user matches your filter",'many' => "%d users match your filter",'count' => $this->_tpl_vars['form']['list']['count'],'arg1' => $this->_tpl_vars['form']['list']['count']), $this);?>

    </span>
  <?php endif; ?>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][create]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create User'), $this);?>
"/>
</div>