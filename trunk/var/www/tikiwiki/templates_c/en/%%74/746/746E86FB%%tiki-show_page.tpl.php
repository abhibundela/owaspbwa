<?php /* Smarty version 2.6.14, created on 2011-04-21 08:07:37
         compiled from tiki-show_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'breadcrumbs', 'tiki-show_page.tpl', 3, false),array('function', 'html_image', 'tiki-show_page.tpl', 43, false),array('modifier', 'lower', 'tiki-show_page.tpl', 22, false),array('modifier', 'escape', 'tiki-show_page.tpl', 23, false),array('modifier', 'userlink', 'tiki-show_page.tpl', 182, false),array('modifier', 'tiki_long_datetime', 'tiki-show_page.tpl', 192, false),)), $this); ?>


<?php echo smarty_function_breadcrumbs(array('type' => 'trail','loc' => 'page','crumbs' => $this->_tpl_vars['crumbs']), $this);?>

<?php if ($this->_tpl_vars['feature_page_title'] == 'y'):  echo smarty_function_breadcrumbs(array('type' => 'pagetitle','loc' => 'page','crumbs' => $this->_tpl_vars['crumbs']), $this);?>

<?php endif; ?>

<div class="wikitopline">
<table><tr>
<td style="vertical-align:top;">
<?php if ($this->_tpl_vars['feature_wiki_pageid'] == 'y'): ?>
	<small><a class="link" href="tiki-index.php?page_id=<?php echo $this->_tpl_vars['page_id']; ?>
">page id: <?php echo $this->_tpl_vars['page_id']; ?>
</a></small>
<?php endif;  echo smarty_function_breadcrumbs(array('type' => 'desc','loc' => 'page','crumbs' => $this->_tpl_vars['crumbs']), $this);?>

<?php if ($this->_tpl_vars['cached_page'] == 'y'): ?><small>(cached)</small><?php endif; ?>
</td>
<?php if ($this->_tpl_vars['is_categorized'] == 'y' && $this->_tpl_vars['feature_categories'] == 'y' && $this->_tpl_vars['feature_categorypath'] == 'y'): ?>
	<td style="vertical-align:top;width:100px;"><?php echo $this->_tpl_vars['display_catpath']; ?>
</td>
<?php endif;  if ($this->_tpl_vars['print_page'] != 'y'): ?>
	<td style="text-align:right;width:142px;wrap:nowrap">
	<?php if ($this->_tpl_vars['editable'] && ( $this->_tpl_vars['tiki_p_edit'] == 'y' || ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'sandbox' ) && $this->_tpl_vars['beingEdited'] != 'y'): ?>
		<a title="edit" href="tiki-editpage.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><img src="img/icons/edit.gif" border="0"  width="20" height="16" alt="edit" /></a>
	<?php endif; ?>       
	<?php if ($this->_tpl_vars['wiki_feature_3d'] == 'y'): ?>
		<a title="3D browser" href="javascript:wiki3d_open('<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
',<?php echo $this->_tpl_vars['wiki_3d_width']; ?>
, <?php echo $this->_tpl_vars['wiki_3d_height']; ?>
)"><img src="img/icons/ico_wiki3d.gif" border="0" width="13" height="16" alt="3D browser" /></a>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['cached_page'] == 'y'): ?>
		<a title="refresh" href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;refresh=1"><img src="img/icons/ico_redo.gif" border="0" height="16" width="16"  alt="refresh" /></a>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['feature_wiki_print'] == 'y'): ?>
	<a title="print" href="tiki-print.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><img src="img/icons/ico_print.gif" border="0"  width="16" height="16" alt="print" /></a>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['feature_wiki_pdf'] == 'y'): ?>
		<a title="create PDF" href="tiki-config_pdf.php?<?php if ($this->_tpl_vars['home_info'] && $this->_tpl_vars['home_info']['page_ref_id']): ?>page_ref_id=<?php echo $this->_tpl_vars['home_info']['page_ref_id'];  else: ?>page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>"><img src="img/icons/ico_pdf.gif" border="0"  width="16" height="16" alt="PDF" /></a>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['user'] && $this->_tpl_vars['feature_notepad'] == 'y' && $this->_tpl_vars['tiki_p_notepad'] == 'y'): ?>
		<a title="Save to notepad" href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;savenotepad=1"><img src="img/icons/ico_save.gif" border="0"  width="16" height="16" alt="save" /></a>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['user'] && $this->_tpl_vars['feature_user_watches'] == 'y'): ?>
		<?php if ($this->_tpl_vars['user_watching_page'] == 'n'): ?>
			<a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;watch_event=wiki_page_changed&amp;watch_object=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;watch_action=add"><?php echo smarty_function_html_image(array('file' => 'img/icons/icon_watch.png','border' => '0','alt' => 'monitor this page','title' => 'monitor this page'), $this);?>
</a>
		<?php else: ?>
			<a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;watch_event=wiki_page_changed&amp;watch_object=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;watch_action=remove"><?php echo smarty_function_html_image(array('file' => 'img/icons/icon_unwatch.png','border' => '0','alt' => 'stop monitoring this page','title' => 'stop monitoring this page'), $this);?>
</a>
		<?php endif; ?>
	<?php endif; ?>
	</td>

	<?php if ($this->_tpl_vars['feature_backlinks'] == 'y' && $this->_tpl_vars['backlinks']): ?>
		<td style="text-align:right;width:42px;">
		<form action="tiki-index.php" method="get">
		<select name="page" onchange="page.form.submit()">
		<option>backlinks...</option>
		<?php unset($this->_sections['back']);
$this->_sections['back']['name'] = 'back';
$this->_sections['back']['loop'] = is_array($_loop=$this->_tpl_vars['backlinks']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['back']['show'] = true;
$this->_sections['back']['max'] = $this->_sections['back']['loop'];
$this->_sections['back']['step'] = 1;
$this->_sections['back']['start'] = $this->_sections['back']['step'] > 0 ? 0 : $this->_sections['back']['loop']-1;
if ($this->_sections['back']['show']) {
    $this->_sections['back']['total'] = $this->_sections['back']['loop'];
    if ($this->_sections['back']['total'] == 0)
        $this->_sections['back']['show'] = false;
} else
    $this->_sections['back']['total'] = 0;
if ($this->_sections['back']['show']):

            for ($this->_sections['back']['index'] = $this->_sections['back']['start'], $this->_sections['back']['iteration'] = 1;
                 $this->_sections['back']['iteration'] <= $this->_sections['back']['total'];
                 $this->_sections['back']['index'] += $this->_sections['back']['step'], $this->_sections['back']['iteration']++):
$this->_sections['back']['rownum'] = $this->_sections['back']['iteration'];
$this->_sections['back']['index_prev'] = $this->_sections['back']['index'] - $this->_sections['back']['step'];
$this->_sections['back']['index_next'] = $this->_sections['back']['index'] + $this->_sections['back']['step'];
$this->_sections['back']['first']      = ($this->_sections['back']['iteration'] == 1);
$this->_sections['back']['last']       = ($this->_sections['back']['iteration'] == $this->_sections['back']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['backlinks'][$this->_sections['back']['index']]['fromPage']; ?>
"><?php echo $this->_tpl_vars['backlinks'][$this->_sections['back']['index']]['fromPage']; ?>
</option>
		<?php endfor; endif; ?>
		</select>
		</form>
		</td>
	<?php endif; ?>

	<?php if (! $this->_tpl_vars['page_ref_id'] && count ( $this->_tpl_vars['showstructs'] ) != 0): ?>
		<td style="text-align:right;width:42px;">
		<form action="tiki-index.php" method="post">
		<select name="page_ref_id" onchange="page_ref_id.form.submit()">
		<option>Structures...</option>
		<?php unset($this->_sections['struct']);
$this->_sections['struct']['name'] = 'struct';
$this->_sections['struct']['loop'] = is_array($_loop=$this->_tpl_vars['showstructs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['struct']['show'] = true;
$this->_sections['struct']['max'] = $this->_sections['struct']['loop'];
$this->_sections['struct']['step'] = 1;
$this->_sections['struct']['start'] = $this->_sections['struct']['step'] > 0 ? 0 : $this->_sections['struct']['loop']-1;
if ($this->_sections['struct']['show']) {
    $this->_sections['struct']['total'] = $this->_sections['struct']['loop'];
    if ($this->_sections['struct']['total'] == 0)
        $this->_sections['struct']['show'] = false;
} else
    $this->_sections['struct']['total'] = 0;
if ($this->_sections['struct']['show']):

            for ($this->_sections['struct']['index'] = $this->_sections['struct']['start'], $this->_sections['struct']['iteration'] = 1;
                 $this->_sections['struct']['iteration'] <= $this->_sections['struct']['total'];
                 $this->_sections['struct']['index'] += $this->_sections['struct']['step'], $this->_sections['struct']['iteration']++):
$this->_sections['struct']['rownum'] = $this->_sections['struct']['iteration'];
$this->_sections['struct']['index_prev'] = $this->_sections['struct']['index'] - $this->_sections['struct']['step'];
$this->_sections['struct']['index_next'] = $this->_sections['struct']['index'] + $this->_sections['struct']['step'];
$this->_sections['struct']['first']      = ($this->_sections['struct']['iteration'] == 1);
$this->_sections['struct']['last']       = ($this->_sections['struct']['iteration'] == $this->_sections['struct']['total']);
?>
		<option value="<?php echo $this->_tpl_vars['showstructs'][$this->_sections['struct']['index']]['req_page_ref_id']; ?>
">
		<?php if ($this->_tpl_vars['showstructs'][$this->_sections['struct']['index']]['page_alias']): ?> 
			<?php echo $this->_tpl_vars['showstructs'][$this->_sections['struct']['index']]['page_alias']; ?>

		<?php else: ?>
			<?php echo $this->_tpl_vars['showstructs'][$this->_sections['struct']['index']]['pageName']; ?>

		<?php endif; ?>
		</option>
		<?php endfor; endif; ?>
		</select>
		</form>
		</td>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['feature_multilingual'] == 'y'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "translated-lang.tpl", 'smarty_include_vars' => array('td' => 'y')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>

<?php endif; ?>
</tr></table>
</div>

<div class="wikitext">
<?php if ($this->_tpl_vars['structure'] == 'y'): ?>
<div class="tocnav">
<table>
<tr>
  <td>
    <?php if ($this->_tpl_vars['prev_info'] && $this->_tpl_vars['prev_info']['page_ref_id']): ?>
		<a href="tiki-index.php?page_ref_id=<?php echo $this->_tpl_vars['prev_info']['page_ref_id']; ?>
"><img src="img/icons2/nav_dot_right.gif" border="0" height="11" width="8" alt="Previous page" 
   			<?php if ($this->_tpl_vars['prev_info']['page_alias']): ?>
   				title='<?php echo $this->_tpl_vars['prev_info']['page_alias']; ?>
'
   			<?php else: ?>
   				title='<?php echo $this->_tpl_vars['prev_info']['pageName']; ?>
'
   			<?php endif; ?>/></a><?php else: ?><img src="img/icons2/8.gif" alt="" border="0" height="1" width="8" /><?php endif; ?>
	<?php if ($this->_tpl_vars['parent_info']): ?>
   	<a href="tiki-index.php?page_ref_id=<?php echo $this->_tpl_vars['parent_info']['page_ref_id']; ?>
"><img src="img/icons2/nav_home.gif" border="0" height="11" width="13" alt="Parent page" 
        <?php if ($this->_tpl_vars['parent_info']['page_alias']): ?>
   	      title='<?php echo $this->_tpl_vars['parent_info']['page_alias']; ?>
'
        <?php else: ?>
   	      title='<?php echo $this->_tpl_vars['parent_info']['pageName']; ?>
'
        <?php endif; ?>/></a><?php else: ?><img src="img/icons2/8.gif" alt="" border="0" height="1" width="8" /><?php endif; ?>
   	<?php if ($this->_tpl_vars['next_info'] && $this->_tpl_vars['next_info']['page_ref_id']): ?>
      <a href="tiki-index.php?page_ref_id=<?php echo $this->_tpl_vars['next_info']['page_ref_id']; ?>
"><img src="img/icons2/nav_dot_left.gif" height="11" width="8" border="0" alt="Next page" 
		  <?php if ($this->_tpl_vars['next_info']['page_alias']): ?>
			  title='<?php echo $this->_tpl_vars['next_info']['page_alias']; ?>
'
		  <?php else: ?>
			  title='<?php echo $this->_tpl_vars['next_info']['pageName']; ?>
'
		  <?php endif; ?>/></a><?php else: ?><img src="img/icons2/8.gif" alt="" border="0" height="1" width="8" />
	<?php endif; ?>
	<?php if ($this->_tpl_vars['home_info']): ?>
   	<a href="tiki-index.php?page_ref_id=<?php echo $this->_tpl_vars['home_info']['page_ref_id']; ?>
"><img src="img/icons2/home.gif" border="0" height="16" width="16" alt="TOC" 
		  <?php if ($this->_tpl_vars['home_info']['page_alias']): ?>
			  title='<?php echo $this->_tpl_vars['home_info']['page_alias']; ?>
'
		  <?php else: ?>
			  title='<?php echo $this->_tpl_vars['home_info']['pageName']; ?>
'
		  <?php endif; ?>/></a><?php endif; ?>
  </td>
  <td>
<?php if ($this->_tpl_vars['tiki_p_edit_structures'] && $this->_tpl_vars['tiki_p_edit_structures'] == 'y'): ?>
    <form action="tiki-editpage.php" method="post">
      <input type="hidden" name="current_page_id" value="<?php echo $this->_tpl_vars['page_info']['page_ref_id']; ?>
" />
      <input type="text" name="page" />
      
      <?php if ($this->_tpl_vars['page_info'] && ! $this->_tpl_vars['parent_info']): ?>
      <input type="hidden" name="add_child" value="checked" /> 
      <?php else: ?>
      <input type="checkbox" name="add_child" /> Child
      <?php endif; ?>      
      <input type="submit" name="insert_into_struct" value="Add Page" />
    </form>
<?php endif; ?>
  </td>
</tr>
<tr>
  <td colspan="2">
    <?php unset($this->_sections['ix']);
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['structure_path']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['name'] = 'ix';
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
      <?php if ($this->_tpl_vars['structure_path'][$this->_sections['ix']['index']]['parent_id']): ?>&nbsp;<?php echo $this->_tpl_vars['site_crumb_seper']; ?>
&nbsp;<?php endif; ?>
	  <a href="tiki-index.php?page_ref_id=<?php echo $this->_tpl_vars['structure_path'][$this->_sections['ix']['index']]['page_ref_id']; ?>
">
      <?php if ($this->_tpl_vars['structure_path'][$this->_sections['ix']['index']]['page_alias']): ?>
        <?php echo $this->_tpl_vars['structure_path'][$this->_sections['ix']['index']]['page_alias']; ?>

	  <?php else: ?>
        <?php echo $this->_tpl_vars['structure_path'][$this->_sections['ix']['index']]['pageName']; ?>

	  <?php endif; ?>
	  </a>
	<?php endfor; endif; ?>
  </td>
</tr>
</table>
</div>
<?php endif;  if ($this->_tpl_vars['feature_wiki_ratings'] == 'y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "poll.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  echo $this->_tpl_vars['parsed']; ?>

<?php if ($this->_tpl_vars['pages'] > 1): ?>
	<br />
	<div align="center">
		<a href="tiki-index.php?<?php if ($this->_tpl_vars['page_info']): ?>page_ref_id=<?php echo $this->_tpl_vars['page_info']['page_ref_id'];  else: ?>page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>&amp;pagenum=<?php echo $this->_tpl_vars['first_page']; ?>
"><img src="img/icons2/nav_first.gif" border="0" height="11" width="27" alt="First page" title="First page" /></a>

		<a href="tiki-index.php?<?php if ($this->_tpl_vars['page_info']): ?>page_ref_id=<?php echo $this->_tpl_vars['page_info']['page_ref_id'];  else: ?>page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>&amp;pagenum=<?php echo $this->_tpl_vars['prev_page']; ?>
"><img src="img/icons2/nav_dot_right.gif" border="0" height="11" width="8" alt="Previous page" title="Previous page" /></a>

		<small>page:<?php echo $this->_tpl_vars['pagenum']; ?>
/<?php echo $this->_tpl_vars['pages']; ?>
</small>

		<a href="tiki-index.php?<?php if ($this->_tpl_vars['page_info']): ?>page_ref_id=<?php echo $this->_tpl_vars['page_info']['page_ref_id'];  else: ?>page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>&amp;pagenum=<?php echo $this->_tpl_vars['next_page']; ?>
"><img src="img/icons2/nav_dot_left.gif" border="0" height="11" width="8" alt="Next page" title="Next page" /></a>


		<a href="tiki-index.php?<?php if ($this->_tpl_vars['page_info']): ?>page_ref_id=<?php echo $this->_tpl_vars['page_info']['page_ref_id'];  else: ?>page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  endif; ?>&amp;pagenum=<?php echo $this->_tpl_vars['last_page']; ?>
"><img src="img/icons2/nav_last.gif" border="0" height="11" width="27" alt="Last page" title="Last page" /></a>
	</div>
<?php endif; ?>
</div> 

<?php if ($this->_tpl_vars['has_footnote'] == 'y'): ?><div class="wikitext wikifootnote"><?php echo $this->_tpl_vars['footnote']; ?>
</div><?php endif; ?>

<?php if (isset ( $this->_tpl_vars['wiki_authors_style'] ) && $this->_tpl_vars['wiki_authors_style'] == 'business'): ?>
<p class="editdate">
  Last edited by <?php echo ((is_array($_tmp=$this->_tpl_vars['lastUser'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

  <?php unset($this->_sections['author']);
$this->_sections['author']['name'] = 'author';
$this->_sections['author']['loop'] = is_array($_loop=$this->_tpl_vars['contributors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['author']['show'] = true;
$this->_sections['author']['max'] = $this->_sections['author']['loop'];
$this->_sections['author']['step'] = 1;
$this->_sections['author']['start'] = $this->_sections['author']['step'] > 0 ? 0 : $this->_sections['author']['loop']-1;
if ($this->_sections['author']['show']) {
    $this->_sections['author']['total'] = $this->_sections['author']['loop'];
    if ($this->_sections['author']['total'] == 0)
        $this->_sections['author']['show'] = false;
} else
    $this->_sections['author']['total'] = 0;
if ($this->_sections['author']['show']):

            for ($this->_sections['author']['index'] = $this->_sections['author']['start'], $this->_sections['author']['iteration'] = 1;
                 $this->_sections['author']['iteration'] <= $this->_sections['author']['total'];
                 $this->_sections['author']['index'] += $this->_sections['author']['step'], $this->_sections['author']['iteration']++):
$this->_sections['author']['rownum'] = $this->_sections['author']['iteration'];
$this->_sections['author']['index_prev'] = $this->_sections['author']['index'] - $this->_sections['author']['step'];
$this->_sections['author']['index_next'] = $this->_sections['author']['index'] + $this->_sections['author']['step'];
$this->_sections['author']['first']      = ($this->_sections['author']['iteration'] == 1);
$this->_sections['author']['last']       = ($this->_sections['author']['iteration'] == $this->_sections['author']['total']);
?>
   <?php if ($this->_sections['author']['first']): ?>, based on work by
   <?php else: ?>
    <?php if (! $this->_sections['author']['last']): ?>,
    <?php else: ?> and
    <?php endif; ?>
   <?php endif; ?>
   <?php echo ((is_array($_tmp=$this->_tpl_vars['contributors'][$this->_sections['author']['index']])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

  <?php endfor; endif; ?>.<br />                                         
  Page last modified on <?php echo ((is_array($_tmp=$this->_tpl_vars['lastModif'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp)); ?>
.
</p>
<?php elseif (isset ( $this->_tpl_vars['wiki_authors_style'] ) && $this->_tpl_vars['wiki_authors_style'] == 'collaborative'): ?>
<p class="editdate">
  Contributors to this page: <?php echo ((is_array($_tmp=$this->_tpl_vars['lastUser'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

  <?php unset($this->_sections['author']);
$this->_sections['author']['name'] = 'author';
$this->_sections['author']['loop'] = is_array($_loop=$this->_tpl_vars['contributors']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['author']['show'] = true;
$this->_sections['author']['max'] = $this->_sections['author']['loop'];
$this->_sections['author']['step'] = 1;
$this->_sections['author']['start'] = $this->_sections['author']['step'] > 0 ? 0 : $this->_sections['author']['loop']-1;
if ($this->_sections['author']['show']) {
    $this->_sections['author']['total'] = $this->_sections['author']['loop'];
    if ($this->_sections['author']['total'] == 0)
        $this->_sections['author']['show'] = false;
} else
    $this->_sections['author']['total'] = 0;
if ($this->_sections['author']['show']):

            for ($this->_sections['author']['index'] = $this->_sections['author']['start'], $this->_sections['author']['iteration'] = 1;
                 $this->_sections['author']['iteration'] <= $this->_sections['author']['total'];
                 $this->_sections['author']['index'] += $this->_sections['author']['step'], $this->_sections['author']['iteration']++):
$this->_sections['author']['rownum'] = $this->_sections['author']['iteration'];
$this->_sections['author']['index_prev'] = $this->_sections['author']['index'] - $this->_sections['author']['step'];
$this->_sections['author']['index_next'] = $this->_sections['author']['index'] + $this->_sections['author']['step'];
$this->_sections['author']['first']      = ($this->_sections['author']['iteration'] == 1);
$this->_sections['author']['last']       = ($this->_sections['author']['iteration'] == $this->_sections['author']['total']);
?>
   <?php if (! $this->_sections['author']['last']): ?>,
   <?php else: ?> and
   <?php endif; ?>
   <?php echo ((is_array($_tmp=$this->_tpl_vars['contributors'][$this->_sections['author']['index']])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

  <?php endfor; endif; ?>.<br />
  Page last modified on <?php echo ((is_array($_tmp=$this->_tpl_vars['lastModif'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp)); ?>
 by <?php echo ((is_array($_tmp=$this->_tpl_vars['lastUser'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>
.
</p>
<?php elseif (isset ( $this->_tpl_vars['wiki_authors_style'] ) && $this->_tpl_vars['wiki_authors_style'] == 'none'):  else: ?>
<p class="editdate">
  Created by: <?php echo ((is_array($_tmp=$this->_tpl_vars['creator'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

  last modification: <?php echo ((is_array($_tmp=$this->_tpl_vars['lastModif'])) ? $this->_run_mod_handler('tiki_long_datetime', true, $_tmp) : smarty_modifier_tiki_long_datetime($_tmp)); ?>
 by <?php echo ((is_array($_tmp=$this->_tpl_vars['lastUser'])) ? $this->_run_mod_handler('userlink', true, $_tmp) : smarty_modifier_userlink($_tmp)); ?>

</p>
<?php endif; ?>

<?php if ($this->_tpl_vars['wiki_feature_copyrights'] == 'y' && $this->_tpl_vars['wikiLicensePage']): ?>
  <?php if ($this->_tpl_vars['wikiLicensePage'] == $this->_tpl_vars['page']): ?>
    <?php if ($this->_tpl_vars['tiki_p_edit_copyrights'] == 'y'): ?>
      <p class="editdate">To edit the copyright notices <a href="copyrights.php?page=<?php echo $this->_tpl_vars['copyrightpage']; ?>
">click here</a>.</p>
    <?php endif; ?>
  <?php else: ?>
    <p class="editdate">The content on this page is licensed under the terms of the <a href="tiki-index.php?page=<?php echo $this->_tpl_vars['wikiLicensePage']; ?>
&amp;copyrightpage=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
"><?php echo $this->_tpl_vars['wikiLicensePage']; ?>
</a>.</p>
  <?php endif;  endif; ?>

<?php if ($this->_tpl_vars['print_page'] == 'y'): ?>
  <div class="editdate" align="center"><p>
    The original document is available at <?php echo $this->_tpl_vars['urlprefix']; ?>
tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>

  </p></div>
<?php endif; ?>

<?php if ($this->_tpl_vars['is_categorized'] == 'y' && $this->_tpl_vars['feature_categories'] == 'y' && $this->_tpl_vars['feature_categoryobjects'] == 'y'): ?>
<div class="catblock"><?php echo $this->_tpl_vars['display_catobjects']; ?>
</div>
<?php endif; ?>