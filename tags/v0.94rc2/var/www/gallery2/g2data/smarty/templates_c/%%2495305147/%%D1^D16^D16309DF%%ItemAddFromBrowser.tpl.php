<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:16
         compiled from gallery:modules/core/templates/ItemAddFromBrowser.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/core/templates/ItemAddFromBrowser.tpl', 62, false),)), $this); ?>
<?php if (! $this->_tpl_vars['ItemAddFromBrowser']['uploadsPermitted']): ?>
<div class="gbBlock giError">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your webserver is configured to disallow file uploads from your web browser at this time.  Please contact your system administrator for assistance."), $this);?>

</div>
<?php else: ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Upload files directly from your computer."), $this);?>

    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Enter the full path to the file and an optional caption in the boxes below."), $this);?>

    <input type="hidden"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[uploadBoxCount]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['uploadBoxCount']; ?>
" />
  </p>

  <p class="giDescription">
    <?php if ($this->_tpl_vars['ItemAddFromBrowser']['maxFileSize'] == 0): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "<b>Note:</b> You can upload up to %s at one time.  If you want to upload more than that, you must upload the files separately, use a different upload format, or ask your system administrator to allow larger uploads.",'arg1' => $this->_tpl_vars['ItemAddFromBrowser']['totalUploadSize']), $this);?>

    <?php else: ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "<b>Note:</b> You can upload up to %s at one time.  No individual file may be larger than %s. If you want to upload more than that, you must upload the files separately, use a different upload format, or ask your system administrator to allow larger uploads.",'arg1' => $this->_tpl_vars['ItemAddFromBrowser']['totalUploadSize'],'arg2' => $this->_tpl_vars['ItemAddFromBrowser']['maxFileSize']), $this);?>

    <?php endif; ?>
  </p>

  <?php unset($this->_sections['uploadBoxes']);
$this->_sections['uploadBoxes']['name'] = 'uploadBoxes';
$this->_sections['uploadBoxes']['loop'] = is_array($_loop=$this->_tpl_vars['form']['uploadBoxCount']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['uploadBoxes']['show'] = true;
$this->_sections['uploadBoxes']['max'] = $this->_sections['uploadBoxes']['loop'];
$this->_sections['uploadBoxes']['step'] = 1;
$this->_sections['uploadBoxes']['start'] = $this->_sections['uploadBoxes']['step'] > 0 ? 0 : $this->_sections['uploadBoxes']['loop']-1;
if ($this->_sections['uploadBoxes']['show']) {
    $this->_sections['uploadBoxes']['total'] = $this->_sections['uploadBoxes']['loop'];
    if ($this->_sections['uploadBoxes']['total'] == 0)
        $this->_sections['uploadBoxes']['show'] = false;
} else
    $this->_sections['uploadBoxes']['total'] = 0;
if ($this->_sections['uploadBoxes']['show']):

            for ($this->_sections['uploadBoxes']['index'] = $this->_sections['uploadBoxes']['start'], $this->_sections['uploadBoxes']['iteration'] = 1;
                 $this->_sections['uploadBoxes']['iteration'] <= $this->_sections['uploadBoxes']['total'];
                 $this->_sections['uploadBoxes']['index'] += $this->_sections['uploadBoxes']['step'], $this->_sections['uploadBoxes']['iteration']++):
$this->_sections['uploadBoxes']['rownum'] = $this->_sections['uploadBoxes']['iteration'];
$this->_sections['uploadBoxes']['index_prev'] = $this->_sections['uploadBoxes']['index'] - $this->_sections['uploadBoxes']['step'];
$this->_sections['uploadBoxes']['index_next'] = $this->_sections['uploadBoxes']['index'] + $this->_sections['uploadBoxes']['step'];
$this->_sections['uploadBoxes']['first']      = ($this->_sections['uploadBoxes']['iteration'] == 1);
$this->_sections['uploadBoxes']['last']       = ($this->_sections['uploadBoxes']['iteration'] == $this->_sections['uploadBoxes']['total']);
?>
  <?php $this->assign('iteration', $this->_sections['uploadBoxes']['iteration']); ?>
  <div<?php if ($this->_tpl_vars['iteration'] > $this->_tpl_vars['form']['visibleBoxCount']): ?> id="fileDiv_<?php echo $this->_tpl_vars['iteration']; ?>
" style="display:none"<?php endif; ?>>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'File'), $this);?>
 </h4>
    <input type="file" size="60" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[".($this->_tpl_vars['iteration'])."]"), $this);?>
"/>

    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Caption'), $this);?>
 </h4>
    <textarea rows="2" cols="60" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[caption][".($this->_tpl_vars['iteration'])."]"), $this);?>
"></textarea>
  </div>
  <?php endfor; endif; ?>

  <?php if ($this->_tpl_vars['form']['uploadBoxCount'] > $this->_tpl_vars['form']['visibleBoxCount']): ?>
    <script type="text/javascript">
      // <![CDATA[
      document.write('<a id="addOne" href="javascript:addOne()"><?php echo $this->_reg_objects['g'][0]->text(array('text' => "More.."), $this);?>
</a>');
      var fileIndex = <?php echo $this->_tpl_vars['form']['visibleBoxCount']; ?>
;
      <?php echo '
      function addOne() {
	var link = document.getElementById(\'addOne\');
	link.blur();
	document.getElementById(\'fileDiv_\' + ++fileIndex).style.display = \'block\';
	if (fileIndex >= ';  echo $this->_tpl_vars['form']['uploadBoxCount'];  echo ') {
	  link.style.display = \'none\';
	}
      }
      // ]]>
    '; ?>
</script>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Set item titles from:"), $this);?>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[set][title]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ItemAddFromBrowser']['titleList'],'selected' => $this->_tpl_vars['form']['set']['title']), $this);?>

  </select>
  &nbsp;

  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Assign caption to:"), $this);?>

  <input type="checkbox" id="cbSummary"<?php if (! empty ( $this->_tpl_vars['form']['set']['summary'] )): ?> checked="checked"<?php endif; ?>
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[set][summary]"), $this);?>
"/>
  <label for="cbSummary"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Summary'), $this);?>
 </label>
  &nbsp;

  <input type="checkbox" id="cbDesc"<?php if (! empty ( $this->_tpl_vars['form']['set']['description'] )): ?> checked="checked"<?php endif; ?>
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[set][description]"), $this);?>
"/>
  <label for="cbDesc"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Description'), $this);?>
 </label>
</div>

<?php $_from = $this->_tpl_vars['ItemAdd']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['option']['file']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['option']['l10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][addFromBrowser]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add Items'), $this);?>
"/>
</div>
<?php endif; ?>