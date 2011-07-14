<?php /* Smarty version 2.6.10, created on 2011-04-20 13:19:09
         compiled from gallery:modules/core/templates/blocks/PhotoSizes.tpl */ ?>
<div class="<?php echo $this->_tpl_vars['class']; ?>
">
   <?php if (count ( $this->_tpl_vars['theme']['imageViews'] ) > 1): ?>
     <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Size: "), $this);?>

     <select onchange="<?php echo 'if (this.value) { newLocation = this.value; this.options[0].selected = true; location.href= newLocation; }'; ?>
">
     <?php unset($this->_sections['imageView']);
$this->_sections['imageView']['name'] = 'imageView';
$this->_sections['imageView']['loop'] = is_array($_loop=$this->_tpl_vars['theme']['imageViews']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['imageView']['show'] = true;
$this->_sections['imageView']['max'] = $this->_sections['imageView']['loop'];
$this->_sections['imageView']['step'] = 1;
$this->_sections['imageView']['start'] = $this->_sections['imageView']['step'] > 0 ? 0 : $this->_sections['imageView']['loop']-1;
if ($this->_sections['imageView']['show']) {
    $this->_sections['imageView']['total'] = $this->_sections['imageView']['loop'];
    if ($this->_sections['imageView']['total'] == 0)
        $this->_sections['imageView']['show'] = false;
} else
    $this->_sections['imageView']['total'] = 0;
if ($this->_sections['imageView']['show']):

            for ($this->_sections['imageView']['index'] = $this->_sections['imageView']['start'], $this->_sections['imageView']['iteration'] = 1;
                 $this->_sections['imageView']['iteration'] <= $this->_sections['imageView']['total'];
                 $this->_sections['imageView']['index'] += $this->_sections['imageView']['step'], $this->_sections['imageView']['iteration']++):
$this->_sections['imageView']['rownum'] = $this->_sections['imageView']['iteration'];
$this->_sections['imageView']['index_prev'] = $this->_sections['imageView']['index'] - $this->_sections['imageView']['step'];
$this->_sections['imageView']['index_next'] = $this->_sections['imageView']['index'] + $this->_sections['imageView']['step'];
$this->_sections['imageView']['first']      = ($this->_sections['imageView']['iteration'] == 1);
$this->_sections['imageView']['last']       = ($this->_sections['imageView']['iteration'] == $this->_sections['imageView']['total']);
?>
       <option value="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "imageViewsIndex=".($this->_sections['imageView']['index'])), $this);?>
"<?php if ($this->_sections['imageView']['index'] == $this->_tpl_vars['theme']['imageViewsIndex']): ?> selected="selected"<?php endif; ?>>
	 <?php if (empty ( $this->_tpl_vars['theme']['imageViews'][$this->_sections['imageView']['index']]['width'] )): ?>
	   <?php if (isset ( $this->_tpl_vars['theme']['imageViews'][$this->_sections['imageView']['index']]['isSource'] )): ?>
	     <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Source'), $this);?>

	   <?php else: ?>
	     <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Unknown'), $this);?>

	   <?php endif; ?>
	 <?php else: ?>
	   <?php echo $this->_reg_objects['g'][0]->text(array('text' => "%dx%d",'arg1' => $this->_tpl_vars['theme']['imageViews'][$this->_sections['imageView']['index']]['width'],'arg2' => $this->_tpl_vars['theme']['imageViews'][$this->_sections['imageView']['index']]['height']), $this);?>

	 <?php endif; ?>
       </option>
     <?php endfor; endif; ?>
     </select>
     <br/>
   <?php endif; ?>

   <?php if (! empty ( $this->_tpl_vars['theme']['sourceImage'] )): ?>
     <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Full size: "), $this);?>

     <?php ob_start(); ?>
       <?php if (empty ( $this->_tpl_vars['theme']['sourceImage']['width'] )): ?>
	 <?php echo $this->_tpl_vars['theme']['sourceImage']['itemTypeName']['0']; ?>

       <?php else: ?>
	 <?php echo $this->_reg_objects['g'][0]->text(array('text' => "%dx%d",'arg1' => $this->_tpl_vars['theme']['sourceImage']['width'],'arg2' => $this->_tpl_vars['theme']['sourceImage']['height']), $this);?>

       <?php endif; ?>
     <?php $this->_smarty_vars['capture']['fullSize'] = ob_get_contents(); ob_end_clean(); ?>
     <?php if (count ( $this->_tpl_vars['theme']['imageViews'] ) > 1): ?>
       <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['theme']['item']['id']),'arg3' => "imageViewsIndex=".($this->_tpl_vars['theme']['sourceImageViewIndex'])), $this);?>
">
	 <?php echo $this->_smarty_vars['capture']['fullSize']; ?>

       </a>
     <?php else: ?>
       <?php echo $this->_smarty_vars['capture']['fullSize']; ?>

     <?php endif; ?>
     <br/>
   <?php endif; ?>
</div>