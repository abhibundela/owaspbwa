<?php /* Smarty version 2.6.14, created on 2011-04-22 02:21:45
         compiled from modules/mod-assistant.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'tikimodule', 'modules/mod-assistant.tpl', 2, false),)), $this); ?>

<?php $this->_tag_stack[] = array('tikimodule', array('title' => 'TikiWiki Assistant','name' => 'assistant','flip' => $this->_tpl_vars['module_params']['flip'],'decorations' => $this->_tpl_vars['module_params']['decorations'])); $_block_repeat=true;smarty_block_tikimodule($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<div><em>Thank you for installing TikiWiki!</em><br />
Click the :: options in the Menu for more options.
Please, also see <a class="link" href="http://tikiwiki.org/TikiMovies">TikiMovies</a> for more setup details.
<?php if ($this->_tpl_vars['tiki_p_admin'] == 'y'): ?>
<p><strong>Note 1:</strong> You can remove this module in <a class="link" href="tiki-admin_modules.php">Admin&#160;»&#160;Modules</a> as well as assign or edit many others.<br />
<strong>Note 2:</strong> The menu module installed by default is named <em>mnu_application_menu</em> &ndash; it is a "custom module" which includes menu ID 42. That menu is stored in database and it can be edited from <a class="link" href="tiki-admin_menus.php">Admin&#160;»&#160;Menus</a>.<br />
(Do not mix this with the original <em>application_menu</em> module. That one can be heavily customized to match style used but it can be currently done only by editing mod-application_menu.tpl file "manually")</p>
<?php endif; ?>
</div>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tikimodule($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>