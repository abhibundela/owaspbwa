<?php /* Smarty version 2.6.14, created on 2011-04-21 08:15:44
         compiled from tiki-admin.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'popup_init', 'tiki-admin.tpl', 1, false),array('function', 'breadcrumbs', 'tiki-admin.tpl', 3, false),)), $this); ?>
<?php echo smarty_function_popup_init(array('src' => "lib/overlib.js"), $this);?>

<div id="pageheader">
<?php echo smarty_function_breadcrumbs(array('type' => 'trail','loc' => 'admin','crumbs' => $this->_tpl_vars['crumbs']), $this);?>

<?php echo smarty_function_breadcrumbs(array('type' => 'pagetitle','loc' => 'admin','crumbs' => $this->_tpl_vars['crumbs']), $this);?>

<?php echo smarty_function_breadcrumbs(array('type' => 'desc','loc' => 'page','crumbs' => $this->_tpl_vars['trail']), $this);?>

</div>

<?php if (in_array ( $_GET['page'] , array ( 'features' , 'general' , 'login' , 'wiki' , 'gal' , 'fgal' , 'cms' , 'polls' , 'search' , 'blogs' , 'forums' , 'faqs' , 'trackers' , 'webmail' , 'rss' , 'directory' , 'userfiles' , 'maps' , 'metatags' , 'wikiatt' , 'score' , 'community' , 'siteid' , 'calendar' , 'intertiki' , 'gmap' , 'i18n' ) )): ?>
  <?php $this->assign('include', $_GET['page']);  else: ?>
  <?php $this->assign('include', "list-sections");  endif;  if ($this->_tpl_vars['include'] != "list-sections"): ?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-admin-include-anchors.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif;  if ($this->_tpl_vars['tikifeedback']): ?>
<div class="simplebox highlight"><?php unset($this->_sections['n']);
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
<?php endif;  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tiki-admin-include-".($this->_tpl_vars['include']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<br /><br />
<div class="cbox">
<div class="cbox-title">Crosslinks to other features and settings</div>
<div class="cbox-data">
Other sections:<br />
<?php if ($this->_tpl_vars['feature_sheet'] == 'y'): ?> <a href="tiki-sheets.php">Spreadsheet</a> <?php endif;  if ($this->_tpl_vars['feature_newsletters'] == 'y'): ?> <a href="tiki-admin_newsletters.php">Newsletters</a> <?php endif;  if ($this->_tpl_vars['feature_surveys'] == 'y'): ?> <a href="tiki-admin_surveys.php">Surveys</a> <?php endif;  if ($this->_tpl_vars['feature_quizzes'] == 'y'): ?> <a href="tiki-edit_quiz.php">Quizzes</a> <?php endif;  if ($this->_tpl_vars['feature_integrator'] == 'y'): ?> <a href="tiki-admin_integrator.php">Integrator</a> <?php endif;  if ($this->_tpl_vars['feature_html_pages'] == 'y'): ?> <a href="tiki-admin_html_pages.php">HTML pages</a> <?php endif;  if ($this->_tpl_vars['feature_shoutbox'] == 'y'): ?> <a href="tiki-shoutbox.php">Shoutbox</a> <a 
href="tiki-admin_shoutbox_words.php">Shoutbox Words</a> <?php endif;  if ($this->_tpl_vars['feature_live_support'] == 'y'): ?> <a href="tiki-live_support_admin.php">Live support</a> <?php endif;  if ($this->_tpl_vars['feature_chat'] == 'y'): ?> <a href="tiki-admin_chat.php">Chat</a> <?php endif;  if ($this->_tpl_vars['feature_charts'] == 'y'): ?> <a href="tiki-admin_charts.php">Charts</a> <?php endif;  if ($this->_tpl_vars['feature_eph'] == 'y'): ?> <a href="tiki-eph_admin.php">Ephemerides</a> <?php endif;  if ($this->_tpl_vars['feature_workflow'] == 'y'): ?> <a href="tiki-g-admin_processes.php">Workflow</a> <?php endif; ?>

<?php if ($this->_tpl_vars['feature_games'] == 'y'): ?> <a href="tiki-list_games.php">Games</a> <?php endif;  if ($this->_tpl_vars['feature_contact'] == 'y'): ?> <a href="tiki-contact.php">Contact us</a> <?php endif; ?>
<hr>

Administration features:<br />
<a href="tiki-adminusers.php">Users</a> 
<a href="tiki-admingroups.php">Groups</a> 
<a href="tiki-admin_security.php">Security</a> 
<a href="tiki-admin_system.php">System</a> 
<a href="tiki-syslog.php">SysLogs</a> 
<a href="tiki-phpinfo.php">phpinfo</a> 
<a href="tiki-mods.php">Mods</a>
<a href="tiki-backup.php">Backups</a>
<?php if ($this->_tpl_vars['feature_banning'] == 'y'): ?><a href="tiki-admin_banning.php">Banning</a> <?php endif;  if ($this->_tpl_vars['lang_use_db'] == 'y'): ?><a href="tiki-edit_languages.php">Edit languages</a> <?php endif; ?>
<hr>

Transversal features (which apply to more than one section):<br />
<a href="tiki-admin_notifications.php">Mail notifications</a> 
<hr>

Navigation features:<br />
<a href="tiki-admin_menus.php">Menus</a> 
<a href="tiki-admin_modules.php">Modules</a>
<?php if ($this->_tpl_vars['feature_categories'] == 'y'): ?> <a href="tiki-admin_categories.php">Categories</a> <?php endif;  if ($this->_tpl_vars['feature_featuredLinks'] == 'y'): ?><a href="tiki-admin_links.php">Links</a><?php endif; ?>
<hr>

Look &amp; feel (themes):<br />
<?php if ($this->_tpl_vars['feature_theme_control'] == 'y'): ?> <a href="tiki-theme_control.php">Theme control</a> <?php endif;  if ($this->_tpl_vars['feature_edit_templates'] == 'y'): ?> <a href="tiki-edit_templates.php">Edit templates</a> <?php endif;  if ($this->_tpl_vars['feature_editcss'] == 'y'): ?> <a href="tiki-edit_css.php">Edit CSS</a> <?php endif;  if ($this->_tpl_vars['feature_mobile'] == 'y'): ?> <a href="tiki-mobile.php">Mobile</a> <?php endif; ?>
<hr>

Text area features (features you can use in all text areas, like wiki pages, blogs, articles, forums, etc):<br />
<a href="tiki-admin_cookies.php">Cookies</a> 
<?php if ($this->_tpl_vars['feature_hotwords'] == 'y'): ?> <a href="tiki-admin_hotwords.php">Hotwords</a> <?php endif; ?>
<a href="tiki-list_cache.php">Cache</a> 
<a href="tiki-admin_quicktags.php">QuickTags</a> 
<a href="tiki-admin_content_templates.php">Content templates</a> 
<a href="tiki-admin_dsn.php">DSN</a> 
<?php if ($this->_tpl_vars['feature_drawings'] == 'y'): ?><a href="tiki-admin_drawings.php">Drawings</a> <?php endif;  if ($this->_tpl_vars['feature_dynamic_content'] == 'y'): ?><a href="tiki-list_contents.php">Dynamic content</a> <?php endif; ?>
<a href="tiki-admin_external_wikis.php">External wikis</a> 
<?php if ($this->_tpl_vars['feature_mailin'] == 'y'): ?><a href="tiki-admin_mailin.php">Mail-in</a> <?php endif; ?>
<hr>

Stats &amp; banners:<br />
<?php if ($this->_tpl_vars['feature_stats'] == 'y'): ?> <a href="tiki-stats.php">Stats</a> <?php endif;  if ($this->_tpl_vars['feature_referer_stats'] == 'y'): ?> <a href="tiki-referer_stats.php">Referer stats</a> <?php endif;  if ($this->_tpl_vars['feature_search'] == 'y' && $this->_tpl_vars['feature_search_stats'] == 'y'): ?> <a href="tiki-search_stats.php">Search stats</a>  <?php endif;  if ($this->_tpl_vars['feature_banners'] == 'y'): ?> <a href="tiki-list_banners.php">Banners</a> <?php endif; ?>
</div>
</div>