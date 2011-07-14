<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:16
         compiled from gallery:modules/core/templates/CreateThumbnailOption.tpl */ ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Thumbnails'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Gallery can create thumbnails at upload time, or create them the first time you want to see the thumbnail itself.  Either way, it will create the thumbnail once and save it, but if you create them at upload time it makes viewing albums for the first time go faster at the expense of a longer upload time."), $this);?>

  </p>

  <input type="checkbox" id="CreateThumbnail_cb" checked="checked"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[CreateThumbnailOption][createThumbnail]"), $this);?>
"/>
  <label for="CreateThumbnail_cb">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Create thumbnails now'), $this);?>

  </label>
</div>