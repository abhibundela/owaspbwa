<?php /* Smarty version 2.6.14, created on 2011-04-21 08:07:37
         compiled from tiki-page_bar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lower', 'tiki-page_bar.tpl', 9, false),array('modifier', 'escape', 'tiki-page_bar.tpl', 12, false),array('block', 'tr', 'tiki-page_bar.tpl', 132, false),)), $this); ?>


<hr/>
<div id="page-bar">
  <table>
    <tr>


<?php if (( $this->_tpl_vars['editable'] && ( $this->_tpl_vars['tiki_p_edit'] == 'y' || ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == 'sandbox' ) ) || $this->_tpl_vars['tiki_p_admin_wiki'] == 'y'): ?>
    <td>
      <div class="button2" >
      <a title="<?php echo $this->_tpl_vars['semUser']; ?>
" href="tiki-editpage.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  if ($this->_tpl_vars['page_ref_id']): ?>&amp;page_ref_id=<?php echo $this->_tpl_vars['page_ref_id'];  endif; ?>" class="linkbut">
        <?php if ($this->_tpl_vars['beingEdited'] == 'y'): ?>
          <span class="highlight">edit</span>
        <?php else: ?>
          edit
        <?php endif; ?>
      </a>
      </div>
    </td>
<?php else: ?>
    <?php if ($this->_tpl_vars['feature_history'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_history'] == 'y'): ?>
    <td>
      <div class="button2" >
      <a href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;source=0" class="linkbut">
        source
      </a>
      </div>
    </td>
    <?php endif;  endif; ?>

<?php if (((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) != 'sandbox'): ?>

<?php if ($this->_tpl_vars['tiki_p_remove'] == 'y' && $this->_tpl_vars['editable']): ?>
<td><div class="button2"><a href="tiki-removepage.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;version=last" class="linkbut">remove</a></div></td>
<?php endif;  if ($this->_tpl_vars['tiki_p_rename'] == 'y' && $this->_tpl_vars['editable']): ?>
<td><div class="button2"><a href="tiki-rename_page.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">rename</a></div></td>
<?php endif;  if ($this->_tpl_vars['lock'] && ( $this->_tpl_vars['tiki_p_admin_wiki'] == 'y' || ( $this->_tpl_vars['user'] && ( $this->_tpl_vars['user'] == $this->_tpl_vars['page_user'] || $this->_tpl_vars['user'] == 'admin' ) && ( $this->_tpl_vars['tiki_p_lock'] == 'y' ) && ( $this->_tpl_vars['feature_wiki_usrlock'] == 'y' ) ) )): ?>
<td><div class="button2"><a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;action=unlock" class="linkbut">unlock</a></div></td>
<?php endif;  if (! $this->_tpl_vars['lock'] && ( $this->_tpl_vars['tiki_p_admin_wiki'] == 'y' || ( ( $this->_tpl_vars['tiki_p_lock'] == 'y' ) && ( $this->_tpl_vars['feature_wiki_usrlock'] == 'y' ) ) )): ?>
<td><div class="button2"><a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;action=lock" class="linkbut">lock</a></div></td>
<?php endif;  if ($this->_tpl_vars['tiki_p_admin_wiki'] == 'y'): ?>
<td><div class="button2"><a href="tiki-pagepermissions.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">perms</a></div></td>
<?php endif; ?>

<?php if ($this->_tpl_vars['feature_history'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_history'] == 'y'): ?>
<td><div class="button2"><a href="tiki-pagehistory.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">history</a></div></td>
<?php endif;  endif; ?>

<?php if ($this->_tpl_vars['feature_likePages'] == 'y'): ?>
<td><div class="button2"><a href="tiki-likepages.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">similar</a></div></td>
<?php endif;  if ($this->_tpl_vars['feature_wiki_undo'] == 'y' && $this->_tpl_vars['canundo'] == 'y'): ?>
<td><div class="button2"><a href="tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;undo=1" class="linkbut">undo</a></div></td>
<?php endif;  if ($this->_tpl_vars['wiki_uses_slides'] == 'y'):  if ($this->_tpl_vars['show_slideshow'] == 'y'): ?>
<td><div class="button2"><a href="tiki-slideshow.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">slides</a></div></td>
<?php elseif ($this->_tpl_vars['structure'] == 'y'): ?>
<td><div class="button2"><a href="tiki-slideshow2.php?page_ref_id=<?php echo $this->_tpl_vars['page_info']['page_ref_id']; ?>
" class="linkbut">slides</a></div></td>
<?php endif;  endif;  if ($this->_tpl_vars['feature_wiki_export'] == 'y' && $this->_tpl_vars['tiki_p_admin_wiki'] == 'y'): ?>
<td><div class="button2"><a href="tiki-export_wiki_pages.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">export</a></div></td>
<?php endif;  if ($this->_tpl_vars['feature_wiki_discuss'] == 'y'): ?>
<td><div class="button2"><a href="tiki-view_forum.php?forumId=<?php echo $this->_tpl_vars['wiki_forum_id']; ?>
&amp;comments_postComment=post&amp;comments_title=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;comments_data=<?php echo ((is_array($_tmp=$this->_tpl_vars['wiki_discussion_string'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
: <?php echo "[tiki-index.php?page=";  echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo "|";  echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo "]"; ?>
&amp;comment_topictype=n" class="linkbut">discuss</a></div></td>
<?php endif; ?>


<?php if ($this->_tpl_vars['edit_page'] == 'y'): ?> 
  <td>
    <div class="button2">
      <a href="#" onclick="javascript:flip('edithelpzone'); return false;" class="linkbut">wiki help</a>
    </div>
  </td>
<?php endif; ?>

<?php if ($this->_tpl_vars['show_page'] == 'y'): ?> 

  
  <?php if ($this->_tpl_vars['feature_wiki_comments'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_comments'] == 'y' && ( ( $this->_tpl_vars['tiki_p_read_comments'] == 'y' && $this->_tpl_vars['comments_cant'] != 0 ) || $this->_tpl_vars['tiki_p_post_comments'] == 'y' || $this->_tpl_vars['tiki_p_edit_comments'] == 'y' )): ?>
   <td>
    <div class="button2">
      <a href="<?php if ($this->_tpl_vars['comments_show'] != 'y'): ?>tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;comzone=show#comments<?php else: ?>tiki-index.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
&amp;comzone=hide<?php endif; ?>" onclick="javascript:flip('comzone<?php if ($this->_tpl_vars['comments_show'] == 'y'): ?>open<?php endif; ?>');<?php if ($this->_tpl_vars['comments_show'] == 'y'): ?> return false;<?php endif; ?>"
         class="linkbut">
	<?php if ($this->_tpl_vars['comments_cant'] == 0 || ( $this->_tpl_vars['tiki_p_read_comments'] == 'n' && $this->_tpl_vars['tiki_p_post_comments'] == 'y' )): ?>
          add comment
        <?php elseif ($this->_tpl_vars['comments_cant'] == 1): ?>
          <span class="highlight">1 comment</span>
        <?php else: ?>
          <span class="highlight"><?php echo $this->_tpl_vars['comments_cant']; ?>
 comments</span>
        <?php endif; ?>
      </a>
    </div>
   </td>
  <?php endif; ?>

  

  <?php  global $atts; global $smarty; $smarty->assign('atts_cnt', count($atts["data"]));  ?>
  <?php if ($this->_tpl_vars['feature_wiki_attachments'] == 'y' && ( $this->_tpl_vars['tiki_p_wiki_view_attachments'] == 'y' && count ( $this->_tpl_vars['atts'] ) > 0 || $this->_tpl_vars['tiki_p_wiki_attach_files'] == 'y' || $this->_tpl_vars['tiki_p_wiki_admin_attachments'] == 'y' )): ?>

  <td>
    <div class="button2">
      <a href="#attachments" onclick="javascript:flip('attzone');" class="linkbut">

        
        <?php if ($this->_tpl_vars['atts_cnt'] == 0 || $this->_tpl_vars['tiki_p_wiki_attach_files'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_attachments'] == 'n' && $this->_tpl_vars['tiki_p_wiki_admin_attachments'] == 'n'): ?>
          attach file
        <?php elseif ($this->_tpl_vars['atts_cnt'] == 1): ?>
          <span class="highlight">1 file attached</span>
        <?php else: ?>
          <span class="highlight"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['atts_cnt']; ?>
 files attached<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></span>
        <?php endif; ?>
      </a>
    </div>
  </td>
  <?php endif; ?>

  <?php if ($this->_tpl_vars['feature_multilingual'] == 'y' && $this->_tpl_vars['tiki_p_edit'] == 'y' && ! $this->_tpl_vars['lock']): ?>
     <td><div class="button2"><a href="tiki-edit_translation.php?page=<?php echo ((is_array($_tmp=$this->_tpl_vars['page'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" class="linkbut">translation</a></div></td>
  <?php endif; ?>

<?php endif; ?>

</tr>
</table>
</div>

<?php if ($this->_tpl_vars['wiki_extras'] == 'y' && $this->_tpl_vars['feature_wiki_attachments'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_attachments'] == 'y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "attachments.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>

<?php if ($this->_tpl_vars['feature_wiki_comments'] == 'y' && $this->_tpl_vars['tiki_p_wiki_view_comments'] == 'y'):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "comments.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>