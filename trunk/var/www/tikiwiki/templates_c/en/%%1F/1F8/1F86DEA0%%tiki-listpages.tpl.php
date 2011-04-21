<?php /* Smarty version 2.6.14, created on 2011-04-21 08:13:54
         compiled from tiki-listpages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'tiki-listpages.tpl', 11, false),array('modifier', 'capitalize', 'tiki-listpages.tpl', 29, false),array('modifier', 'truncate', 'tiki-listpages.tpl', 99, false),array('modifier', 'tiki_short_datetime', 'tiki-listpages.tpl', 109, false),array('modifier', 'userlink', 'tiki-listpages.tpl', 112, false),array('modifier', 'kbsize', 'tiki-listpages.tpl', 151, false),array('modifier', 'times', 'tiki-listpages.tpl', 204, false),array('function', 'cycle', 'tiki-listpages.tpl', 92, false),)), $this); ?>


<h1><a href="tiki-listpages.php" class="pagetitle">Pages</a></h1>
<?php if ($this->_tpl_vars['tiki_p_admin'] == 'y'): ?>
<a href="tiki-admin.php?page=wiki"><img src='img/icons/config.gif' border='0'  alt="configure listing" title="configure listing" /></a>
<?php endif; ?>
<table class="findtable">
<tr><td class="findtitle">Find</td>
   <td class="findtitle">
   <form method="get" action="tiki-listpages.php">
     <input type="text" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
     Exact&nbsp;match<input type="checkbox" name="exact_match" <?php if ($this->_tpl_vars['exact_match'] != 'n'): ?>checked="checked"<?php endif; ?>/>
     <input type="submit" name="search" value="find" />
     <input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
   </form>
   </td>
</tr>
</table>

<div align="center">
<form name="checkform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
<input type="hidden" name="offset" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['offset'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="sort_mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['sort_mode'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="hidden" name="find" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<?php if ($this->_tpl_vars['cant_pages'] > 1 || $this->_tpl_vars['initial'] || $this->_tpl_vars['find']): ?>
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
<a href="<?php echo $_SERVER['PHP_SELF']; ?>
?initial=<?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']];  if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext"><?php echo $this->_tpl_vars['initials'][$this->_sections['ini']['index']]; ?>
</a> . 
<?php endif;  endfor; endif; ?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>
?initial=<?php if ($this->_tpl_vars['offset']): ?>&amp;offset=<?php echo $this->_tpl_vars['offset'];  endif;  if ($this->_tpl_vars['numrows']): ?>&amp;numrows=<?php echo $this->_tpl_vars['numrows'];  endif;  if ($this->_tpl_vars['sort_mode']): ?>&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  endif; ?>" 
class="prevnext">All</a>
</form>
</div>
<?php endif; ?>

    
<?php if ($this->_tpl_vars['tiki_p_remove'] == 'y'): ?>              
  <?php $this->assign('checkboxes_on', 'y');  else: ?>
  <?php $this->assign('checkboxes_on', 'n');  endif; ?>

<table class="normal">
<tr>
<?php if ($this->_tpl_vars['checkboxes_on'] == 'y'): ?>
<form name="checkboxes_on" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>
">
  <td class="heading">&nbsp;</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_name'] == 'y'): ?>
	<td class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'pageName_desc'): ?>pageName_asc<?php else: ?>pageName_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo ((is_array($_tmp=$this->_tpl_vars['find'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Page</a></td>
	<?php endif;  if ($this->_tpl_vars['wiki_list_hits'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'hits_desc'): ?>hits_asc<?php else: ?>hits_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Hits</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_lastmodif'] == 'y'): ?>
	<td class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'lastModif_desc'): ?>lastModif_asc<?php else: ?>lastModif_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Last mod</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_creator'] == 'y'): ?>
	<td class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'creator_desc'): ?>creator_asc<?php else: ?>creator_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Creator</a></td>
<?php endif; ?>

<?php if ($this->_tpl_vars['wiki_list_user'] == 'y'): ?>
	<td class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'user_desc'): ?>user_asc<?php else: ?>user_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Last author</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_lastver'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'version_desc'): ?>version_asc<?php else: ?>version_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Last ver</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_comment'] == 'y'): ?>
	<td class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'comment_desc'): ?>comment_asc<?php else: ?>comment_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Com</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_status'] == 'y'): ?>
	<td style="text-align:center;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'flag_desc'): ?>flag_asc<?php else: ?>flag_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Status</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_versions'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'versions_desc'): ?>versions_asc<?php else: ?>versions_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Vers</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_links'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'links_desc'): ?>links_asc<?php else: ?>links_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Links</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_backlinks'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'backlinks_desc'): ?>backlinks_asc<?php else: ?>backlinks_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Backlinks</a></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_size'] == 'y'): ?>
	<td style="text-align:right;" class="heading"><a class="tableheading" href="tiki-listpages.php?offset=<?php echo $this->_tpl_vars['offset']; ?>
&amp;sort_mode=<?php if ($this->_tpl_vars['sort_mode'] == 'size_desc'): ?>size_asc<?php else: ?>size_desc<?php endif;  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif;  if ($this->_tpl_vars['find']): ?>&amp;find=<?php echo $this->_tpl_vars['find'];  endif;  if ($this->_tpl_vars['exact_match'] == 'y'): ?>&amp;exact_match=on"<?php endif; ?>">Size</a></td>
<?php endif; ?>
</tr>
<?php echo smarty_function_cycle(array('values' => "even,odd",'print' => false), $this);?>

<?php unset($this->_sections['changes']);
$this->_sections['changes']['name'] = 'changes';
$this->_sections['changes']['loop'] = is_array($_loop=$this->_tpl_vars['listpages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['changes']['show'] = true;
$this->_sections['changes']['max'] = $this->_sections['changes']['loop'];
$this->_sections['changes']['step'] = 1;
$this->_sections['changes']['start'] = $this->_sections['changes']['step'] > 0 ? 0 : $this->_sections['changes']['loop']-1;
if ($this->_sections['changes']['show']) {
    $this->_sections['changes']['total'] = $this->_sections['changes']['loop'];
    if ($this->_sections['changes']['total'] == 0)
        $this->_sections['changes']['show'] = false;
} else
    $this->_sections['changes']['total'] = 0;
if ($this->_sections['changes']['show']):

            for ($this->_sections['changes']['index'] = $this->_sections['changes']['start'], $this->_sections['changes']['iteration'] = 1;
                 $this->_sections['changes']['iteration'] <= $this->_sections['changes']['total'];
                 $this->_sections['changes']['index'] += $this->_sections['changes']['step'], $this->_sections['changes']['iteration']++):
$this->_sections['changes']['rownum'] = $this->_sections['changes']['iteration'];
$this->_sections['changes']['index_prev'] = $this->_sections['changes']['index'] - $this->_sections['changes']['step'];
$this->_sections['changes']['index_next'] = $this->_sections['changes']['index'] + $this->_sections['changes']['step'];
$this->_sections['changes']['first']      = ($this->_sections['changes']['iteration'] == 1);
$this->_sections['changes']['last']       = ($this->_sections['changes']['iteration'] == $this->_sections['changes']['total']);
?>
<tr>
<?php if ($this->_tpl_vars['checkboxes_on'] == 'y'): ?>
<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><input type="checkbox" name="checked[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_name'] == 'y'): ?>
	<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="link" title="<?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20, "...", true) : smarty_modifier_truncate($_tmp, 20, "...", true)); ?>
</a>
	<?php if ($this->_tpl_vars['tiki_p_edit'] == 'y'): ?>
	<br />(<a class="link" href="tiki-editpage.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
">edit</a>)
	<?php endif; ?>
	</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_hits'] == 'y'): ?>	
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['hits']; ?>
</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_lastmodif'] == 'y'): ?>
	<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['lastModif'])) ? $this->_run_mod_handler('tiki_short_datetime', true, $_tmp) : smarty_modifier_tiki_short_datetime($_tmp)); ?>
</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_creator'] == 'y'): ?>
	<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['creator'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>
</td>
<?php endif; ?>

<?php if ($this->_tpl_vars['wiki_list_user'] == 'y'): ?>
	<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['user'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>
</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_lastver'] == 'y'): ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['version']; ?>
</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_comment'] == 'y'): ?>
	<td class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php if ($this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['comment'] == ""): ?>&nbsp;<?php else:  echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['comment'];  endif; ?></td>
<?php endif;  if ($this->_tpl_vars['wiki_list_status'] == 'y'): ?>
	<td style="text-align:center;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
">
	<?php if ($this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['flag'] == 'locked'): ?>
		<img src='img/icons/lock_topic.gif' alt='locked' />
	<?php else: ?>
		<img src='img/icons/unlock_topic.gif' alt='unlocked' />
	<?php endif; ?>
	</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_versions'] == 'y'): ?>
	<?php if ($this->_tpl_vars['feature_history'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_history'] == 'y'): ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><a class="link" href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['versions']; ?>
</a></td>
	<?php else: ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['versions']; ?>
</td>
	<?php endif;  endif;  if ($this->_tpl_vars['wiki_list_links'] == 'y'): ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['links']; ?>
</td>
<?php endif;  if ($this->_tpl_vars['wiki_list_backlinks'] == 'y'): ?>
	<?php if ($this->_tpl_vars['feature_backlinks'] == 'y'): ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><a class="link" href="tiki-backlinks.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['pageName'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['backlinks']; ?>
</a></td>
	<?php else: ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo $this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['backlinks']; ?>
</td>
	<?php endif;  endif;  if ($this->_tpl_vars['wiki_list_size'] == 'y'): ?>
	<td style="text-align:right;" class="<?php echo smarty_function_cycle(array('advance' => false), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['listpages'][$this->_sections['changes']['index']]['len'])) ? $this->_run_mod_handler('kbsize', true, $_tmp) : smarty_modifier_kbsize($_tmp)); ?>
</td>
<?php endif; ?>
       <?php echo smarty_function_cycle(array('print' => false), $this);?>

</tr>
<?php endfor; else: ?>
<tr><td colspan="16">
<b>No records found</b>
</td></tr>
<?php endif;  if ($this->_tpl_vars['checkboxes_on'] == 'y'): ?>
  <script type='text/javascript'>
  <!--
  // check / uncheck all.
  // in the future, we could extend this to happen serverside as well for the convenience of people w/o javascript.
  // for now those people just have to check every single box
  document.write("<tr><td><input name=\"switcher\" id=\"clickall\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form,'checked[]',this.checked)\"/></td>");
  document.write("<td colspan=\"15\"><label for=\"clickall\">all</label></td></tr>");
  //-->                     
  </script>
<?php endif; ?>
</table>
<?php if ($this->_tpl_vars['checkboxes_on'] == 'y'): ?> 
  <p align="left"> 
  <select name="submit_mult" onchange="this.form.submit();">
    <option value="" selected="selected">with checked:</option>
    <?php if ($this->_tpl_vars['tiki_p_remove'] == 'y'): ?> 
      <option value="remove_pages" >remove</option>
    <?php endif; ?>
    
  </select>                
  <script type='text/javascript'>
  <!--
  // Fake js to allow the use of the <noscript> tag (so non-js-users can still submit)
  //-->
  </script>
  <noscript>
    <input type="submit" value="ok" />
  </noscript>
  </p>
<?php endif; ?>
</form>
<br />
<div class="mini">
<?php if ($this->_tpl_vars['prev_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-listpages.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['prev_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif; ?>">prev</a>]
<?php endif; ?>
Page: <?php echo $this->_tpl_vars['actual_page']; ?>
/<?php echo $this->_tpl_vars['cant_pages']; ?>

<?php if ($this->_tpl_vars['next_offset'] >= 0): ?>
[<a class="prevnext" href="tiki-listpages.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['next_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif; ?>">next</a>]
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
<a class="prevnext" href="tiki-listpages.php?find=<?php echo $this->_tpl_vars['find']; ?>
&amp;offset=<?php echo $this->_tpl_vars['selector_offset']; ?>
&amp;sort_mode=<?php echo $this->_tpl_vars['sort_mode'];  if ($this->_tpl_vars['initial']): ?>&amp;initial=<?php echo $this->_tpl_vars['initial'];  endif; ?>">
<?php echo $this->_sections['foo']['index_next']; ?>
</a>
<?php endfor; endif;  endif; ?>
</div>
</div>
