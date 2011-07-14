<?php /* Smarty version 2.6.10, created on 2011-04-20 13:19:09
         compiled from gallery:modules/exif/templates/blocks/ExifInfo.tpl */ ?>
<?php if (empty ( $this->_tpl_vars['item'] )): ?> <?php $this->assign('item', $this->_tpl_vars['theme']['item']); ?> <?php endif; ?>

<?php echo $this->_reg_objects['g'][0]->callback(array('type' => "exif.LoadExifInfo",'itemId' => $this->_tpl_vars['item']['id']), $this);?>


<?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
<div class="<?php echo $this->_tpl_vars['class']; ?>
">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Photo Properties'), $this);?>
 </h3>

  <?php if (isset ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['mode'] )): ?>
  <div>
    <?php if (( $this->_tpl_vars['block']['exif']['LoadExifInfo']['mode'] == 'summary' )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'summary'), $this);?>

    <?php else: ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=exif.SwitchDetailMode",'arg2' => "mode=summary",'arg3' => "return=true"), $this);?>
">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'summary'), $this);?>

      </a>
    <?php endif; ?>

    <?php if (( $this->_tpl_vars['block']['exif']['LoadExifInfo']['mode'] == 'detailed' )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'details'), $this);?>

    <?php else: ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=exif.SwitchDetailMode",'arg2' => "mode=detailed",'arg3' => "return=true"), $this);?>
">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'details'), $this);?>

      </a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <?php if (! empty ( $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'] )): ?>
  <table class="gbDataTable">
    <?php unset($this->_sections['outer']);
$this->_sections['outer']['name'] = 'outer';
$this->_sections['outer']['loop'] = is_array($_loop=$this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['outer']['step'] = ((int)2) == 0 ? 1 : (int)2;
$this->_sections['outer']['show'] = true;
$this->_sections['outer']['max'] = $this->_sections['outer']['loop'];
$this->_sections['outer']['start'] = $this->_sections['outer']['step'] > 0 ? 0 : $this->_sections['outer']['loop']-1;
if ($this->_sections['outer']['show']) {
    $this->_sections['outer']['total'] = min(ceil(($this->_sections['outer']['step'] > 0 ? $this->_sections['outer']['loop'] - $this->_sections['outer']['start'] : $this->_sections['outer']['start']+1)/abs($this->_sections['outer']['step'])), $this->_sections['outer']['max']);
    if ($this->_sections['outer']['total'] == 0)
        $this->_sections['outer']['show'] = false;
} else
    $this->_sections['outer']['total'] = 0;
if ($this->_sections['outer']['show']):

            for ($this->_sections['outer']['index'] = $this->_sections['outer']['start'], $this->_sections['outer']['iteration'] = 1;
                 $this->_sections['outer']['iteration'] <= $this->_sections['outer']['total'];
                 $this->_sections['outer']['index'] += $this->_sections['outer']['step'], $this->_sections['outer']['iteration']++):
$this->_sections['outer']['rownum'] = $this->_sections['outer']['iteration'];
$this->_sections['outer']['index_prev'] = $this->_sections['outer']['index'] - $this->_sections['outer']['step'];
$this->_sections['outer']['index_next'] = $this->_sections['outer']['index'] + $this->_sections['outer']['step'];
$this->_sections['outer']['first']      = ($this->_sections['outer']['iteration'] == 1);
$this->_sections['outer']['last']       = ($this->_sections['outer']['iteration'] == $this->_sections['outer']['total']);
?>
    <tr>
      <?php unset($this->_sections['inner']);
$this->_sections['inner']['name'] = 'inner';
$this->_sections['inner']['loop'] = is_array($_loop=$this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['inner']['start'] = (int)$this->_sections['outer']['index'];
$this->_sections['inner']['max'] = (int)2;
$this->_sections['inner']['show'] = true;
if ($this->_sections['inner']['max'] < 0)
    $this->_sections['inner']['max'] = $this->_sections['inner']['loop'];
$this->_sections['inner']['step'] = 1;
if ($this->_sections['inner']['start'] < 0)
    $this->_sections['inner']['start'] = max($this->_sections['inner']['step'] > 0 ? 0 : -1, $this->_sections['inner']['loop'] + $this->_sections['inner']['start']);
else
    $this->_sections['inner']['start'] = min($this->_sections['inner']['start'], $this->_sections['inner']['step'] > 0 ? $this->_sections['inner']['loop'] : $this->_sections['inner']['loop']-1);
if ($this->_sections['inner']['show']) {
    $this->_sections['inner']['total'] = min(ceil(($this->_sections['inner']['step'] > 0 ? $this->_sections['inner']['loop'] - $this->_sections['inner']['start'] : $this->_sections['inner']['start']+1)/abs($this->_sections['inner']['step'])), $this->_sections['inner']['max']);
    if ($this->_sections['inner']['total'] == 0)
        $this->_sections['inner']['show'] = false;
} else
    $this->_sections['inner']['total'] = 0;
if ($this->_sections['inner']['show']):

            for ($this->_sections['inner']['index'] = $this->_sections['inner']['start'], $this->_sections['inner']['iteration'] = 1;
                 $this->_sections['inner']['iteration'] <= $this->_sections['inner']['total'];
                 $this->_sections['inner']['index'] += $this->_sections['inner']['step'], $this->_sections['inner']['iteration']++):
$this->_sections['inner']['rownum'] = $this->_sections['inner']['iteration'];
$this->_sections['inner']['index_prev'] = $this->_sections['inner']['index'] - $this->_sections['inner']['step'];
$this->_sections['inner']['index_next'] = $this->_sections['inner']['index'] + $this->_sections['inner']['step'];
$this->_sections['inner']['first']      = ($this->_sections['inner']['iteration'] == 1);
$this->_sections['inner']['last']       = ($this->_sections['inner']['iteration'] == $this->_sections['inner']['total']);
?>
      <td class="gbEven">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'][$this->_sections['inner']['index']]['title']), $this);?>

      </td>
      <td class="gbOdd">
	<?php echo $this->_tpl_vars['block']['exif']['LoadExifInfo']['exifData'][$this->_sections['inner']['index']]['value']; ?>

      </td>
      <?php endfor; endif; ?>
    </tr>
    <?php endfor; endif; ?>
  </table>
  <?php endif; ?>
</div>
<?php endif; ?>