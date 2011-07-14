<?php /* Smarty version 2.6.14, created on 2011-04-21 08:08:11
         compiled from tiki-adminusers.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'tiki-adminusers.tpl', 2, false),array('function', 'cycle', 'tiki-adminusers.tpl', 56, false),array('function', 'popup', 'tiki-adminusers.tpl', 246, false),array('modifier', 'escape', 'tiki-adminusers.tpl', 76, false),array('modifier', 'capitalize', 'tiki-adminusers.tpl', 88, false),array('modifier', 'duration_short', 'tiki-adminusers.tpl', 121, false),array('modifier', 'dbg', 'tiki-adminusers.tpl', 121, false),array('modifier', 'tiki_long_datetime', 'tiki-adminusers.tpl', 121, false),array('modifier', 'times', 'tiki-adminusers.tpl', 201, false),array('block', 'tr', 'tiki-adminusers.tpl', 246, false),)), $this); ?>

<?php echo smarty_function_popup_init(array('src' => "lib/overlib.js"), $this);?>


<h1><a href="tiki-adminusers.php" class="pagetitle">Admin users</a>

      <?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
Users Management" target="tikihelp" class="tikihelp" title="admin users">
<img src="img/icons/help.gif" border="0" height="16" width="16" alt='help' /></a><?php endif; ?>

<?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-adminusers.tpl" target="tikihelp" class="tikihelp" title="View template: admin users template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='edit' /></a><?php endif; ?>
</h1>

<?php if ($this->_tpl_vars['tiki_p_admin'] == 'y'): ?> 
<span class="button2"><a href="tiki-admingroups.php" class="linkbut">Admin groups</a></span>
<?php endif; ?>
<span class="button2"><a href="tiki-adminusers.php" class="linkbut">Admin users</a></span>
<?php if ($this->_tpl_vars['userinfo']['userId']): ?>
<span class="button2"><a href="tiki-adminusers.php?add=1" class="linkbut">Add a new user</a></span>
<?php endif; ?>

<?php if (! empty ( $this->_tpl_vars['feature_intertiki_mymaster'] )): ?>
  <br /><br /><b>Warning: since this tiki site is in slave mode, all user information you enter manually will be automatically overriden by other site's data, including users permissions</b>
<?php endif; ?>
  
<?php if ($this->_tpl_vars['tikifeedback']): ?>
<br /><div class="simplebox <?php if ($this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['num'] > 0): ?> highlight<?php endif; ?>"><?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['tikifeedback']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
 echo $this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['mes']; ?>
<br /><?php endfor; endif; ?></div>
<?php endif; ?>
<br />
<?php if ($this->_tpl_vars['added'] != "" || $this->_tpl_vars['discarded'] != ""): ?>
<div class="simplebox">
<h2>Batch Upload Results</h2>
Added users: <?php echo $this->_tpl_vars['added']; ?>

<?php if ($this->_tpl_vars['discarded'] != ""): ?>
- Rejected users: <?php echo $this->_tpl_vars['discarded']; ?>
<br /><br />
<table class="normal">
<tr><td class="heading">Username</td><td class="heading">Reason</td></tr>
<?php unset($this->_sections['reject']);
$this->_sections['reject']['name'] = 'reject';
$this->_sections['reject']['loop'] = is_array($_loop=$this->_tpl_vars['discardlist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['reject']['show'] = true;
$this->_sections['reject']['max'] = $this->_sections['reject']['loop'];
$this->_sections['reject']['step'] = 1;
$this->_sections['reject']['start'] = $this->_sections['reject']['step'] > 0 ? 0 : $this->_sections['reject']['loop']-1;
if ($this->_sections['reject']['show']) {
    $this->_sections['reject']['total'] = $this->_sections['reject']['loop'];
    if ($this->_sections['reject']['total'] == 0)
        $this->_sections['reject']['show'] = false;
} else
    $this->_sections['reject']['total'] = 0;
if ($this->_sections['reject']['show']):

            for ($this->_sections['reject']['index'] = $this->_sections['reject']['start'], $this->_sections['reject']['iteration'] = 1;
                 $this->_sections['reject']['iteration'] <= $this->_sections['reject']['total'];
                 $this->_sections['reject']['index'] += $this->_sections['reject']['step'], $this->_sections['reject']['iteration']++):
$this->_sections['reject']['rownum'] = $this->_sections['reject']['iteration'];
$this->_sections['reject']['index_prev'] = $this->_sections['reject']['index'] - $this->_sections['reject']['step'];
$this->_sections['reject']['index_next'] = $this->_sections['reject']['index'] + $this->_sections['reject']['step'];
$this->_sections['reject']['first']      = ($this->_sections['reject']['iteration'] == 1);
$this->_sections['reject']['last']       = ($this->_sections['reject']['iteration'] == $this->_sections['reject']['total']);
?>
<tr><td class="odd"><?php echo $this->_tpl_vars['discardlist'][$this->_sections['reject']['index']]['login']; ?>
</td><td class="odd"><?php echo $this->_tpl_vars['discardlist'][$this->_sections['reject']['index']]['reason']; ?>
</td></tr>
<?php endfor; endif; ?>
</table>
<?php endif;  if ($this->_tpl_vars['errors']): ?>
<br />
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['errors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
 echo $this->_tpl_vars['errors'][$this->_sections['ix']['index']]; ?>
<br />
<?php endfor; endif;  endif; ?>
</div>
<?php endif; ?>

<br /><br />

<?php if ($this->_tpl_vars['feature_tabs'] == 'y'):  echo smarty_function_cycle(array('name' => 'tabs','values' => "1,2,3,4",'print' => false,'advance' => false,'reset' => true), $this);?>

<div id="page-bar">
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($this->_tpl_vars['cookietab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,3);">Users</a></span>
<?php if ($this->_tpl_vars['userinfo']['userId']): ?>
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($this->_tpl_vars['cookietab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,3);">Edit user <i><?php echo $this->_tpl_vars['userinfo']['login']; ?>
</i></a></span>
<?php else: ?>
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($this->_tpl_vars['cookietab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,3);">Add a new user</a></span>
<?php endif; ?>
</span>
</div>
<?php endif; ?>

<?php echo smarty_function_cycle(array('name' => 'content','values' => "1,2,3,4",'print' => false,'advance' => false,'reset' => true), $this);?>


<div id="content<?php echo smarty_function_cycle(array('name' => 'content','assign' => 'focustab'), $this); echo $this->_tpl_vars['focustab']; ?>
" class="tabcontent"<?php if ($this->_tpl_vars['feature_tabs'] == 'y'): ?> style="display:<?php if ($this->_tpl_vars['focustab'] == $this->_tpl_vars['cookietab']): ?>block<?php else: ?>none<?php endif; ?>;"<?php endif; ?>>
<h2>Users</h2>

<form method="get" action="tiki-adminusers.php">
<table class="findtable"><tr>
<td>Find</td>
<td><input type="text" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
<td><input type="submit" value="find" name="search" /></td>
<td>Number of displayed rows</td>
<td><input type="text" size="4" name="numrows" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['numrows'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td>
</tr></table>
</form>

<?php if ($this->_tpl_vars['cant_pages'] > 1): ?>
<div align="center">
<?php unset($this->_sections['ini']);
$this->_sections['ini']['name'] = 'ini';
$this->_sections['ini']['loop'] = is_array($_loop=$this->_tpl_vars['initials']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ini']['show'] = true;
$this->_sections['ini']['max'] = $this->_sections['ini']['loop'];
$this->_sections['ini']['step'] = 1;
$this->_sections['ini']['start'] = $this->_sections['ini']['step'] > 0 ? 0 : $this->_sections['ini']['loop']-1;
if ($this->_sections['ini']['show']) {
    $this->_sections['ini']['total'] = $this->_sections['ini']['loop'];
    if ($this->_sections['ini']['total'] == 0)
        $this->_sections['ini']['show'] = false;
} else
    $this->_sections['ini']['total'] = 0;
if ($this->_sections['ini']['show']):

            for ($this->_sections['ini']['index'] = $this->_sections['ini']['start'], $this->_sections['ini']['iteration'] = 1;
                 $this->_sections['ini']['iteration'] <= $this->_sections['ini']['total'];
                 $this->_sections['ini']['index'] += $this->_sections['ini']['step'], $this->_sections['ini']['iteration']++):
$this->_sections['ini']['rownum'] = $this->_sections['ini']['iteration'];
$this->_sections['ini']['index_prev'] = $this->_sections['ini']['index'] - $this->_sections['ini']['step'];
$this->_sections['ini']['index_next'] = $this->_sections['ini']['index'] + $this->_sections['ini']['step'];
$this->_sections['ini']['first']      = ($this->_sections['ini']['iteration'] == 1);
$this->_sections['ini']['last']       = ($this->_sections['ini']['iteration'] == $this->_sections['ini']['total']);
 if ($this->_tpl_vars['initial'] && $this->_tpl_vars['initials'][$this->_sections['ini']['index']] == $this->_tpl_vars['initial']): ?>
<span class="button2"><span class="linkbuton"><?php echo ((is_array($_tmp=$this->_tpl_vars['initials'][$this->_sections['ini']['index']])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</span></span> . 
<?php else: ?>
<a href="tiki-adminusers.php?initial=<?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']];  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext"><?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']]; ?>
</a> . 
<?php endif;  endfor; endif; ?>
<a href="tiki-adminusers.php?initial=<?php if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext">All</a>
</div>
<?php endif; ?>

<form name="checkform" method="post" action="<?php echo $_SERVER['PHP_SELF'];  if ($this->_tpl_vars['group_management_mode'] != 'y' && $this->_tpl_vars['set_default_groups_mode'] != 'y'): ?>#multiple<?php endif; ?>">
<table class="normal">
<tr>
<td class="heading auto">&nbsp;</td>
<td class="heading">&nbsp;</td>
<td class="heading">&nbsp;</td>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'login_desc'): ?>login_asc<?php else: ?>login_desc<?php endif; ?>">Name</a></td>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'email_desc'): ?>email_asc<?php else: ?>email_desc<?php endif; ?>">Email</a></td>
<td class="heading"><a class="tableheading" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'currentLogin_desc'): ?>currentLogin_asc<?php else: ?>currentLogin_desc<?php endif; ?>">Last login</a></td>
<td class="heading">&nbsp;</td>
<td class="heading">Groups</td>
<td class="heading">&nbsp;</td>
</tr>
<?php echo smarty_function_cycle(array('print' => false,'values' => "even,odd"), $this);?>

<?php unset($this->_sections['user']);
$this->_sections['user']['name'] = 'user';
$this->_sections['user']['loop'] = is_array($_loop=$this->_tpl_vars['users']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['user']['show'] = true;
$this->_sections['user']['max'] = $this->_sections['user']['loop'];
$this->_sections['user']['step'] = 1;
$this->_sections['user']['start'] = $this->_sections['user']['step'] > 0 ? 0 : $this->_sections['user']['loop']-1;
if ($this->_sections['user']['show']) {
    $this->_sections['user']['total'] = $this->_sections['user']['loop'];
    if ($this->_sections['user']['total'] == 0)
        $this->_sections['user']['show'] = false;
} else
    $this->_sections['user']['total'] = 0;
if ($this->_sections['user']['show']):

            for ($this->_sections['user']['index'] = $this->_sections['user']['start'], $this->_sections['user']['iteration'] = 1;
                 $this->_sections['user']['iteration'] <= $this->_sections['user']['total'];
                 $this->_sections['user']['index'] += $this->_sections['user']['step'], $this->_sections['user']['iteration']++):
$this->_sections['user']['rownum'] = $this->_sections['user']['iteration'];
$this->_sections['user']['index_prev'] = $this->_sections['user']['index'] - $this->_sections['user']['step'];
$this->_sections['user']['index_next'] = $this->_sections['user']['index'] + $this->_sections['user']['step'];
$this->_sections['user']['first']      = ($this->_sections['user']['iteration'] == 1);
$this->_sections['user']['last']       = ($this->_sections['user']['iteration'] == $this->_sections['user']['total']);
?>
<tr class="<?php echo smarty_function_cycle(array(), $this);?>
">
<td class="thin"><input type="checkbox" name="checked[]" value="<?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
" <?php if ($this->_tpl_vars['users'][$this->_sections['user']['index']]['checked'] == 'y'): ?>checked="checked" <?php endif; ?>/></td>
<td class="thin"><a class="link" href="tiki-user_preferences.php?view_user=<?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
" title="Change user preferences: <?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
"><img border="0" alt="Change user preferences: <?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
" src="img/icons/config.gif" /></a></td>
<td class="thin"><a class="link" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;user=<?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['userId'];  if (feature_tabs != 'y'): ?>#2<?php endif; ?>"  
title="edit account settings: <?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
"><img border="0" alt="edit account settings: <?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
" src="img/icons/edit.gif" /></a></td>
<td><a class="link" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;user=<?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['userId'];  if (feature_tabs != 'y'): ?>#2<?php endif; ?>" title="edit account settings"><?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
</a></td>
<td><?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['email']; ?>
</td>
<td><?php if ($this->_tpl_vars['users'][$this->_sections['user']['index']]['currentLogin'] == ''): ?>Never <i>(<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['age'])) ? $this->_run_mod_handler('duration_short', true, $_tmp) : smarty_modifier_duration_short($_tmp)); ?>
)</i><?php else:  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['currentLogin'])) ? $this->_run_mod_handler('dbg', true, $_tmp) : smarty_modifier_dbg($_tmp)))) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp));  endif; ?></td>
<td class="thin"><a class="link" href="tiki-assignuser.php?assign_user=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['user'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" title="Assign Group"><img border="0" alt="Assign Group" src="img/icons/key.gif" /></a></td>
<td>
<?php $_from = $this->_tpl_vars['users'][$this->_sections['user']['index']]['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['grs'] => $this->_tpl_vars['what']):
 if ($this->_tpl_vars['grs'] != 'Anonymous'):  if ($this->_tpl_vars['what'] == 'included'): ?><i><?php endif; ?><a class="link" href="tiki-admingroups.php?group=<?php echo ((is_array($_tmp=$this->_tpl_vars['grs'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" title=<?php if ($this->_tpl_vars['what'] == 'included'): ?>"edit included group"<?php else: ?>"edit"<?php endif; ?>><?php echo $this->_tpl_vars['grs']; ?>
</a><?php if ($this->_tpl_vars['what'] == 'included'): ?></i><?php endif;  if ($this->_tpl_vars['what'] != 'included'): ?>(<a class="link" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;user=<?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['user']; ?>
&amp;action=removegroup&amp;group=<?php echo ((is_array($_tmp=$this->_tpl_vars['grs'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" title="remove">x</a>)<?php endif;  if ($this->_tpl_vars['grs'] == $this->_tpl_vars['users'][$this->_sections['user']['index']]['default_group']): ?> default<?php endif; ?><br />
<?php endif;  endforeach; endif; unset($_from); ?>
<td  class="thin"><?php if ($this->_tpl_vars['users'][$this->_sections['user']['index']]['user'] != 'admin'): ?><a class="link" href="tiki-adminusers.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;action=delete&amp;user=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['user'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"
title="delete"><img src="img/icons2/delete.gif" border="0" height="16" width="16" alt='delete' /></a><?php endif; ?>
</td>
</tr>
<?php endfor; endif; ?>
  <script type='text/javascript'>
  <!--
  // check / uncheck all.
  // in the future, we could extend this to happen serverside as well for the convenience of people w/o javascript.
  // for now those people just have to check every single box
  document.write("<tr><td class=\"thin\"><input name=\"switcher\" id=\"clickall\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form,'checked[]',this.checked)\"/></td>");
  document.write("<td class=\"form\" colspan=\"18\"><label for=\"clickall\">select all</label></td></tr>");
  //-->                     
  </script>
  <tr>
  <td class="form" colspan="18">
  <a name="multiple"></a><p align="left"> 
  <?php if ($this->_tpl_vars['group_management_mode'] != 'y' && $this->_tpl_vars['set_default_groups_mode'] != 'y'): ?>
  Perform action with checked:
  <select name="submit_mult">
    <option value="" selected>-</option>
    <option value="remove_users" >remove</option>
    <?php if ($this->_tpl_vars['feature_wiki_userpage'] == 'y'): ?><option value="remove_users_with_page">remove users and their userpages</option><?php endif; ?>
    <option value="assign_groups" >manage group assignments</option>
    <option value="set_default_groups">set default groups</option>
  </select>
  <input type="submit" value="ok" />
  <?php elseif ($this->_tpl_vars['group_management_mode'] == 'y'): ?>
  <select name="group_management">
  	<option value="add">Assign selected to</option>
  	<option value="remove">Remove selected from</option>
  </select>
  the following groups:<br />
  <select name="checked_groups[]" multiple="multiple" size="20">
  <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['groups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
  	<option value="<?php echo $this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName']; ?>
"><?php echo $this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName']; ?>
</option>
  <?php endfor; endif; ?>
  </select><br /><input type="submit" value="ok" /><div class="simplebox">Tip: hold down CTRL to select multiple</div>
  <?php elseif ($this->_tpl_vars['set_default_groups_mode'] == 'y'): ?>
  Set the default group of the selected users to:<br />
  <select name="checked_group" size="20">
  <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['groups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
  	<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><?php echo $this->_tpl_vars['groups'][$this->_sections['ix']['index']]['groupName']; ?>
</option>
  <?php endfor; endif; ?>
  </select><br /><input type="submit" value="ok" />
  <input type="hidden" name="set_default_groups" value="<?php echo $this->_tpl_vars['set_default_groups_mode']; ?>
" />
  <?php endif; ?>
  </p>
  </td></tr>
  </table>
  
<input type="hidden" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="numrows" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['numrows'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="offset" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['offset'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</form>

<?php if ($this->_tpl_vars['cant_pages'] > 1): ?>
<br />
<div class="mini">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-adminusers.php?<?php if ($this->_tpl_vars['find']): ?>find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php endif;  if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">prev</a>]&nbsp;
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;[<a class="prevnext" href="tiki-adminusers.php?<?php if ($this->_tpl_vars['find']): ?>find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php endif;  if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['next_offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">next</a>]
<?php endif;  if ($this->_tpl_vars['direct_pagination'] == 'y'): ?>
<br />
<?php unset($this->_sections['foo']);
$this->_sections['foo']['loop'] = is_array($_loop=$this->_tpl_vars['cant_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['foo']['name'] = 'foo';
$this->_sections['foo']['show'] = true;
$this->_sections['foo']['max'] = $this->_sections['foo']['loop'];
$this->_sections['foo']['step'] = 1;
$this->_sections['foo']['start'] = $this->_sections['foo']['step'] > 0 ? 0 : $this->_sections['foo']['loop']-1;
if ($this->_sections['foo']['show']) {
    $this->_sections['foo']['total'] = $this->_sections['foo']['loop'];
    if ($this->_sections['foo']['total'] == 0)
        $this->_sections['foo']['show'] = false;
} else
    $this->_sections['foo']['total'] = 0;
if ($this->_sections['foo']['show']):

            for ($this->_sections['foo']['index'] = $this->_sections['foo']['start'], $this->_sections['foo']['iteration'] = 1;
                 $this->_sections['foo']['iteration'] <= $this->_sections['foo']['total'];
                 $this->_sections['foo']['index'] += $this->_sections['foo']['step'], $this->_sections['foo']['iteration']++):
$this->_sections['foo']['rownum'] = $this->_sections['foo']['iteration'];
$this->_sections['foo']['index_prev'] = $this->_sections['foo']['index'] - $this->_sections['foo']['step'];
$this->_sections['foo']['index_next'] = $this->_sections['foo']['index'] + $this->_sections['foo']['step'];
$this->_sections['foo']['first']      = ($this->_sections['foo']['iteration'] == 1);
$this->_sections['foo']['last']       = ($this->_sections['foo']['iteration'] == $this->_sections['foo']['total']);
 $this->assign('selector_offset', ((is_array($_tmp=$this->_sections['foo']['index'])) ? $this->_run_mod_handler('times', true, $_tmp, $this->_tpl_vars['maxRecords']) : smarty_modifier_times($_tmp, $this->_tpl_vars['maxRecords']))); ?>

<a class="prevnext" href="tiki-adminusers.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
">
<?php echo $this->_sections['foo']['index_next']; ?>
</a>&nbsp;
<?php endfor; endif;  endif; ?>

</div>
<?php endif; ?>
</div>


<a name="2" ></a>
<div id="content<?php echo smarty_function_cycle(array('name' => 'content','assign' => 'focustab'), $this); echo $this->_tpl_vars['focustab']; ?>
" class="tabcontent"<?php if ($this->_tpl_vars['feature_tabs'] == 'y'): ?> style="display:<?php if ($this->_tpl_vars['focustab'] == $this->_tpl_vars['cookietab']): ?>block<?php else: ?>none<?php endif; ?>;"<?php endif; ?>>
<?php if ($this->_tpl_vars['userinfo']['userId']): ?>
<h2>Edit user: <?php echo $this->_tpl_vars['userinfo']['login']; ?>
</h2>
<a class="linkbut" href="tiki-assignuser.php?assign_user=<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">assign to groups: <?php echo $this->_tpl_vars['userinfo']['login']; ?>
</a>
<?php else: ?>
<h2>Add a new user</h2>
<?php endif; ?>
<form action="tiki-adminusers.php" method="post" enctype="multipart/form-data">
<table class="normal">
<tr class="formcolor"><td>User:</td><td><input type="text" name="name"  value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['login'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /><br />
<?php if ($this->_tpl_vars['userinfo']['userId']): ?>
  <?php if ($this->_tpl_vars['feature_intertiki_server'] == 'y'): ?>
    <i>Warning: changing the username will require the user to change his password and will mess with slave intertiki sites that use this one as master</i>
  <?php else: ?>
    <i>Warning: changing the username will require the user to change his password</i>
  <?php endif;  endif; ?>
</td></tr>
<tr class="formcolor"><td>Pass:</td><td><input type="password" name="pass" id="pass" /></td></tr>
<tr class="formcolor"><td>Again:</td><td><input type="password" name="pass2" id="pass2" /></td></tr>
<tr class="formcolor"><td>Email:</td><td><input type="text" name="email" size="30"  value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<?php if ($this->_tpl_vars['userinfo']['userId'] != 0): ?>
<tr class="formcolor"><td>Created:</td><td><?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['created'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp)); ?>
</td></tr>
<tr class="formcolor"><td>Registration:</td><td><?php if ($this->_tpl_vars['userinfo']['registrationDate']):  echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['registrationDate'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp));  endif; ?></td></tr>
<tr class="formcolor"><td>Last login:</td><td><?php if ($this->_tpl_vars['userinfo']['lastLogin']):  echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['lastLogin'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp));  endif; ?></td></tr>
<?php endif;  if ($this->_tpl_vars['userinfo']['userId']): ?>
<tr class="formcolor"><td>&nbsp;</td><td>
<input type="hidden" name="user" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['userinfo']['userId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="edituser" value="1" />
<input type="submit" name="submit" value="Save" />
<?php else: ?>
<tr class="formcolor"><td>Batch upload (CSV file<a <?php echo smarty_function_popup(array('text' => 'login,password,email,groups<br />user1,password1,email1,&quot;group1,group2&quot;<br />user2, password2,email2'), $this);?>
><img src="img/icons/help.gif" border="0" height="16" width="16" alt='<?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>help' /></a>)<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td><td><input type="file" name="csvlist"/><br />Overwrite: <input type="checkbox" name="overwrite" checked="checked" /></td></tr>
<tr class="formcolor"><td>&nbsp;</td><td>
<input type="hidden" name="newuser" value="1" />
<input type="submit" name="submit" value="Add" />
<?php endif; ?>
</td></tr>
</table>
</form>
<br /><br />

<?php if ($this->_tpl_vars['userTracker'] == 'y'):  if ($this->_tpl_vars['userstrackerid'] && $this->_tpl_vars['usersitemid']):  $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>User tracker item : <?php echo $this->_tpl_vars['usersitemid'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <span class="button2"><a href="tiki-view_tracker_item.php?trackerId=<?php echo $this->_tpl_vars['userstrackerid']; ?>
&amp;itemId=<?php echo $this->_tpl_vars['usersitemid']; ?>
&amp;show=mod" class="linkbut">Edit item</a></span>
<?php endif; ?>
<br /><br />
<?php endif; ?>

<table class="normal">
<tr class="formcolor"><td>
<a class="link" href="javascript:genPass('genepass','pass','pass2');">Generate a password</a></td>
<td><input id='genepass' type="text" /></td></tr>
</table>
</div>
