<?php /* Smarty version 2.6.14, created on 2011-04-21 08:15:56
         compiled from tiki-admingroups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'tiki-admingroups.tpl', 2, false),array('function', 'cycle', 'tiki-admingroups.tpl', 23, false),array('modifier', 'escape', 'tiki-admingroups.tpl', 43, false),array('modifier', 'capitalize', 'tiki-admingroups.tpl', 55, false),array('modifier', 'times', 'tiki-admingroups.tpl', 109, false),array('modifier', 'count', 'tiki-admingroups.tpl', 132, false),array('modifier', 'truncate', 'tiki-admingroups.tpl', 137, false),array('modifier', 'userlink', 'tiki-admingroups.tpl', 221, false),array('block', 'tr', 'tiki-admingroups.tpl', 80, false),)), $this); ?>

<?php echo smarty_function_popup_init(array('src' => "lib/overlib.js"), $this);?>


<h1><a class="pagetitle" href="tiki-admingroups.php">Admin groups</a>
<?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
<a href="<?php echo $this->_tpl_vars['helpurl']; ?>
Permissions Settings" target="tikihelp" class="tikihelp" title="admin groups">
<img src="img/icons/help.gif" border="0" height="16" width="16" alt='help' /><?php endif;  if ($this->_tpl_vars['feature_help'] == 'y'): ?></a><?php endif; ?>

<?php if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?>
<a href="tiki-edit_templates.php?template=tiki-admingroups.tpl" target="tikihelp" class="tikihelp" title="View tpl: admin groups template">
<img src="img/icons/info.gif" border="0" width="16" height="16" alt='edit template' /><?php endif;  if ($this->_tpl_vars['feature_view_tpl'] == 'y'): ?></a><?php endif; ?></h1>

<span class="button2"><a href="tiki-admingroups.php" class="linkbut">Admin groups</a></span>
<span class="button2"><a href="tiki-adminusers.php" class="linkbut">Admin users</a></span>
<?php if ($this->_tpl_vars['groupname']): ?>
<span class="button2"><a href="tiki-admingroups.php?add=1<?php if ($this->_tpl_vars['feature_tabs'] != 'y'): ?>#2<?php endif; ?>" class="linkbut">Add new group</a></span>
<?php endif; ?>
<br /><br />

<?php if ($this->_tpl_vars['feature_tabs'] == 'y'):  echo smarty_function_cycle(array('name' => 'tabs','values' => "1,2,3,4",'print' => false,'advance' => false,'reset' => true), $this);?>

<div id="page-bar">
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($_COOKIE['tab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,5);">List</a></span>
<?php if ($this->_tpl_vars['groupname']): ?>
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($_COOKIE['tab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,5);">Edit group <i><?php echo $this->_tpl_vars['groupname']; ?>
</i></a></span>
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($_COOKIE['tab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,5);">Members</a></span>
<?php else: ?>
<span id="tab<?php echo smarty_function_cycle(array('name' => 'tabs','advance' => false,'assign' => 'tabi'), $this); echo $this->_tpl_vars['tabi']; ?>
" class="tabmark" style="border-color:<?php if ($_COOKIE['tab'] == $this->_tpl_vars['tabi']): ?>black<?php else: ?>white<?php endif; ?>;"><a href="javascript:tikitabs(<?php echo smarty_function_cycle(array('name' => 'tabs'), $this);?>
,5);">Add a new group</a></span>
<?php endif; ?>
</div>
<?php endif; ?>

<?php echo smarty_function_cycle(array('name' => 'content','values' => "1,2,3,4",'print' => false,'advance' => false,'reset' => true), $this);?>


<div id="content<?php echo smarty_function_cycle(array('name' => 'content','assign' => 'focustab'), $this); echo $this->_tpl_vars['focustab']; ?>
" class="tabcontent"<?php if ($this->_tpl_vars['feature_tabs'] == 'y'): ?> style="display:<?php if ($this->_tpl_vars['focustab'] == $this->_tpl_vars['cookietab']): ?>block<?php else: ?>none<?php endif; ?>;"<?php endif; ?>>
<h2>List of existing groups</h2>

<form method="get" action="tiki-admingroups.php">
<table class="findtable"><tr>
<td><label for="groups_find">Find</label></td>
<td><input type="text" name="find" id="groups_find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
<span class="button2"><span class="linkbut"><?php echo ((is_array($_tmp=$this->_tpl_vars['initials'][$this->_sections['ini']['index']])) ? $this->_run_mod_handler('capitalize', true, $_tmp) : smarty_modifier_capitalize($_tmp)); ?>
</span></span> . 
<?php else: ?>
<a href="tiki-admingroups.php?initial=<?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']];  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext"><?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']]; ?>
</a> . 
<?php endif;  endfor; endif; ?>
<a href="tiki-admingroups.php?initial=<?php if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif;  if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext">All</a>
</div>
<?php endif; ?>

<table class="normal">
<tr>
<td class="heading" style="width: 20px;">&nbsp;</td>
<td class="heading"><a class="tableheading" href="tiki-admingroups.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'groupName_desc'): ?>groupName_asc<?php else: ?>groupName_desc<?php endif; ?>">name</a></td>
<td class="heading"><a class="tableheading" href="tiki-admingroups.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'groupDesc_desc'): ?>groupDesc_asc<?php else: ?>groupDesc_desc<?php endif; ?>">desc</a></td>
<td class="heading">Includes</td>
<td class="heading">Permissions</td>
<td class="heading" style="width: 20px;">&nbsp;</td>
</tr>
<?php echo smarty_function_cycle(array('values' => "even,odd",'print' => false), $this);?>

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
<td style="width: 20px;"><a class="link" href="tiki-admingroups.php?group=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" title="edit"><img src="img/icons/edit.gif" border="0" width="20" height="16"  alt='edit' /></a></td>
<td><a class="link" href="tiki-admingroups.php?group=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  if ($this->_tpl_vars['feature_tabs'] != 'y'): ?>#2<?php endif; ?>" title="edit"><?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName']; ?>
</a></td>
<td><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['groupDesc'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></td>
<td>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['users'][$this->_sections['user']['index']]['included']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
 echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['included'][$this->_sections['ix']['index']]; ?>
<br />
<?php endfor; endif; ?>
</td>
<td>
<a class="link" href="tiki-assignpermission.php?group=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" title="permissions"><img border="0" alt="permissions" src="img/icons/key.gif" /> <?php echo $this->_tpl_vars['users'][$this->_sections['user']['index']]['permcant']; ?>
</a>
</td>
<td style="width: 20px;">
<?php if ($this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName'] != 'Anonymous'): ?><a class="link" href="tiki-admingroups.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;action=delete&amp;group=<?php echo ((is_array($_tmp=$this->_tpl_vars['users'][$this->_sections['user']['index']]['groupName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" 
title="delete"><img border="0" alt="remove" src="img/icons2/delete.gif" /></a><?php endif; ?>
</td>
</tr>
<?php endfor; endif; ?>
</table>
<?php if ($this->_tpl_vars['cant_pages'] > 1): ?>
<br />
<div class="mini" align="center">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-admingroups.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
">prev</a>]&nbsp;
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
&nbsp;[<a class="prevnext" href="tiki-admingroups.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['next_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
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
 $this->assign('selector_offset', ((is_array($_tmp=$this->_sections['foo']['index'])) ? $this->_run_mod_handler('times', true, $_tmp, $this->_tpl_vars['numrows']) : smarty_modifier_times($_tmp, $this->_tpl_vars['numrows']))); ?>
<a class="prevnext" href="tiki-admingroups.php?find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;<?php if ($this->_tpl_vars['initial']): ?>initial=<?php echo $this->_tpl_vars['initial']; ?>
&amp;<?php endif; ?>offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode']; ?>
&amp;numrows=<?php echo $this->_tpl_vars['numrows']; ?>
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
<?php if ($this->_tpl_vars['groupname']): ?>
<h2>Edit group <?php echo $this->_tpl_vars['groupname']; ?>
</h2>
<a class="linkbut" href="tiki-assignpermission.php?group=<?php echo $this->_tpl_vars['groupname']; ?>
">assign permissions</a>
<?php else: ?>
<h2>Add new group</h2>
<?php endif; ?>
<form action="tiki-admingroups.php" method="post">
<table class="normal">
<tr class="formcolor"><td><label for="groups_group">Group:</label></td><td><input type="text" name="name" id="groups_group" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['groupname'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr class="formcolor"><td><label for="groups_desc">Description:</label></td><td><textarea rows="5" cols="20" name="desc" id="groups_desc"><?php echo $this->_tpl_vars['groupdesc']; ?>
</textarea></td></tr>
<tr class="formcolor"><td><label for="groups_inc">Include:</label></td><td>
<?php if (count($this->_tpl_vars['inc']) > 20 && $this->_tpl_vars['hasOneIncludedGroup'] == 'y'):  $_from = $this->_tpl_vars['inc']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gr'] => $this->_tpl_vars['yn']):
 if ($this->_tpl_vars['yn'] == 'y'):  echo ((is_array($_tmp=$this->_tpl_vars['gr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php endif;  endforeach; endif; unset($_from); ?><br />
<?php endif; ?>
<select name="include_groups[]" id="groups_inc" multiple="multiple" size="4">
<?php $_from = $this->_tpl_vars['inc']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['gr'] => $this->_tpl_vars['yn']):
?>
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['gr'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['yn'] == 'y'): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['gr'])) ? $this->_run_mod_handler('truncate', true, $_tmp, '52', " ...") : smarty_modifier_truncate($_tmp, '52', " ...")); ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
</td></tr>
<tr class="formcolor"><td><label for="groups_home">Group Homepage:<br />
(Use wiki page name or full URL)<br />
To use a relative link, use ex.: <i>http:tiki-forums.php</i>
</label></td><td><input type="text" size="40" name="home" id="groups_home" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['grouphome'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<?php if ($this->_tpl_vars['groupTracker'] == 'y'): ?>
<tr class="formcolor"><td><label for="groupTracker">Group Information Tracker</label></td><td>
<select name="groupstracker">
<option value="0">choose a group tracker ...</option>
<?php $_from = $this->_tpl_vars['trackers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['tit']):
?>
<option value="<?php echo $this->_tpl_vars['tid']; ?>
"<?php if ($this->_tpl_vars['tid'] == $this->_tpl_vars['grouptrackerid']): ?> <?php $this->assign('ggr', ($this->_tpl_vars['tit'])); ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['tit']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php if ($this->_tpl_vars['grouptrackerid']): ?>
<br />
<select name="groupfield">
<option value="0">choose a field ...</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['groupFields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo $this->_tpl_vars['groupFields'][$this->_sections['ix']['index']]['fieldId']; ?>
"<?php if ($this->_tpl_vars['groupFields'][$this->_sections['ix']['index']]['fieldId'] == $this->_tpl_vars['groupfieldid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['groupFields'][$this->_sections['ix']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
<?php endif; ?>
<span class="button2"><a href="<?php if ($this->_tpl_vars['grouptrackerid']): ?>tiki-admin_tracker_fields.php?trackerId=<?php echo $this->_tpl_vars['grouptrackerid'];  else: ?>tiki-admin_trackers.php<?php endif; ?>" class="linkbut">admin <?php echo $this->_tpl_vars['ggr']; ?>
</a>
</td></tr>
<?php endif;  if ($this->_tpl_vars['userTracker'] == 'y'): ?>
<tr class="formcolor"><td><label for="userstracker">Users Information Tracker</label></td><td>
<select name="userstracker">
<option value="0">choose a users tracker ...</option>
<?php $_from = $this->_tpl_vars['trackers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tid'] => $this->_tpl_vars['tit']):
?>
<option value="<?php echo $this->_tpl_vars['tid']; ?>
"<?php if ($this->_tpl_vars['tid'] == $this->_tpl_vars['userstrackerid']): ?> <?php $this->assign('ugr', ($this->_tpl_vars['tit'])); ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['tit']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<?php if ($this->_tpl_vars['userstrackerid']): ?>
<br />
<select name="usersfield">
<option value="0">choose a field ...</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['usersFields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo $this->_tpl_vars['usersFields'][$this->_sections['ix']['index']]['fieldId']; ?>
"<?php if ($this->_tpl_vars['usersFields'][$this->_sections['ix']['index']]['fieldId'] == $this->_tpl_vars['usersfieldid']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['usersFields'][$this->_sections['ix']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
<?php endif; ?>
<span class="button2"><a href="<?php if ($this->_tpl_vars['grouptrackerid']): ?>tiki-admin_tracker_fields.php?trackerId=<?php echo $this->_tpl_vars['userstrackerid'];  else: ?>tiki-admin_trackers.php<?php endif; ?>" class="linkbut">admin <?php echo $this->_tpl_vars['ugr']; ?>
</a>
</td></tr>
<?php endif;  if ($this->_tpl_vars['group'] != ''): ?>
<tr class="formcolor"><td>&nbsp;
<input type="hidden" name="olgroup" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['group'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
</td><td><input type="submit" name="save" value="Save" /></td></tr>
<?php else: ?>
<tr class="formcolor"><td >&nbsp;</td><td><input type="submit" name="newgroup" value="Add" /></td></tr>
<?php endif; ?>
</table>
</form>
<br /><br />

<?php if ($this->_tpl_vars['groupTracker'] == 'y'):  if ($this->_tpl_vars['grouptrackerid'] && $this->_tpl_vars['groupitemid']):  $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Group tracker item : <?php echo $this->_tpl_vars['groupitemid'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <span class="button2"><a href="tiki-view_tracker_item.php?trackerId=<?php echo $this->_tpl_vars['grouptrackerid']; ?>
&amp;itemId=<?php echo $this->_tpl_vars['groupitemid']; ?>
&amp;show=mod" class="linkbut">Edit item</a></span>
<?php elseif ($this->_tpl_vars['grouptrackerid']):  if ($this->_tpl_vars['groupfieldid']): ?>
Group tracker item not found <span class="button2"><a href="tiki-view_tracker.php?trackerId=<?php echo $this->_tpl_vars['grouptrackerid']; ?>
" class="linkbut">Create item</a></span>
<?php else: ?>
choose a field ...
<?php endif;  else: ?>
choose a group tracker ...
<?php endif; ?>
<br /><br />
<?php endif; ?>
</div>


<a name="3" ></a>
<?php if ($this->_tpl_vars['groupname']): ?>
<div id="content<?php echo smarty_function_cycle(array('name' => 'content','assign' => 'focustab'), $this); echo $this->_tpl_vars['focustab']; ?>
" class="tabcontent"<?php if ($this->_tpl_vars['feature_tabs'] == 'y'): ?> style="display:<?php if ($this->_tpl_vars['focustab'] == $this->_tpl_vars['cookietab']): ?>block<?php else: ?>none<?php endif; ?>;"<?php endif; ?>>
<h2>Members List: <?php echo $this->_tpl_vars['groupname']; ?>
</h2>
<table class="normal"><tr>
<?php if ($this->_tpl_vars['memberslist']):  echo smarty_function_cycle(array('name' => 'table','values' => ',,,,</tr><tr>','print' => false,'advance' => false), $this);?>

<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['memberslist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<td class="formcolor auto"><a href="tiki-adminusers.php?user=<?php echo ((is_array($_tmp=$this->_tpl_vars['memberslist'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&action=removegroup&group=<?php echo $this->_tpl_vars['groupname'];  if ($this->_tpl_vars['feature_tabs'] != 'y'): ?>#2<?php endif; ?>" class="link" title="remove from group"><img src="img/icons2/delete.gif" border="0" width="16" height="16"  alt='remove'></a> <a href="tiki-adminusers.php?user=<?php echo ((is_array($_tmp=$this->_tpl_vars['memberslist'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  if ($this->_tpl_vars['feature_tabs'] != 'y'): ?>#2<?php endif; ?>" class="link" title="edit"><img src="img/icons/edit.gif" border="0" width="20" height="16"  alt='edit'></a> <?php echo ((is_array($_tmp=$this->_tpl_vars['memberslist'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>
</td><?php echo smarty_function_cycle(array('name' => 'table'), $this);?>

<?php endfor; endif; ?>
</tr></table>
<div class="box"><?php echo $this->_sections['ix']['total']; ?>
 users in group <?php echo $this->_tpl_vars['groupname']; ?>
</div>
</div>
<?php else: ?>
<td class="formcolor auto"><a href="tiki-admingroups.php?group=<?php echo $this->_tpl_vars['groupname']; ?>
&show=1<?php if ($this->_tpl_vars['feature_tabs'] != 'y'): ?>#3<?php endif; ?>" class="linkbut">List all members</a></td>
</tr></table>
</div>
<?php endif;  endif; ?>