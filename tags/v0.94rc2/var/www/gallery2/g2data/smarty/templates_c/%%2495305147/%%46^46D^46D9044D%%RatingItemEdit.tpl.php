<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:12
         compiled from gallery:modules/rating/templates/RatingItemEdit.tpl */ ?>
<div class="gbBlock">
	<h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Rating'), $this);?>
 </h4>
	<p class="giDescription">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "This will enable or disable ratings for this album and, optionally, for its subalbums.  You can use permissions to allow viewing or adding ratings for specific users or groups."), $this);?>

	</p>
	<input type="checkbox" id="rating.enabled" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[rating][enabled]"), $this);?>
"
   	<?php if ($this->_tpl_vars['form']['rating']['enabled']): ?> checked<?php endif; ?>/> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Enable rating for this album'), $this);?>

	<br/>
    <?php echo $this->_reg_objects['g'][0]->changeInDescendents(array('module' => 'rating','text' => "... and for all subalbums"), $this);?>

</div>