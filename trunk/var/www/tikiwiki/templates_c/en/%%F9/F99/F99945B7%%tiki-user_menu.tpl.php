<?php /* Smarty version 2.6.14, created on 2011-04-22 02:21:45
         compiled from tiki-user_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'tiki-user_menu.tpl', 8, false),array('modifier', 'escape', 'tiki-user_menu.tpl', 18, false),array('block', 'tr', 'tiki-user_menu.tpl', 18, false),)), $this); ?>

<?php $this->assign('opensec', 'n');  $this->assign('sep', ''); ?>

<?php if ($this->_tpl_vars['menu_info']['type'] == 'e' || $this->_tpl_vars['menu_info']['type'] == 'd'): ?>

<?php $_from = $this->_tpl_vars['channels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pos'] => $this->_tpl_vars['chdata']):
 $this->assign('cname', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['menu_info']['menuId'])) ? $this->_run_mod_handler('cat', true, $_tmp, '__') : smarty_modifier_cat($_tmp, '__')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['chdata']['position']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['chdata']['position'])));  if ($this->_tpl_vars['chdata']['type'] == 's'):  if ($this->_tpl_vars['opensec'] == 'y'): ?></div><?php endif; ?>
<div class="separator<?php echo $this->_tpl_vars['sep']; ?>
">
<?php if ($this->_tpl_vars['sep'] == 'line'):  $this->assign('sep', '');  endif; ?>

<?php if ($this->_tpl_vars['chdata']['url']):  if ($this->_tpl_vars['feature_menusfolderstyle'] == 'y'): ?>
<a class='separator' href="javascript:icntoggle('menu<?php echo $this->_tpl_vars['cname']; ?>
');" title="Toggle options"><img src="img/icons/<?php if ($this->_tpl_vars['menu_info']['type'] != 'd'): ?>o<?php endif; ?>fo.gif" border="0" name="menu<?php echo $this->_tpl_vars['cname']; ?>
icn" alt='Toggle'/></a>&nbsp;
<?php else: ?><a class='separator' href="javascript:toggle('menu<?php echo $this->_tpl_vars['cname']; ?>
');">[-]</a><?php endif; ?> 
<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['chdata']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="separator"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['chdata']['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
<?php if ($this->_tpl_vars['feature_menusfolderstyle'] != 'y'): ?><a class='separator' href="javascript:toggle('menu<?php echo $this->_tpl_vars['cname']; ?>
');">[+]</a><?php endif; ?> 
<?php else:  if ($this->_tpl_vars['feature_menusfolderstyle'] == 'y'): ?>
<a class='separator' href="javascript:icntoggle('menu<?php echo $this->_tpl_vars['cname']; ?>
');" title="Toggle options"><img src="img/icons/<?php if ($this->_tpl_vars['menu_info']['type'] != 'd'): ?>o<?php endif; ?>fo.gif" border="0" name="menu<?php echo $this->_tpl_vars['cname']; ?>
icn" alt='Toggle'/>&nbsp;
<?php else: ?><a class='separator' href="javascript:toggle('menu<?php echo $this->_tpl_vars['cname']; ?>
');">[-]<?php endif;  $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['chdata']['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack);  if ($this->_tpl_vars['feature_menusfolderstyle'] != 'y'): ?>[+]<?php endif; ?></a> 
<?php endif; ?>
</div>
<?php $this->assign('opensec', 'y'); ?>
<div <?php if ($this->_tpl_vars['menu_info']['type'] == 'd' && $_COOKIE['menu'] != ''): ?>style="display:none;"<?php else: ?>style="display:block;"<?php endif; ?> id='menu<?php echo $this->_tpl_vars['cname']; ?>
'>
<?php elseif ($this->_tpl_vars['chdata']['type'] == 'o'): ?>
<div class="option<?php echo $this->_tpl_vars['sep']; ?>
">&nbsp;<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['chdata']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="linkmenu"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['chdata']['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></div>
<?php if ($this->_tpl_vars['sep'] == 'line'):  $this->assign('sep', '');  endif;  else:  if ($this->_tpl_vars['chdata']['type'] == '-'):  if ($this->_tpl_vars['opensec'] == 'y'): ?></div><?php endif;  $this->assign('opensec', 'n');  endif;  $this->assign('sep', 'line');  endif;  endforeach; endif; unset($_from);  if ($this->_tpl_vars['opensec'] == 'y'): ?></div><?php endif; ?>

<?php else:  $_from = $this->_tpl_vars['channels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pos'] => $this->_tpl_vars['chdata']):
 if ($this->_tpl_vars['chdata']['type'] == 's'): ?>
<div class="separator<?php echo $this->_tpl_vars['sep']; ?>
"><a class='separator' href="<?php echo ((is_array($_tmp=$this->_tpl_vars['chdata']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['chdata']['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></div>
<?php if ($this->_tpl_vars['sep'] == 'line'):  $this->assign('sep', '');  endif;  elseif ($this->_tpl_vars['chdata']['type'] == 'o'): ?>
<div class="option<?php echo $this->_tpl_vars['sep']; ?>
">&nbsp;<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['chdata']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="linkmenu"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['chdata']['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></div>
<?php if ($this->_tpl_vars['sep'] == 'line'):  $this->assign('sep', '');  endif;  else:  $this->assign('sep', 'line');  endif;  endforeach; endif; unset($_from);  endif; ?>

<?php if ($this->_tpl_vars['sep'] == 'line'): ?>
<div class="separator<?php echo $this->_tpl_vars['sep']; ?>
">&nbsp;</div>
<?php endif; ?>

<?php if ($this->_tpl_vars['menu_info']['type'] == 'e' || $this->_tpl_vars['menu_info']['type'] == 'd'): ?>
<script type='text/javascript'>
<?php $_from = $this->_tpl_vars['channels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pos'] => $this->_tpl_vars['chdata']):
 if ($this->_tpl_vars['chdata']['type'] == 's'): ?>
  <?php if ($this->_tpl_vars['feature_menusfolderstyle'] == 'y'): ?>
    setfolderstate('menu<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['menu_info']['menuId'])) ? $this->_run_mod_handler('cat', true, $_tmp, '__') : smarty_modifier_cat($_tmp, '__')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['chdata']['position']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['chdata']['position'])); ?>
', '<?php echo $this->_tpl_vars['menu_info']['type']; ?>
');
  <?php else: ?>
    setsectionstate('menu<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['menu_info']['menuId'])) ? $this->_run_mod_handler('cat', true, $_tmp, '__') : smarty_modifier_cat($_tmp, '__')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['chdata']['position']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['chdata']['position'])); ?>
', '<?php echo $this->_tpl_vars['menu_info']['type']; ?>
');
  <?php endif;  endif;  endforeach; endif; unset($_from); ?>
</script>
<?php endif; ?>
