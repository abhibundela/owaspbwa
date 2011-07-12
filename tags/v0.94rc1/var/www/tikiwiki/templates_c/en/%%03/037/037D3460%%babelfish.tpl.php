<?php /* Smarty version 2.6.14, created on 2011-04-22 02:21:45
         compiled from babelfish.tpl */ ?>


<?php if ($this->_tpl_vars['feature_babelfish'] == 'y' && $this->_tpl_vars['feature_babelfish_logo'] == 'y'): ?>

<?php $this->assign('links', 0);  unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['babelfish_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<?php $this->assign('links', $this->_tpl_vars['links']+1);  endfor; endif; ?>

<div id="babelfish" align="center">
<?php if ($this->_tpl_vars['links'] > 0): ?>
<table width="100%">
  <?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['babelfish_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
    <tr>
      <?php if ($this->_sections['i']['index'] == 0): ?>
        <td>
          <a href="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['href']; ?>
" target="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['target']; ?>
"> <?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['msg']; ?>
 </a>
        </td>
        <td rowspan="<?php echo $this->_sections['i']['total']; ?>
" align="right">
          <?php echo $this->_tpl_vars['babelfish_logo']; ?>

        </td>
      <?php else: ?>
        <td>
          <a href="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['href']; ?>
" target="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['target']; ?>
"><?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['msg']; ?>
</a>
        </td>
      <?php endif; ?>
    </tr>
  <?php endfor; endif; ?>
</table>
<?php else: ?>
<small><strong>Babelfish (debug): Fatal error</strong></small>
<?php endif; ?>
</div>

<?php elseif ($this->_tpl_vars['feature_babelfish'] == 'y' && $this->_tpl_vars['feature_babelfish_logo'] == 'n'): ?>

<div id="babelfish" align="center">
<?php if ($this->_tpl_vars['links'] > 0): ?>
<table width="100%">
  <?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['babelfish_links']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
  <tr><td align="center">
    <a href="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['href']; ?>
" target="<?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['target']; ?>
"><?php echo $this->_tpl_vars['babelfish_links'][$this->_sections['i']['index']]['msg']; ?>
</a>
  </td> </tr>
  <?php endfor; endif; ?>
</table>
<?php else: ?>
<small><strong>Babelfish (debug): Fatal error</strong></small>
<?php endif; ?>
</div>

<?php elseif ($this->_tpl_vars['feature_babelfish'] == 'n' && $this->_tpl_vars['feature_babelfish_logo'] == 'y'): ?>

<div id="babelfish" align="center">
  <?php echo $this->_tpl_vars['babelfish_logo']; ?>

</div>

<?php endif; ?>