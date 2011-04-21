<?php /* Smarty version 2.6.14, created on 2011-04-21 08:13:32
         compiled from tiki-editpage.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'tiki-editpage.tpl', 3, false),array('modifier', 'escape', 'tiki-editpage.tpl', 14, false),array('modifier', 'lower', 'tiki-editpage.tpl', 15, false),array('modifier', 'default', 'tiki-editpage.tpl', 107, false),array('block', 'tr', 'tiki-editpage.tpl', 70, false),)), $this); ?>


<?php echo smarty_function_popup_init(array('src' => "lib/overlib.js"), $this);?>



<?php if ($this->_tpl_vars['editpageconflict'] == 'y'): ?>
<script type='text/javascript'>
<!-- //Hide Script
	alert("This page is being edited by <?php echo $this->_tpl_vars['semUser']; ?>
. Proceed at your own peril.")
//End Hide Script -->
</script>
<?php endif; ?>

<h1>Edit: <?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp));  if ($this->_tpl_vars['pageAlias'] != ''): ?>&nbsp;(<?php echo ((is_array($_tmp=$this->_tpl_vars['pageAlias'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
)<?php endif; ?></h1>
<?php if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'sandbox'): ?>
<div class="wikitext">
The SandBox is a page where you can practice your editing skills, use the preview feature to preview the appearance of the page, no versions are stored for this page.
</div>
<?php endif;  if ($this->_tpl_vars['preview']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-preview.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
<form  enctype="multipart/form-data" method="post" action="tiki-editpage.php" id='editpageform'>
<?php if ($this->_tpl_vars['preview']): ?>
<input type="submit" class="wikiaction" name="preview" value="preview" />
<?php if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox'):  if ($this->_tpl_vars['tiki_p_minor'] == 'y'): ?>
<input type="checkbox" name="isminor" value="on" />Minor
<?php endif; ?>
<input type="submit" class="wikiaction" name="save" value="save" /> &nbsp;&nbsp; <input type="submit" class="wikiaction" name="cancel_edit" value="cancel edit" />
<?php endif;  endif;  if ($this->_tpl_vars['page_ref_id']): ?>
<input type="hidden" name="page_ref_id" value="<?php echo $this->_tpl_vars['page_ref_id']; ?>
" />
<?php endif;  if ($this->_tpl_vars['current_page_id']): ?>
<input type="hidden" name="current_page_id" value="<?php echo $this->_tpl_vars['current_page_id']; ?>
" />
<?php endif;  if ($this->_tpl_vars['add_child']): ?>
<input type="hidden" name="add_child" value="true" />
<?php endif;  if ($this->_tpl_vars['can_wysiwyg']):  if (! $this->_tpl_vars['wysiwyg']): ?>
<span class="button2"><a class="linkbut" href="?page=<?php echo $this->_tpl_vars['page']; ?>
&&wysiwyg=y">Use wysiwyg editor</a></span>
<?php else: ?>
<span class="button2"><a class="linkbut" href="?page=<?php echo $this->_tpl_vars['page']; ?>
&&wysiwyg=n">Use normal editor</a></span>
<?php endif;  endif; ?>

<table class="normal">

<?php if ($this->_tpl_vars['categIds']):  unset($this->_sections['o']);
$this->_sections['o']['name'] = 'o';
$this->_sections['o']['loop'] = is_array($_loop=$this->_tpl_vars['categIds']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['o']['show'] = true;
$this->_sections['o']['max'] = $this->_sections['o']['loop'];
$this->_sections['o']['step'] = 1;
$this->_sections['o']['start'] = $this->_sections['o']['step'] > 0 ? 0 : $this->_sections['o']['loop']-1;
if ($this->_sections['o']['show']) {
    $this->_sections['o']['total'] = $this->_sections['o']['loop'];
    if ($this->_sections['o']['total'] == 0)
        $this->_sections['o']['show'] = false;
} else
    $this->_sections['o']['total'] = 0;
if ($this->_sections['o']['show']):

            for ($this->_sections['o']['index'] = $this->_sections['o']['start'], $this->_sections['o']['iteration'] = 1;
                 $this->_sections['o']['iteration'] <= $this->_sections['o']['total'];
                 $this->_sections['o']['index'] += $this->_sections['o']['step'], $this->_sections['o']['iteration']++):
$this->_sections['o']['rownum'] = $this->_sections['o']['iteration'];
$this->_sections['o']['index_prev'] = $this->_sections['o']['index'] - $this->_sections['o']['step'];
$this->_sections['o']['index_next'] = $this->_sections['o']['index'] + $this->_sections['o']['step'];
$this->_sections['o']['first']      = ($this->_sections['o']['iteration'] == 1);
$this->_sections['o']['last']       = ($this->_sections['o']['iteration'] == $this->_sections['o']['total']);
?>
<input type="hidden" name="cat_categories[]" value="<?php echo $this->_tpl_vars['categIds'][$this->_sections['o']['index']]; ?>
" />
<?php endfor; endif; ?>
<input type="hidden" name="categId" value="<?php echo $this->_tpl_vars['categIdstr']; ?>
" />
<input type="hidden" name="cat_categorize" value="on" />
<?php else:  if ($this->_tpl_vars['tiki_p_view_categories'] == 'y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "categorize.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "structures.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feature_wiki_templates'] == 'y' && $this->_tpl_vars['tiki_p_use_content_templates'] == 'y' && ! $this->_tpl_vars['templateId']): ?>
<tr class="formcolor"><td>Apply template:</td><td>
<select name="templateId" onchange="javascript:document.getElementById('editpageform').submit();">
<option value="0">none</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['templates']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['templates'][$this->_sections['ix']['index']]['templateId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['templateId'] == $this->_tpl_vars['templates'][$this->_sections['ix']['index']]['templateId']): ?>selected="selected"<?php endif; ?>><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['templates'][$this->_sections['ix']['index']]['name'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></option>
<?php endfor; endif; ?>
</select>
</td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['feature_wiki_ratings'] == 'y' && $this->_tpl_vars['tiki_p_wiki_admin_ratings'] == 'y'): ?>
<tr class="formcolor"><td>Use rating:</td><td>
<?php if ($this->_tpl_vars['poll_rated']['info']): ?>
<a href="tiki-admin_poll_options.php?pollId=<?php echo $this->_tpl_vars['poll_rated']['info']['pollId']; ?>
"><?php echo $this->_tpl_vars['poll_rated']['info']['title']; ?>
</a>
<span class="button2"><a class="linkbut" href="tiki-editpage.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;removepoll=<?php echo $this->_tpl_vars['poll_rated']['info']['pollId']; ?>
">disable</a>
<input type="hidden" name="poll_template" value="<?php echo $this->_tpl_vars['poll_rated']['info']['pollId']; ?>
" />
<?php if ($this->_tpl_vars['tiki_p_admin_poll'] == 'y'): ?>
<span class="button2"><a class="linkbut" href="tiki-admin_polls.php">admin polls</a></span>
<?php endif;  else:  if (count ( $this->_tpl_vars['polls_templates'] )): ?>
type
<select name="poll_template">
<option value="0">none</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['polls_templates']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['polls_templates'][$this->_sections['ix']['index']]['pollId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['polls_templates'][$this->_sections['ix']['index']]['pollId'] == $this->_tpl_vars['poll_template']): ?> selected="selected"<?php endif; ?>><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['polls_templates'][$this->_sections['ix']['index']]['title'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></option>
<?php endfor; endif; ?>
</select>
title
<input type="text" name="poll_title" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['poll_title'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" size="22" />
<?php else: ?>
There is no available poll template.
<?php if ($this->_tpl_vars['tiki_p_admin_polls'] != 'y'): ?>
You should ask an admin to create them.
<?php endif;  endif;  if (count ( $this->_tpl_vars['listpolls'] )): ?>
or use 
<select name="olpoll">
<option value="">... an existing poll</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['listpolls']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['listpolls'][$this->_sections['ix']['index']]['pollId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo ((is_array($_tmp=@$this->_tpl_vars['listpolls'][$this->_sections['ix']['index']]['title'])) ? $this->_run_mod_handler('default', true, $_tmp, "<i>... no title ...</i>") : smarty_modifier_default($_tmp, "<i>... no title ...</i>"));  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> (<?php echo $this->_tpl_vars['listpolls'][$this->_sections['ix']['index']]['votes']; ?>
 votes)</option>
<?php endfor; endif; ?>
</select>
<?php endif;  endif; ?>
</td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['feature_multilingual'] == 'y'): ?>
<tr class="formcolor"><td>Language:</td><td>
<select name="lang">
<option value="">Unknown</option>
<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['languages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['languages'][$this->_sections['ix']['index']]['value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['lang'] == $this->_tpl_vars['languages'][$this->_sections['ix']['index']]['value']): ?> selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['languages'][$this->_sections['ix']['index']]['name']; ?>
</option>
<?php endfor; endif; ?>
</select>
</td></tr>

<?php endif; ?>

<?php if ($this->_tpl_vars['feature_smileys'] == 'y' && ! $this->_tpl_vars['wysiwyg']): ?>
<tr class="formcolor"><td>Smileys:</td><td>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-smileys.tpl", 'smarty_include_vars' => array('area_name' => 'editwiki')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</td>
</tr>
<?php endif;  if ($this->_tpl_vars['feature_wiki_description'] == 'y'): ?>
<tr class="formcolor"><td>Description:</td><td><input style="width:95%;" class="wikitext" type="text" name="description" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<?php endif; ?>
<tr class="formcolor"><td>Edit:<br /><br />
<?php if (! $this->_tpl_vars['wysiwyg']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "textareasize.tpl", 'smarty_include_vars' => array('area_name' => 'editwiki','formId' => 'editpageform')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><br /><br />
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-edit_help_tool.tpl", 'smarty_include_vars' => array('area_name' => 'editwiki')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</td>
<td>
<textarea id='editwiki' class="wikiedit" name="edit" rows="<?php echo $this->_tpl_vars['rows']; ?>
" cols="<?php echo $this->_tpl_vars['cols']; ?>
" style="WIDTH: 100%;"><?php echo ((is_array($_tmp=$this->_tpl_vars['pagedata'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea>
<?php if ($this->_tpl_vars['wysiwyg']): ?>
 <script type="text/javascript" src="lib/fckeditor/fckeditor.js"></script>
 <script type="text/javascript">
        sBasePath = 'lib/fckeditor/';
	var oFCKeditor = new FCKeditor( 'edit' ) ;
	oFCKeditor.BasePath	= sBasePath ;
	oFCKeditor.ReplaceTextarea() ;
 </script>
<?php endif; ?>
<input type="hidden" name="rows" value="<?php echo $this->_tpl_vars['rows']; ?>
"/>
<input type="hidden" name="cols" value="<?php echo $this->_tpl_vars['cols']; ?>
"/>
</td></tr>

<?php if ($this->_tpl_vars['feature_wiki_replace'] == 'y'): ?>
<script type="text/javascript">
<?php echo '
function searchrep() {
  c = document.getElementById(\'caseinsens\')
  s = document.getElementById(\'search\')
  r = document.getElementById(\'replace\')
  t = document.getElementById(\'editwiki\')

  var opt = \'g\';
  if (c.checked == true) {
    opt += \'i\'
  }
  var str = t.value
  var re = new RegExp(s.value,opt)
  t.value = str.replace(re,r.value)
}
'; ?>

</script>

<tr class="formcolor"><td>Search :</td><td>
<input style="width:100;" class="wikitext" type="text" id="search"/>
Replace to:
<input style="width:100;" class="wikitext" type="text" id="replace"/>
<input type="checkbox" id="caseinsens" />Case Insensitivity
<input type="button" value="replace" onclick="javascript:searchrep();">
</td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['feature_wiki_footnotes'] == 'y'):  if ($this->_tpl_vars['user']): ?>
<tr class="formcolor"><td>My Footnotes:</td><td><textarea name="footnote" rows="8" cols="42" style="width:95%;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['footnote'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</textarea></td></tr>
<?php endif;  endif; ?>

<?php if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox'): ?>
<tr class="formcolor"><td>Edit Summary:</td><td><input style="width:90%;" class="wikitext" type="text" name="comment" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['commentdata'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<?php if ($this->_tpl_vars['wiki_feature_copyrights'] == 'y'): ?>
<tr class="formcolor"><td>Copyright:</td><td>
<table border="0">
<tr class="formcolor"><td>Title:</td><td><input size="40" class="wikitext" type="text" name="copyrightTitle" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['copyrightTitle'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr class="formcolor"><td>Year:</td><td><input size="4" class="wikitext" type="text" name="copyrightYear" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['copyrightYear'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
<tr class="formcolor"><td>Authors:</td><td><input size="40" class="wikitext" name="copyrightAuthors" type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['copyrightAuthors'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" /></td></tr>
</table>
</td></tr>
<?php endif;  endif;  if ($this->_tpl_vars['feature_wiki_allowhtml'] == 'y' && $this->_tpl_vars['tiki_p_use_HTML'] == 'y'): ?>
<tr class="formcolor"><td>Allow HTML: </td><td><input type="checkbox" name="allowhtml" <?php if ($this->_tpl_vars['allowhtml'] == 'y'): ?>checked="checked"<?php endif; ?>/></td></tr>
<?php endif;  if ($this->_tpl_vars['wiki_spellcheck'] == 'y'): ?>
<tr class="formcolor"><td>Spellcheck: </td><td><input type="checkbox" name="spellcheck" <?php if ($this->_tpl_vars['spellcheck'] == 'y'): ?>checked="checked"<?php endif; ?>/></td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['feature_wiki_import_html'] == 'y'): ?>
<tr class="formcolor">
  <td>Import HTML:</td>
  <td>
    <input class="wikitext" type="text" name="suck_url" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['suck_url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />&nbsp;
  </td>
</tr>
<tr class="formcolor">
  <td>&nbsp;</td>
  <td>
    <input type="submit" class="wikiaction" name="do_suck" value="Import" />&nbsp;
    <input type="checkbox" name="parsehtml" <?php if ($this->_tpl_vars['parsehtml'] == 'y'): ?>checked="checked"<?php endif; ?>/>&nbsp;
    Try to convert HTML to wiki
  </td>
</tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['tiki_p_admin_wiki'] == 'y'): ?>
<tr class="formcolor"><td>Import page:</td><td>
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
<input name="userfile1" type="file" />
<a href="tiki-export_wiki_pages.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;all=1" class="link">export all versions</a>
</td></tr>
<?php endif;  if ($this->_tpl_vars['feature_wiki_pictures'] == 'y' && $this->_tpl_vars['tiki_p_upload_picture'] == 'y'): ?>
<tr class="formcolor"><td>Upload picture</td><td>
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
<input type="hidden" name="hasAlreadyInserted" value="" />
<input type="hidden" name="prefix" value="/img/wiki_up/<?php if ($this->_tpl_vars['tikidomain']):  echo $this->_tpl_vars['tikidomain']; ?>
/<?php endif; ?>" />
<input name="picfile1" type="file" onchange="javascript:insertImg('editwiki','picfile1','hasAlreadyInserted')"/>
</td></tr>
<?php endif;  if ($this->_tpl_vars['feature_wiki_icache'] == 'y'): ?>
<tr class="formcolor"><td>Cache</td><td>
    <select name="wiki_cache">
    <option value="0" <?php if ($this->_tpl_vars['wiki_cache'] == 0): ?>selected="selected"<?php endif; ?>>0 (no cache)</option>
    <option value="60" <?php if ($this->_tpl_vars['wiki_cache'] == 60): ?>selected="selected"<?php endif; ?>>1 minute</option>
    <option value="300" <?php if ($this->_tpl_vars['wiki_cache'] == 300): ?>selected="selected"<?php endif; ?>>5 minutes</option>
    <option value="600" <?php if ($this->_tpl_vars['wiki_cache'] == 600): ?>selected="selected"<?php endif; ?>>10 minute</option>
    <option value="900" <?php if ($this->_tpl_vars['wiki_cache'] == 900): ?>selected="selected"<?php endif; ?>>15 minutes</option>
    <option value="1800" <?php if ($this->_tpl_vars['wiki_cache'] == 1800): ?>selected="selected"<?php endif; ?>>30 minute</option>
    <option value="3600" <?php if ($this->_tpl_vars['wiki_cache'] == 3600): ?>selected="selected"<?php endif; ?>>1 hour</option>
    <option value="7200" <?php if ($this->_tpl_vars['wiki_cache'] == 7200): ?>selected="selected"<?php endif; ?>>2 hours</option>
    </select> 
</td></tr>
<?php endif; ?>

<tr class="formcolor"><td>&nbsp;</td><td><input type="hidden" name="page" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
<input type="submit" class="wikiaction" name="preview" value="preview" /></td></tr>

<?php if ($this->_tpl_vars['feature_antibot'] == 'y' && $this->_tpl_vars['anon_user'] == 'y'): ?>
<tr><td class="formcolor">Anti-Bot verification code:</td>
<td class="formcolor"><img src="tiki-random_num_img.php" alt='Random Image'/></td></tr>
<tr><td class="formcolor">Enter the code you see above:</td>
<td class="formcolor"><input type="text" maxlength="8" size="8" name="antibotcode" /></td></tr>
<?php endif; ?>

<?php if ($this->_tpl_vars['wiki_feature_copyrights'] == 'y'): ?>
<tr class="formcolor"><td>License:</td><td><a href="tiki-index.php?page=<?php echo $this->_tpl_vars['wikiLicensePage']; ?>
"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['wikiLicensePage'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a></td></tr>
<?php if ($this->_tpl_vars['wikiSubmitNotice'] != ""): ?>
<tr class="formcolor"><td>Important:</td><td><b><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['wikiSubmitNotice'];  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></b></td>
<?php endif;  endif;  if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox' || $this->_tpl_vars['tiki_p_admin'] == 'y'): ?>
<tr class="formcolor"><td>&nbsp;</td><td>
<?php if ($this->_tpl_vars['tiki_p_minor'] == 'y' && ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox'): ?>
<input type="checkbox" name="isminor" value="on" />Minor
<?php endif; ?>
<input type="submit" class="wikiaction" name="save" value="save" /> &nbsp;&nbsp;
<?php if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox'): ?>
<input type="submit" class="wikiaction" name="cancel_edit" value="cancel edit" />
<?php endif;  endif; ?>
</td></tr>
</table>
</form>
<br />
<?php if ($this->_tpl_vars['wysiwyg'] != 'y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-edit_help.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>