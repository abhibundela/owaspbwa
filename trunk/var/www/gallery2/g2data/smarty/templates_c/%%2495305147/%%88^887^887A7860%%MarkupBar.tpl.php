<?php /* Smarty version 2.6.10, created on 2011-04-17 15:15:38
         compiled from gallery:modules/core/templates/MarkupBar.tpl */ ?>
<?php if ($this->_tpl_vars['theme']['markupType'] == 'bbcode'):  if (! empty ( $this->_tpl_vars['firstMarkupBar'] )): ?>
<script type="text/javascript"><?php echo '
  // <![CDATA[
  function openOrCloseTextElement(elementId, bbCodeElement, button) {
    var element = document.getElementById(elementId);
    if (!button.g2ToggleMode) {
      element.value = element.value + \'[\' + bbCodeElement + \']\';
      button.value = \'*\' + button.value;
    } else {
      element.value = element.value + \'[/\' + bbCodeElement + \']\';
      button.value = button.value.substring(1);
    }
    element.focus();
    button.g2ToggleMode = !button.g2ToggleMode;
  }

  function appendTextElement(elementId, bbCodeElement, button) {
    var element = document.getElementById(elementId);
    element.value = element.value + \'[\' + bbCodeElement + \']\';
    element.focus();
  }

  '; ?>

  function appendUrlElement(elementId, bbCodeElement) {
    var element = document.getElementById(elementId);
    var url = prompt('<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Enter a URL','forJavascript' => true), $this);?>
'), text = null;
    if (url != null) text = prompt('<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Enter some text describing the URL','forJavascript' => true), $this);?>
');
    if (text != null) element.value = element.value + '[url=' + url + ']' + text + '[/url]';
    element.focus();
  }

  function appendImageElement(elementId, bbCodeElement) {
    var element = document.getElementById(elementId);
    var url = prompt('<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Enter an image URL','forJavascript' => true), $this);?>
');
    if (url != null) element.value = element.value + '[img]' + url + '[/img]';
    element.focus();
  }
  // ]]>
</script>
<?php endif; ?>

<div class="gbMarkupBar">
  <input type="button" class="inputTypeButton"
  	 value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "B <!-- Button label for Bold -->"), $this);?>
"
	 onclick="openOrCloseTextElement('<?php echo $this->_tpl_vars['element']; ?>
', 'b', this)"
	 style="font-weight: bold;"/>
  <input type="button" class="inputTypeButton"
  	 value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "i <!-- Button label for italic -->"), $this);?>
"
	 onclick="openOrCloseTextElement('<?php echo $this->_tpl_vars['element']; ?>
', 'i', this)"
	 style="font-style: italic; padding-left: 1px; padding-right: 4px"/>
  <input type="button" class="inputTypeButton" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'list'), $this);?>
"
	 onclick="openOrCloseTextElement('<?php echo $this->_tpl_vars['element']; ?>
', 'list', this)"/>
  <input type="button" class="inputTypeButton" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'bullet'), $this);?>
"
	 onclick="appendTextElement('<?php echo $this->_tpl_vars['element']; ?>
', '*', this)"/>
  <input type="button" class="inputTypeButton" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'url'), $this);?>
"
	 onclick="appendUrlElement('<?php echo $this->_tpl_vars['element']; ?>
', this)"/>
  <input type="button" class="inputTypeButton" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'image'), $this);?>
"
	 onclick="appendImageElement('<?php echo $this->_tpl_vars['element']; ?>
', this)"/>
</div>
<?php endif; ?>