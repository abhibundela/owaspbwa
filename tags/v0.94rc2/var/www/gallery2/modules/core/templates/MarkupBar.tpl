{*
 * $Revision: 1.12 $
 * If you want to customize this file, do not edit it directly since future upgrades
 * may overwrite it.  Instead, copy it into a new directory called "local" and edit that
 * version.  Gallery will look for that file first and use it if it exists.
 *}
{if $theme.markupType == 'bbcode'}
{if !empty($firstMarkupBar)}
<script type="text/javascript">{literal}
  // <![CDATA[
  function openOrCloseTextElement(elementId, bbCodeElement, button) {
    var element = document.getElementById(elementId);
    if (!button.g2ToggleMode) {
      element.value = element.value + '[' + bbCodeElement + ']';
      button.value = '*' + button.value;
    } else {
      element.value = element.value + '[/' + bbCodeElement + ']';
      button.value = button.value.substring(1);
    }
    element.focus();
    button.g2ToggleMode = !button.g2ToggleMode;
  }

  function appendTextElement(elementId, bbCodeElement, button) {
    var element = document.getElementById(elementId);
    element.value = element.value + '[' + bbCodeElement + ']';
    element.focus();
  }

  {/literal}
  function appendUrlElement(elementId, bbCodeElement) {ldelim}
    var element = document.getElementById(elementId);
    var url = prompt('{g->text text="Enter a URL" forJavascript=true}'), text = null;
    if (url != null) text = prompt('{g->text text="Enter some text describing the URL"
					     forJavascript=true}');
    if (text != null) element.value = element.value + '[url=' + url + ']' + text + '[/url]';
    element.focus();
  {rdelim}

  function appendImageElement(elementId, bbCodeElement) {ldelim}
    var element = document.getElementById(elementId);
    var url = prompt('{g->text text="Enter an image URL" forJavascript=true}');
    if (url != null) element.value = element.value + '[img]' + url + '[/img]';
    element.focus();
  {rdelim}
  // ]]>
</script>
{/if}

<div class="gbMarkupBar">
  <input type="button" class="inputTypeButton"
  	 value="{g->text text="B <!-- Button label for Bold -->"}"
	 onclick="openOrCloseTextElement('{$element}', 'b', this)"
	 style="font-weight: bold;"/>
  <input type="button" class="inputTypeButton"
  	 value="{g->text text="i <!-- Button label for italic -->"}"
	 onclick="openOrCloseTextElement('{$element}', 'i', this)"
	 style="font-style: italic; padding-left: 1px; padding-right: 4px"/>
  <input type="button" class="inputTypeButton" value="{g->text text="list"}"
	 onclick="openOrCloseTextElement('{$element}', 'list', this)"/>
  <input type="button" class="inputTypeButton" value="{g->text text="bullet"}"
	 onclick="appendTextElement('{$element}', '*', this)"/>
  <input type="button" class="inputTypeButton" value="{g->text text="url"}"
	 onclick="appendUrlElement('{$element}', this)"/>
  <input type="button" class="inputTypeButton" value="{g->text text="image"}"
	 onclick="appendImageElement('{$element}', this)"/>
</div>
{/if}
