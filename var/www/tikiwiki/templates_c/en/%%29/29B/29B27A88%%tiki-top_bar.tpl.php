<?php /* Smarty version 2.6.14, created on 2011-04-21 08:07:37
         compiled from tiki-top_bar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'tiki_short_datetime', 'tiki-top_bar.tpl', 4, false),array('function', 'phplayers', 'tiki-top_bar.tpl', 12, false),)), $this); ?>
This is TikiWiki 1.9.5 -Sirius- &#169; 2002&#8211;2006 by the <a href="http://tikiwiki.org" 
title="tikiwiki.org">Tiki community</a>
<?php if ($this->_tpl_vars['feature_calendar'] == 'y' && $this->_tpl_vars['tiki_p_view_calendar'] == 'y'): ?>
  <a href="tiki-calendar.php"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('tiki_short_datetime', true, $_tmp) : smarty_modifier_tiki_short_datetime($_tmp)); ?>
</a>
<?php else: ?>
  <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('tiki_short_datetime', true, $_tmp) : smarty_modifier_tiki_short_datetime($_tmp)); ?>

<?php endif;  if ($this->_tpl_vars['tiki_p_admin'] == 'y' && $this->_tpl_vars['feature_debug_console'] == 'y'): ?>
  &#160;//&#160;<a href="javascript:toggle('debugconsole');">debug</a>
<?php endif;  if ($this->_tpl_vars['feature_phplayers'] == 'y' && $this->_tpl_vars['feature_siteidentity'] == 'y' && $this->_tpl_vars['feature_sitemenu'] == 'y'):  echo smarty_function_phplayers(array('id' => 42,'type' => 'horiz'), $this);?>

<?php endif; ?>