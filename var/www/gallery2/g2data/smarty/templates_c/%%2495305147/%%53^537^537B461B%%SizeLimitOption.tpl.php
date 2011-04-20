<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:12
         compiled from gallery:modules/sizelimit/templates/SizeLimitOption.tpl */ ?>
<script type="text/javascript">
  // <![CDATA[
  function SetSizeLimitOption_toggleXY() {
    var frm = document.getElementById('itemAdminForm');
    frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensions][width]"), $this);?>
"].disabled =
      !frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensionChoice]"), $this);?>
"][1].checked;
    frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensions][height]"), $this);?>
"].disabled =
      !frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensionChoice]"), $this);?>
"][1].checked;
  }
  function SetSizeLimitOption_toggleSize() {
    var frm = document.getElementById('itemAdminForm');
    frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][filesize]"), $this);?>
"].disabled =
     !frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][sizeChoice]"), $this);?>
"][1].checked;
  }
  // ]]>
</script>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Define picture size limit'), $this);?>
 </h3>

  <div style="margin: 0.5em 0">
    <div style="font-weight: bold">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Maximum dimensions of full sized images'), $this);?>

    </div>
    <input type="radio" id="SizeLimit_DimNone" onclick="SetSizeLimitOption_toggleXY()"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensionChoice]"), $this);?>
" value="unlimited"
     <?php if ($this->_tpl_vars['SizeLimitOption']['dimensionChoice'] == 'unlimited'): ?>checked="checked"<?php endif; ?>/>
    <label for="SizeLimit_DimNone">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Limits'), $this);?>

    </label>
    <br/>
    <input type="radio" onclick="SetSizeLimitOption_toggleXY()"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensionChoice]"), $this);?>
" value="explicit"
     <?php if ($this->_tpl_vars['SizeLimitOption']['dimensionChoice'] == 'explicit'): ?>checked="checked"<?php endif; ?>/>
    <?php echo $this->_reg_objects['g'][0]->dimensions(array('formVar' => "form[SizeLimitOption][dimensions]",'width' => $this->_tpl_vars['SizeLimitOption']['width'],'height' => $this->_tpl_vars['SizeLimitOption']['height']), $this);?>


    <?php if ($this->_tpl_vars['SizeLimitOption']['dimensionChoice'] == 'unlimited'): ?>
    <script type="text/javascript">
      var frm = document.getElementById('itemAdminForm');
      frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensions][width]"), $this);?>
"].disabled = true;
      frm.elements["<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][dimensions][height]"), $this);?>
"].disabled = true;
    </script>
    <?php endif; ?>

    <?php if (! empty ( $this->_tpl_vars['form']['error']['SizeLimitOption']['dim']['missing'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must specify at least one of the dimensions'), $this);?>

    </div>
    <?php endif; ?>
  </div>

  <div style="margin: 0.5em 0">
    <div style="font-weight: bold">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Maximum file size of full sized images in kilobytes'), $this);?>

    </div>
    <input type="radio" id="SizeLimit_SizeNone" onclick="SetSizeLimitOption_toggleSize()"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][sizeChoice]"), $this);?>
" value="unlimited"
     <?php if ($this->_tpl_vars['SizeLimitOption']['sizeChoice'] == 'unlimited'): ?>checked="checked"<?php endif; ?>/>
    <label for="SizeLimit_SizeNone">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No Limits'), $this);?>

    </label>
    <br/>
    <input type="radio" onclick="SetSizeLimitOption_toggleSize()"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][sizeChoice]"), $this);?>
" value="explicit"
     <?php if ($this->_tpl_vars['SizeLimitOption']['sizeChoice'] == 'explicit'): ?>checked="checked"<?php endif; ?>/>
    <input type="text" size="7" maxlength="6"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][filesize]"), $this);?>
"
	   value="<?php echo $this->_tpl_vars['SizeLimitOption']['filesize']; ?>
"
     <?php if ($this->_tpl_vars['SizeLimitOption']['sizeChoice'] != 'explicit'): ?>disabled="disabled"<?php endif; ?>/>

    <?php if (! empty ( $this->_tpl_vars['form']['error']['SizeLimitOption']['filesize']['invalid'] )): ?>
    <div class="giError">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a number (greater than zero)"), $this);?>

    </div>
    <?php endif; ?>
  </div>

  <input type="checkbox" id="SizeLimit_KeepOriginal"
	 name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][keepOriginal]"), $this);?>
"
   <?php if ($this->_tpl_vars['SizeLimitOption']['keepOriginal']): ?> checked="checked"<?php endif; ?>/>
  <label for="SizeLimit_KeepOriginal">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Keep original image?"), $this);?>

  </label>
  <br/>
  <input type="checkbox" id="SizeLimit_ApplyToDescendents"
	 name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[SizeLimitOption][applyToDescendents]"), $this);?>
"/>
  <label for="SizeLimit_ApplyToDescendents">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Check here to apply size limits to the pictures in this album and all subalbums'), $this);?>

  </label>
  <blockquote><p>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Checking this option will rebuild pictures according to appropriate limits'), $this);?>

  </p></blockquote>
  <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'sizelimit','text' => 'Use these size limits in all subalbums'), $this);?>

  <blockquote><p>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Checking this option will set same picture size limits in all subalbums'), $this);?>

  </p></blockquote>
</div>