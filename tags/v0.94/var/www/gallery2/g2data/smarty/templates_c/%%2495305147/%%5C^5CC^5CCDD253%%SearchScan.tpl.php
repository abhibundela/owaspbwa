<?php /* Smarty version 2.6.10, created on 2011-04-20 13:05:09
         compiled from gallery:modules/search/templates/SearchScan.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'gallery:modules/search/templates/SearchScan.tpl', 116, false),array('modifier', 'markup', 'gallery:modules/search/templates/SearchScan.tpl', 116, false),)), $this); ?>
<form id="SearchScan" action="<?php echo $this->_reg_objects['g'][0]->url(array(), $this);?>
" method="post">
  <div id="gsContent" class="gcBorder1">
    <div class="gbBlock gcBackground1">
      <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Search the Gallery'), $this);?>
 </h2>
    </div>

    <?php echo $this->_reg_objects['g'][0]->hiddenFormVars(array(), $this);?>

    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'controller'), $this);?>
" value="<?php echo $this->_tpl_vars['SearchScan']['controller']; ?>
"/>
    <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[formName]"), $this);?>
" value="SearchScan"/>

    <script type="text/javascript">
      // <![CDATA[
      function setCheck(val) {
	<?php $_from = $this->_tpl_vars['SearchScan']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleId'] => $this->_tpl_vars['moduleInfo']):
?>
	  <?php $_from = $this->_tpl_vars['moduleInfo']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['optionId'] => $this->_tpl_vars['optionInfo']):
?>
	    document.getElementById('cb_<?php echo $this->_tpl_vars['moduleId']; ?>
_<?php echo $this->_tpl_vars['optionId']; ?>
').checked = val;
	  <?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
      }

      function invertCheck() {
	var o;
	<?php $_from = $this->_tpl_vars['SearchScan']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleId'] => $this->_tpl_vars['moduleInfo']):
?>
	  <?php $_from = $this->_tpl_vars['moduleInfo']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['optionId'] => $this->_tpl_vars['optionInfo']):
?>
	    o = document.getElementById('cb_<?php echo $this->_tpl_vars['moduleId']; ?>
_<?php echo $this->_tpl_vars['optionId']; ?>
'); o.checked = !o.checked;
	  <?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
      }
      // ]]>
    </script>

    <div class="gbBlock">
      <input type="text" size="50"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[searchCriteria]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['searchCriteria']; ?>
"/>
      <script type="text/javascript">
        document.getElementById('SearchScan')['<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[searchCriteria]"), $this);?>
'].focus();
      </script>
      <input type="submit" class="inputTypeSubmit"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][search]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Search'), $this);?>
"/>

      <?php if (isset ( $this->_tpl_vars['form']['error']['searchCriteria']['missing'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter some text to search for!"), $this);?>

      </div>
      <?php endif; ?>

      <div style="margin: 0.5em 0">
	<?php $_from = $this->_tpl_vars['SearchScan']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleId'] => $this->_tpl_vars['moduleInfo']):
?>
	  <?php $_from = $this->_tpl_vars['moduleInfo']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['optionId'] => $this->_tpl_vars['optionInfo']):
?>
	  <input type="checkbox" id="cb_<?php echo $this->_tpl_vars['moduleId']; ?>
_<?php echo $this->_tpl_vars['optionId']; ?>
"
	   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[options][".($this->_tpl_vars['moduleId'])."][".($this->_tpl_vars['optionId'])."]"), $this);?>
"
	   <?php if (isset ( $this->_tpl_vars['form']['options'][$this->_tpl_vars['moduleId']][$this->_tpl_vars['optionId']] )): ?>checked="checked"<?php endif; ?>/>
	  <label for="cb_<?php echo $this->_tpl_vars['moduleId']; ?>
_<?php echo $this->_tpl_vars['optionId']; ?>
">
	    <?php echo $this->_tpl_vars['optionInfo']['description']; ?>

	  </label>
	  <?php endforeach; endif; unset($_from); ?>
	<?php endforeach; endif; unset($_from); ?>
      </div>

      <div>
	<a href="javascript:setCheck(1)"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Check All'), $this);?>
</a>
	&nbsp;
	<a href="javascript:setCheck(0)"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Uncheck All'), $this);?>
</a>
	&nbsp;
	<a href="javascript:invertCheck()"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invert'), $this);?>
</a>
      </div>
    </div>

    <?php $this->assign('resultCount', '0'); ?>
    <?php if (! empty ( $this->_tpl_vars['SearchScan']['searchResults'] )): ?>
    <?php $_from = $this->_tpl_vars['SearchScan']['searchResults']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['moduleId'] => $this->_tpl_vars['results']):
?>
      <?php $this->assign('resultCount', $this->_tpl_vars['resultCount']+$this->_tpl_vars['results']['count']); ?>

      <div class="gbBlock">
	<h4>
	  <?php echo $this->_tpl_vars['SearchScan']['modules'][$this->_tpl_vars['moduleId']]['name']; ?>

	  <?php if (( $this->_tpl_vars['results']['count'] > 0 )): ?>
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Results %d - %d",'arg1' => $this->_tpl_vars['results']['start'],'arg2' => $this->_tpl_vars['results']['end']), $this);?>

	  <?php endif; ?>
	  <?php if (( $this->_tpl_vars['results']['count'] > $this->_tpl_vars['results']['end'] )): ?>
	    <?php $this->assign('moduleId', $this->_tpl_vars['moduleId']); ?>
	    &nbsp;
	    <input type="submit" class="inputTypeSubmit"
	     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][showAll][".($this->_tpl_vars['moduleId'])."]"), $this);?>
"
	     value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Show all %d",'arg1' => $this->_tpl_vars['results']['count']), $this);?>
"/>
	  <?php endif; ?>
	</h4>

	<?php $this->assign('searchCriteria', $this->_tpl_vars['form']['searchCriteria']); ?>
	<?php if (( sizeof ( $this->_tpl_vars['results']['results'] ) > 0 )): ?>
	  <table><tr>
	    <?php $_from = $this->_tpl_vars['results']['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['result']):
?>
	      <?php $this->assign('itemId', $this->_tpl_vars['result']['itemId']); ?>
	      <td class="<?php if ($this->_tpl_vars['SearchScan']['items'][$this->_tpl_vars['itemId']]['canContainChildren']): ?>gbItemAlbum<?php else: ?>gbItemImage<?php endif; ?>"
		style="width: 10%">
		<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['itemId'])), $this);?>
">
		<?php if (isset ( $this->_tpl_vars['SearchScan']['thumbnails'][$this->_tpl_vars['itemId']] )): ?>
		  <?php echo $this->_reg_objects['g'][0]->image(array('item' => $this->_tpl_vars['SearchScan']['items'][$this->_tpl_vars['itemId']],'image' => $this->_tpl_vars['SearchScan']['thumbnails'][$this->_tpl_vars['itemId']],'class' => 'giThumbnail'), $this);?>

		<?php else: ?>
		<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No thumbnail'), $this);?>

		<?php endif; ?>
		</a>
		<ul class="giInfo">
		  <?php $_from = $this->_tpl_vars['result']['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
		    <?php if (isset ( $this->_tpl_vars['field']['value'] )): ?>
		    <li>
		      <span class="ResultKey"><?php echo $this->_tpl_vars['field']['key']; ?>
:</span>
		      <span class="ResultData"><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['field']['value'])) ? $this->_run_mod_handler('default', true, $_tmp, "&nbsp;") : smarty_modifier_default($_tmp, "&nbsp;")))) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
		    </li>
		    <?php endif; ?>
		  <?php endforeach; endif; unset($_from); ?>
		</ul>
	      </td>
	    <?php endforeach; endif; unset($_from); ?>
	  </tr></table>
	  <script type="text/javascript">
	    search_HighlightResults('<?php echo $this->_tpl_vars['searchCriteria']; ?>
');
	  </script>
	<?php else: ?>
	  <p class="giDescription">
	    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No results found for'), $this);?>
 '<?php echo $this->_tpl_vars['form']['searchCriteria']; ?>
'
	  </p>
	<?php endif; ?>
      </div>
    <?php endforeach; endif; unset($_from); ?>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['resultCount'] > 0 && $this->_tpl_vars['SearchScan']['slideshowAvailable']): ?>
    <div class="gbBlock gcBackground1">
      <input type="submit" class="inputTypeSubmit"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][slideshow]"), $this);?>
"
       value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'View these results in a slideshow'), $this);?>
"/>
    </div>
    <?php endif; ?>
  </div>
</form>