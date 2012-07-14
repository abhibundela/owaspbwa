<?php /* Smarty version 2.6.10, created on 2012-07-13 15:00:49
         compiled from gallery:modules/core/templates/AutoComplete.tpl */ ?>
<?php if ($this->_tpl_vars['callCount'] == 1): ?>
<script type="text/javascript" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "lib/javascript/AutoComplete.js"), $this);?>
"></script>
<script type="text/javascript" src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "lib/javascript/XmlHttp.js"), $this);?>
"></script>
<?php endif; ?>
<script type="text/javascript">
  // <![CDATA[
  autoCompleteAttach('<?php echo $this->_tpl_vars['element']; ?>
', '<?php echo $this->_tpl_vars['url']; ?>
');
  // ]]>
</script>
