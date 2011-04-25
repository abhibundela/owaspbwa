<?php /* Smarty version 2.6.14, created on 2011-04-22 02:21:45
         compiled from tiki-bot_bar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'elapsed', 'tiki-bot_bar.tpl', 62, false),array('function', 'memusage', 'tiki-bot_bar.tpl', 62, false),)), $this); ?>
<?php if ($this->_tpl_vars['feature_bot_bar_icons'] == 'y'): ?>
	<div id="power" style="text-align: center">
		<a href="http://tikiwiki.org/" title="TikiWiki"><img style="border: 0; vertical-align: middle" alt="Powered by TikiWiki" src="img/tiki/tikibutton2.png" /></a>
		<a href="http://www.php.net/" title="PHP"><img style="border: 0; vertical-align: middle" alt="Powered by PHP" src="img/php.png" /></a>
		<a href="http://smarty.php.net/" title="Smarty"><img style="border: 0; vertical-align: middle" alt="Powered by Smarty" src="img/smarty.gif"  /></a>
		<a href="http://adodb.sourceforge.net/" title="ADOdb"><img style="border: 0; vertical-align: middle" alt="Powered by ADOdb" src="img/adodb.png" /></a>
		<a href="http://www.w3.org/Style/CSS/" title="CSS"><img style="border: 0; vertical-align: middle" alt="Made with CSS" src="img/css1.png" /></a>
		<a href="http://www.w3.org/RDF/" title="RDF"><img style="border: 0; vertical-align: middle" alt="Powered by RDF" src="img/rdf.gif"  /></a>
		<?php if ($this->_tpl_vars['feature_phplayers'] == 'y'): ?>
		<a href="http://phplayersmenu.sourceforge.net/" title="PHP Layers Menu"><img style="border: 0; vertical-align: middle" alt="powered by The PHP Layers Menu System" src="lib/phplayers/LOGOS/powered_by_phplm.png"  /></a>		
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_mobile'] == 'y'): ?>
		<a href="http://www.hawhaw.de/" title="HAWHAW"><img style="border: 0; vertical-align: middle" alt="powered by HAWHAW" src="img/poweredbyhawhaw.gif"  /></a>		
		<?php endif; ?>		
		
	</div>

<?php endif; ?>
	<div id="rss" style="text-align: center">
		<?php if ($this->_tpl_vars['feature_wiki'] == 'y' && $this->_tpl_vars['rss_wiki'] == 'y' && $this->_tpl_vars['tiki_p_view'] == 'y'): ?>
				<a title="Wiki RSS" href="tiki-wiki_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Wiki</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_blogs'] == 'y' && $this->_tpl_vars['rss_blogs'] == 'y' && $this->_tpl_vars['tiki_p_read_blog'] == 'y'): ?>
				<a title="Blogs RSS" href="tiki-blogs_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Blogs</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_articles'] == 'y' && $this->_tpl_vars['rss_articles'] == 'y' && $this->_tpl_vars['tiki_p_read_article'] == 'y'): ?>
				<a title="Articles RSS" href="tiki-articles_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="rss" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Articles</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_galleries'] == 'y' && $this->_tpl_vars['rss_image_galleries'] == 'y' && $this->_tpl_vars['tiki_p_view_image_gallery'] == 'y'): ?>
				<a title="Image Galleries RSS" href="tiki-image_galleries_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Image Galleries</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_file_galleries'] == 'y' && $this->_tpl_vars['rss_file_galleries'] == 'y' && $this->_tpl_vars['tiki_p_view_file_gallery'] == 'y'): ?>
				<a title="File Galleries RSS" href="tiki-file_galleries_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>File Galleries</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_forums'] == 'y' && $this->_tpl_vars['rss_forums'] == 'y' && $this->_tpl_vars['tiki_p_forum_read'] == 'y'): ?>
				<a title="Forums RSS" href="tiki-forums_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Forums</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_maps'] == 'y' && $this->_tpl_vars['rss_mapfiles'] == 'y' && $this->_tpl_vars['tiki_p_map_view'] == 'y'): ?>
				<a title="Maps RSS" href="tiki-map_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="RSS" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Maps</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_directory'] == 'y' && $this->_tpl_vars['rss_directories'] == 'y' && $this->_tpl_vars['tiki_p_view_directory'] == 'y'): ?>
				<a href="tiki-directories_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="rss" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Directories</small>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['feature_calendar'] == 'y' && $this->_tpl_vars['rss_calendar'] == 'y' && $this->_tpl_vars['tiki_p_view_calendar'] == 'y'): ?>
				<a href="tiki-calendars_rss.php?ver=<?php echo $this->_tpl_vars['rssfeed_default_version']; ?>
"><img alt="rss" style="border: 0; vertical-align: text-bottom;" src="img/rss.png" /></a>
				<small>Calendars</small>
		<?php endif; ?>
	</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "babelfish.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['feature_bot_bar_debug'] == 'y'): ?>
<div id="loadstats" style="text-align: center">
	<small>[ Execution time: <?php echo smarty_function_elapsed(array(), $this);?>
 secs ] &nbsp; [ Memory usage: <?php echo smarty_function_memusage(array(), $this);?>
 ] &nbsp; [ <?php echo $this->_tpl_vars['num_queries']; ?>
 database queries used ] &nbsp; [ GZIP <?php echo $this->_tpl_vars['gzip']; ?>
 ] &nbsp; [ Server load: <?php echo $this->_tpl_vars['server_load']; ?>
 ]</small>
</div>
<?php endif; ?>