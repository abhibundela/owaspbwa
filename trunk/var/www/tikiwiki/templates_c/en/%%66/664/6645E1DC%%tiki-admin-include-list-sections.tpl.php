<?php /* Smarty version 2.6.14, created on 2011-04-21 08:15:44
         compiled from tiki-admin-include-list-sections.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'tr', 'tiki-admin-include-list-sections.tpl', 11, false),array('function', 'help', 'tiki-admin-include-list-sections.tpl', 12, false),)), $this); ?>


<div class="rbox" name="tip">
<div class="rbox-title" name="tip">Tip</div>  
<div class="rbox-data" name="tip">Enable/disable Tiki features in  <a class="rbox-link" href="tiki-admin.php?page=features">Admin->Features</a>, but configure them elsewhere</div>
</div>
<br />

<div class="cbox">
  <div class="cbox-title">
    <?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start();  echo $this->_tpl_vars['crumbs'][$this->_tpl_vars['crumb']]->description;  $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
    <?php echo smarty_function_help(array('crumb' => $this->_tpl_vars['crumbs'][$this->_tpl_vars['crumb']]), $this);?>

  </div>
  <div class="cbox-data">
    <table width="100%"><tr>
    </tr><tr>
      <td width="33%" style="text-align:center;"><a title="Features"
          href="tiki-admin.php?page=features" class="link"><img border="0"
          src="img/icons/admin_features.png" alt="icon" /><br />
          Features</a></td>
      <td width="33%"  style="text-align:center;"><a title="General"
          href="tiki-admin.php?page=general" class="link"><img border="0"
          src="img/icons/admin_general.png" alt="icon" /><br />
          General</a></td>
      <td width="33%"  style="text-align:center;">
          <a title="Login"
          href="tiki-admin.php?page=login" class="link"><img border="0"
          src="img/icons/admin_login.png" alt="icon" /><br />
          Login</a></td>
    </tr><tr>
      <td width="33%"  style="text-align:center;">
          <a title="Wiki"
          href="tiki-admin.php?page=wiki" class="link">
	  <?php if ($this->_tpl_vars['feature_wiki'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_wiki.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_wiki_grey.png" alt="icon" />
	  <?php endif; ?>
          <br />Wiki</a>
      </td>
      <td width="33%" style="text-align:center;">
          <a href="tiki-admin.php?page=gal"
	  title="Image Galleries"
          class="link">
	  <?php if ($this->_tpl_vars['feature_galleries'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_imagegal.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_imagegal_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Image Galleries</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=cms"
	  title="Articles"
          class="link">
	  <?php if ($this->_tpl_vars['feature_articles'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_articles.png" alt="icon" />
	  <?php else: ?>
 	    <img border="0" src="img/icons/admin_articles_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Articles</a>
      </td>
    </tr><tr>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=blogs"
	  title="Blogs"
          class="link">
	  <?php if ($this->_tpl_vars['feature_blogs'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_blogs.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_blogs_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Blogs</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=forums"
	  title="Forums"
          class="link">
	  <?php if ($this->_tpl_vars['feature_forums'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_forums.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_forums_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Forums</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=directory"
	  title="Directory"
          class="link">
	  <?php if ($this->_tpl_vars['feature_directory'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_directory.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_directory_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Directory</a>
      </td>
    </tr><tr>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=fgal"
	  title="File Galleries"
          class="link">
	  <?php if ($this->_tpl_vars['feature_file_galleries'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_filegal.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_filegal_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />File Galleries</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=faqs"
	  title="FAQs"
          class="link">
	  <?php if ($this->_tpl_vars['feature_faqs'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_faqs.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_faqs_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />FAQs</a>
      </td>
      <td width="33%" style="text-align:center;">
          <a href="tiki-admin.php?page=maps"
	  title="Maps"
          class="link">
	  <?php if ($this->_tpl_vars['feature_maps'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_maps.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_maps_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Maps</a>
      </td>
    </tr><tr>
      <td width="33%" style="text-align:center;">
          <a href="tiki-admin.php?page=trackers"
	  title="Trackers"
          class="link">
	  <?php if ($this->_tpl_vars['feature_trackers'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_trackers.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_trackers_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Trackers</a>
      </td>
      <td style="text-align:center;">
         <a href="tiki-admin.php?page=calendar"
         title="Calendar"
         class="link">
	 <?php if ($this->_tpl_vars['feature_calendar'] == 'y'): ?>
	   <img border="0" src="img/icons/admin_calendar.png" alt="icon" />
	 <?php else: ?>
	   <img border="0" src="img/icons/admin_calendar_grey.png" alt="icon" />
	 <?php endif; ?>
	 <br />Calendar</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=userfiles"
	  title="User files"
          class="link">
	  <?php if ($this->_tpl_vars['feature_userfiles'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_userfiles.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_userfiles_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />User files</a>
      </td>
    </tr><tr>
      <td width="33%" style="text-align:center;">
          <a href="tiki-admin.php?page=polls"
	  title="Polls"
          class="link">
	  <?php if ($this->_tpl_vars['feature_polls'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_polls.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_polls_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Polls</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=search"
	  title="Search"
          class="link">
	  <?php if ($this->_tpl_vars['feature_search'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_search.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_search_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Search</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=webmail"
	  title="Webmail"
          class="link">
	  <?php if ($this->_tpl_vars['feature_webmail'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_webmail.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_webmail_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Webmail</a>
      </td>
    </tr><tr>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=rss"
	  title="RSS"
          class="link"><img border="0" src="img/icons/admin_rss.png"
          alt="icon" /><br />RSS</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=score"
	  title="Score"
          class="link">
	  <?php if ($this->_tpl_vars['feature_score'] == 'y'): ?>
	    <img border="0" src="img/icons/admin_score.png" alt="icon" />
	  <?php else: ?>
	    <img border="0" src="img/icons/admin_score_grey.png" alt="icon" />
	  <?php endif; ?>
	  <br />Score</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=metatags"
	  title="Meta Tags"
          class="link"><img border="0" src="img/icons/admin_metatags.png"
          alt="icon" /><br />Meta Tags</a>
      </td>
    </tr>
    <tr>
      <td style="text-align:center;">
           <a href="tiki-admin.php?page=community"
           title="Community"
           class="link"><img border="0" src="img/icons/admin_community.png"
           alt="icon" /><br />Community</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=siteid"
     	   title="Site Identity"
	   class="link">
	   <?php if ($this->_tpl_vars['feature_siteidentity'] == 'y'): ?>
	     <img border="0" src="img/icons/admin_siteid.png" alt="icon" />
	   <?php else: ?>
	     <img border="0" src="img/icons/admin_siteid_grey.png" alt="icon" />
	   <?php endif; ?>
	   <br />Site Identity</a>
      </td>
      <td style="text-align:center;">
          <a href="tiki-admin.php?page=intertiki"
     	   title="InterTiki"
	   class="link">
	   <?php if ($this->_tpl_vars['feature_intertiki'] == 'y'): ?>
	     <img border="0" src="img/icons/admin_intertiki.png" alt="icon" />
	   <?php else: ?>
	     <img border="0" src="img/icons/admin_intertiki_grey.png" alt="icon" />
	   <?php endif; ?>
	   <br />InterTiki</a>
      </td>
    </tr>
		<tr>
<td style="text-align:center;">
<a href="tiki-admin.php?page=gmap" title="Google Maps" class="link">
<?php if ($this->_tpl_vars['feature_gmap'] == 'y'): ?>
<img border="0" src="img/icons/admin_gmap.png" alt="icon" />
<?php else: ?>
<img border="0" src="img/icons/admin_gmap_grey.png" alt="icon" />
<?php endif; ?>
<br />Google Maps</a>
</td>
<td style="text-align:center;">
<a href="tiki-admin.php?page=i18n" title="i18n" class="link">
<img border="0" src="img/icons/admin_i18n.png" alt="icon" />
<br />i18n</a>
</td>
<td style="text-align:center;">
</td>



		</tr>
   </table>
  </div>
</div>
