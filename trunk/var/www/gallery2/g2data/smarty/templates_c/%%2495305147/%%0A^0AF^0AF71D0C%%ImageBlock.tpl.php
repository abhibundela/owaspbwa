<?php /* Smarty version 2.6.10, created on 2012-07-13 15:00:26
         compiled from gallery:modules/imageblock/templates/ImageBlock.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:modules/imageblock/templates/ImageBlock.tpl', 48, false),array('modifier', 'default', 'gallery:modules/imageblock/templates/ImageBlock.tpl', 70, false),)), $this); ?>
<?php $_from = $this->_tpl_vars['ImageBlockData']['blocks']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['block']):
?>
<div class="one-image">
  <?php if (! empty ( $this->_tpl_vars['block']['title'] )): ?>
    <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['block']['title']), $this);?>
 </h3>
  <?php endif; ?>

  <?php ob_start(); ?>
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['block']['id']),'forceFullUrl' => $this->_tpl_vars['ImageBlockData']['forceFullUrl']), $this);?>
" <?php echo '';  if (! empty ( $this->_tpl_vars['ImageBlockData']['linkTarget'] )):  echo 'target="';  echo $this->_tpl_vars['ImageBlockData']['linkTarget'];  echo '"';  endif;  echo ''; ?>
>
  <?php $this->_smarty_vars['capture']['link'] = ob_get_contents(); ob_end_clean(); ?>
  <?php if ($this->_tpl_vars['block']['item']['canContainChildren']): ?>
    <?php $this->assign('frameType', 'albumFrame'); ?>
  <?php else: ?>
    <?php $this->assign('frameType', 'itemFrame'); ?>
  <?php endif; ?>
  <?php if (array_key_exists ( 'maxSize' , $this->_tpl_vars['ImageBlockData'] )): ?>
    <?php $this->assign('maxSize', $this->_tpl_vars['ImageBlockData']['maxSize']); ?>
  <?php elseif (isset ( $this->_tpl_vars['ImageBlockData'][$this->_tpl_vars['frameType']] ) && $this->_tpl_vars['ImageBlockData'][$this->_tpl_vars['frameType']] != 'none'): ?>
    <?php $this->assign('maxSize', 120); ?>
  <?php else: ?>
    <?php $this->assign('maxSize', 150); ?>
  <?php endif; ?>
  <?php $this->assign('imageItem', $this->_tpl_vars['block']['item']); ?>
  <?php if (isset ( $this->_tpl_vars['block']['forceItem'] )):  $this->assign('imageItem', $this->_tpl_vars['block']['thumb']);  endif; ?>
  <?php if (isset ( $this->_tpl_vars['ImageBlockData'][$this->_tpl_vars['frameType']] )): ?>
    <?php $this->_tag_stack[] = array('container', array('type' => "imageframe.ImageFrame",'frame' => $this->_tpl_vars['ImageBlockData'][$this->_tpl_vars['frameType']],'width' => $this->_tpl_vars['block']['thumb']['width'],'height' => $this->_tpl_vars['block']['thumb']['height'],'maxSize' => $this->_tpl_vars['maxSize']), $this); $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
      <?php echo $this->_smarty_vars['capture']['link']; ?>

	<?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['imageItem'],'image' => $this->_tpl_vars['block']['thumb'],'id' => "%ID%",'class' => "%CLASS%",'maxSize' => $this->_tpl_vars['maxSize'],'forceFullUrl' => $this->_tpl_vars['ImageBlockData']['forceFullUrl']), $this);?>

      </a>
    <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->container($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

  <?php else: ?>
    <?php echo $this->_smarty_vars['capture']['link']; ?>

      <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['imageItem'],'image' => $this->_tpl_vars['block']['thumb'],'class' => 'giThumbnail','maxSize' => $this->_tpl_vars['maxSize'],'forceFullUrl' => $this->_tpl_vars['ImageBlockData']['forceFullUrl']), $this);?>

    </a>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['ImageBlockData']['show']['title'] ) && isset ( $this->_tpl_vars['block']['item']['title'] )): ?>
    <h4 class="giDescription">
      <?php echo ((is_array($_tmp=$this->_tpl_vars['block']['item']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>

    </h4>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['ImageBlockData']['show']['date'] ) || isset ( $this->_tpl_vars['ImageBlockData']['show']['views'] ) || isset ( $this->_tpl_vars['ImageBlockData']['show']['owner'] )): ?>
    <p class="giInfo">
      <?php if (isset ( $this->_tpl_vars['ImageBlockData']['show']['date'] )): ?>
      <span class="summary">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Date:"), $this);?>
 <?php echo $this->_reg_objects['g'][0]->date(array('timestamp' => $this->_tpl_vars['block']['item']['originationTimestamp']), $this);?>

      </span>
      <?php endif; ?>

      <?php if (isset ( $this->_tpl_vars['ImageBlockData']['show']['views'] )): ?>
      <span class="summary">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Views: %d",'arg1' => $this->_tpl_vars['block']['viewCount']), $this);?>

      </span>
      <?php endif; ?>

      <?php if (isset ( $this->_tpl_vars['ImageBlockData']['show']['owner'] )): ?>
      <span class="summary">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Owner: %s",'arg1' => ((is_array($_tmp=@$this->_tpl_vars['block']['owner']['fullName'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['block']['owner']['userName']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['block']['owner']['userName']))), $this);?>

      </span>
      <?php endif; ?>
    </p>
  <?php endif; ?>
 </div>
<?php endforeach; endif; unset($_from); ?>
