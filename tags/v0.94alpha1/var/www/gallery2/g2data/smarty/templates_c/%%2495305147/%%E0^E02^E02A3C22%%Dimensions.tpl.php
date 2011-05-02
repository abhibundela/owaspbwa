<?php /* Smarty version 2.6.10, created on 2011-04-17 15:16:12
         compiled from gallery:modules/core/templates/Dimensions.tpl */ ?>
<?php if ($this->_tpl_vars['callCount'] == 1):  echo '
<script type="text/javascript">
// <![CDATA[
var dim_timer = new Array();
function dim_keypress(i,w,e) {
  var key = window.event ? window.event.keyCode : e.which;
  var h = document.getElementById(w.id + \'_h\');
  if (((key >= 48 && key <= 57) || key == 8) && (w.value == h.value)) {
    clearTimeout(dim_timer[i]);
    dim_timer[i] = setTimeout(\'dim_copy("\' + w.id + \'")\', 500);
  }
}
function dim_keydown(i,w) {
  // IE only gets backspace via keydown..
  if (window.event && window.event.keyCode == 8) dim_keypress(i,w);
}
function dim_copy(id) {
  var w = document.getElementById(id), h = document.getElementById(id + \'_h\');
  h.value = w.value;
}
// ]]>
</script>
';  endif; ?>
<input type="text" id="<?php echo $this->_tpl_vars['formVarId']; ?>
" size="6" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => $this->_tpl_vars['formVar']), $this);?>
[width]"
 onkeypress="dim_keypress(<?php echo $this->_tpl_vars['callCount']; ?>
,this,event)" onkeydown="dim_keydown(<?php echo $this->_tpl_vars['callCount']; ?>
,this)"
 <?php if (isset ( $this->_tpl_vars['width'] )): ?>value="<?php echo $this->_tpl_vars['width']; ?>
"<?php endif; ?>/>
x
<input type="text" id="<?php echo $this->_tpl_vars['formVarId']; ?>
_h" size="6" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => $this->_tpl_vars['formVar']), $this);?>
[height]"
 <?php if (isset ( $this->_tpl_vars['height'] )): ?>value="<?php echo $this->_tpl_vars['height']; ?>
"<?php endif; ?>/>