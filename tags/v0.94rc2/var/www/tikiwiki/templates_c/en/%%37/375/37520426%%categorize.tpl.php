<?php /* Smarty version 2.6.14, created on 2011-04-21 08:13:32
         compiled from categorize.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'categorize.tpl', 14, false),)), $this); ?>
<?php if ($this->_tpl_vars['feature_categories'] == 'y' && ( count ( $this->_tpl_vars['categories'] ) > 0 || $this->_tpl_vars['tiki_p_admin_categories'] == 'y' )): ?>
<tr class="formcolor">
 <td>Categorize</td>
 <td<?php if ($this->_tpl_vars['cols']): ?> colspan="<?php echo $this->_tpl_vars['cols']; ?>
"<?php endif; ?>>
  [ <a class="link" href="javascript:show('categorizator');">show categories</a>
  | <a class="link" href="javascript:hide('categorizator');">hide categories</a> ]
  <div id="categorizator" <?php if ($this->_tpl_vars['cat_categorize'] == 'n' && $this->_tpl_vars['categ_checked'] != 'y'): ?>style="display:none;"<?php else: ?>style="display:block;"<?php endif; ?>>
<?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
	<div class="simplebox">Tip: hold down CTRL to select multiple categories</div>
<?php endif; ?>
  <?php if (count ( $this->_tpl_vars['categories'] ) > 0): ?>
   <select name="cat_categories[]" multiple="multiple" size="5">
   <?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['categories'][$this->_sections['ix']['index']]['categId'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['categories'][$this->_sections['ix']['index']]['incat'] == 'y'): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['categories'][$this->_sections['ix']['index']]['categpath']; ?>
</option>
   <?php endfor; endif; ?>
   </select><br />
   <label for="cat-check">categorize this object:</label>
    <input type="checkbox" name="cat_categorize" id="cat-check" <?php if ($this->_tpl_vars['cat_categorize'] == 'y' || $this->_tpl_vars['categ_checked'] == 'y'): ?>checked="checked"<?php endif; ?>/><br />
<?php if ($this->_tpl_vars['feature_help'] == 'y'): ?>
    <div class="simplebox">Tip: uncheck the above checkbox to uncategorize this page/object</div>
<?php endif; ?>
  <?php else: ?>
    No categories defined <br />
  <?php endif; ?>
  <?php if ($this->_tpl_vars['tiki_p_admin_categories'] == 'y'): ?>
    <a href="tiki-admin_categories.php" class="link">Admin categories</a>
  <?php endif; ?>
  </div>
  </td>
</tr>
<?php endif; ?>