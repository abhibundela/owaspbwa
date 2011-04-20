<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:12
         compiled from gallery:modules/core/templates/ItemEditAlbum.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/core/templates/ItemEditAlbum.tpl', 15, false),array('function', 'counter', 'gallery:modules/core/templates/ItemEditAlbum.tpl', 81, false),array('function', 'cycle', 'gallery:modules/core/templates/ItemEditAlbum.tpl', 83, false),)), $this); ?>
<div class="gbBlock">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Sort order'), $this);?>
 </h2>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This sets the sort order for the album.  This applies to all current items, and any newly added items."), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[orderBy]"), $this);?>
" onchange="pickOrder()">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ItemEditAlbum']['orderByList'],'selected' => $this->_tpl_vars['form']['orderBy']), $this);?>

  </select>
  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[orderDirection]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ItemEditAlbum']['orderDirectionList'],'selected' => $this->_tpl_vars['form']['orderDirection']), $this);?>

  </select>
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'with'), $this);?>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[presort]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ItemEditAlbum']['presortList'],'selected' => $this->_tpl_vars['form']['presort']), $this);?>

  </select><br/>
  <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'sort','text' => 'Apply to all subalbums'), $this);?>

  <script type="text/javascript">
    // <![CDATA[
    function pickOrder() {
      var list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[orderBy]"), $this);?>
';
      var frm = document.getElementById('itemAdminForm');
      var index = frm.elements[list].selectedIndex;
      list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[orderDirection]"), $this);?>
';
      frm.elements[list].disabled = (index <= 1) ?1:0;
      list = '<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[presort]"), $this);?>
';
      frm.elements[list].disabled = (index <= 1) ?1:0;
    }
    pickOrder();
    // ]]>
  </script>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Theme'), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Choose a theme for this album. (The way the album is arranged on the page)"), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[theme]"), $this);?>
">
    <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['ItemEditAlbum']['themeList'],'selected' => $this->_tpl_vars['form']['theme']), $this);?>

  </select><br/>
  <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'theme','text' => 'Use this theme in all subalbums'), $this);?>

</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Thumbnails'), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => " Every item requires a thumbnail. Set the default size in pixels here."), $this);?>

  </p>

  <input type="text" size="6"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[thumbnail][size]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['thumbnail']['size']; ?>
"/>

  <?php if (! empty ( $this->_tpl_vars['form']['error']['thumbnail']['size']['invalid'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a number (greater than zero)"), $this);?>

  </div>
  <?php endif; ?>
  <br/>
  <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'thumbnail','text' => 'Use this thumbnail size in all subalbums'), $this);?>

</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Resized Images'), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Each item in your album can have multiple sizes. Define the default sizes here."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <th align="center"> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Active'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Target Size (pixels)"), $this);?>
 </th>
  </tr>
  <?php echo smarty_function_counter(array('start' => 0,'assign' => 'index'), $this);?>

  <?php $_from = $this->_tpl_vars['form']['resizes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['resize']):
?>
  <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
    <td align="center">
      <input type="checkbox"<?php if ($this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['active']): ?> checked="checked"<?php endif; ?>
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[resizes][".($this->_tpl_vars['index'])."][active]"), $this);?>
"/>
    </td><td>
     <?php echo $this->_reg_objects['g'][0]->dimensions(array('formVar' => "form[resizes][".($this->_tpl_vars['index'])."]",'width' => $this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['width'],'height' => $this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['height']), $this);?>

    </td>
  </tr>

  <?php if (! empty ( $this->_tpl_vars['form']['error']['resizes'][$this->_tpl_vars['index']]['size']['missing'] )): ?>
  <tr><td colspan="2" class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a valid size'), $this);?>

  </td></tr>
  <?php endif; ?>
  <?php if (! empty ( $this->_tpl_vars['form']['error']['resizes'][$this->_tpl_vars['index']]['size']['invalid'] )): ?>
  <tr><td colspan="2" class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a number (greater than zero)"), $this);?>

  </td></tr>
  <?php endif; ?>
  <?php echo smarty_function_counter(array(), $this);?>

  <?php endforeach; endif; unset($_from); ?>
  </table>
  <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'resizes','text' => 'Use these target sizes in all subalbums'), $this);?>

</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recreate thumbnails and resizes'), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The thumbnail and resized image settings are for all new items. To apply these settings to all the items in your album, check the appropriate box."), $this);?>

  </p>

  <input type="checkbox" id="cbRecreateThumbs"<?php if ($this->_tpl_vars['form']['recreateThumbnails']): ?> checked="checked"<?php endif; ?>
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[recreateThumbnails]"), $this);?>
"/>
  <label for="cbRecreateThumbs">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recreate thumbnails'), $this);?>

  </label>
  <br/>

  <input type="checkbox" id="cbRecreateResizes"<?php if ($this->_tpl_vars['form']['recreateResizes']): ?> checked="checked"<?php endif; ?>
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[recreateResizes]"), $this);?>
"/>
  <label for="cbRecreateResizes">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Recreate resized images'), $this);?>

  </label>
</div>

<?php $_from = $this->_tpl_vars['ItemEdit']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['option']['file']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['option']['l10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][undo]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>