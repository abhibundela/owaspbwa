<?php /* Smarty version 2.6.10, created on 2011-04-20 13:26:14
         compiled from gallery:modules/slideshow/templates/Slideshow.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'markup', 'gallery:modules/slideshow/templates/Slideshow.tpl', 25, false),)), $this); ?>
<?php if (empty ( $this->_tpl_vars['SlideShow']['itemList'] )): ?>
<div id="gsContent">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This album has no photos to show in a slideshow."), $this);?>

  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['SlideShow']['item']['id'])), $this);?>
">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Back to Album View'), $this);?>

  </a>
</div>
<?php else: ?>

<?php $_from = $this->_tpl_vars['SlideShow']['itemList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['it']):
?>
<div style="display:none">
  <?php $_from = $this->_tpl_vars['it']['sources']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['source']):
?>
    <a id="item_<?php echo $this->_tpl_vars['i']; ?>
_<?php echo $this->_tpl_vars['j']; ?>
"
     href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.DownloadItem",'arg2' => "itemId=".($this->_tpl_vars['source']['id']),'arg3' => "serialNumber=".($this->_tpl_vars['source']['serialNumber'])), $this);?>
"></a>
  <?php endforeach; endif; unset($_from); ?>

  <a id="href_<?php echo $this->_tpl_vars['i']; ?>
" href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['it']['id'])), $this);?>
"></a>
  <span id="title_<?php echo $this->_tpl_vars['i']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['it']['data']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
  <span id="summary_<?php echo $this->_tpl_vars['i']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['it']['data']['summary'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
  <span id="date_<?php echo $this->_tpl_vars['i']; ?>
">
    <?php if (isset ( $this->_tpl_vars['it']['exif']['DateTime'] )): ?>
      <?php echo $this->_tpl_vars['it']['exif']['DateTime']['title']; ?>
: <?php echo $this->_tpl_vars['it']['exif']['DateTime']['value']; ?>

    <?php else: ?>
      <?php ob_start();  echo $this->_reg_objects['g'][0]->date(array('timestamp' => $this->_tpl_vars['it']['data']['modificationTimestamp']), $this); $this->_smarty_vars['capture']['date'] = ob_get_contents(); ob_end_clean(); ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Date: %s",'arg1' => $this->_smarty_vars['capture']['date']), $this);?>

    <?php endif; ?>
  </span>
  <span id="description_<?php echo $this->_tpl_vars['i']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['it']['data']['description'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)); ?>
</span>
</div>
<?php endforeach; endif; unset($_from); ?>

<script type="text/JavaScript">
  // <![CDATA[
  var image = new Image(), timer, iDelay = 15000, iDir = 1, iSize = 0;
  var bPause = 0, bShowText = 0, bShowTools = 1;
  var linkStop, spanPause, spanText, toolText;
  var toolBar, textBanner, spanTitle, spanSummary, spanDate, spanDescription;
  var index = <?php echo $this->_tpl_vars['SlideShow']['start']; ?>
, count = <?php echo $this->_tpl_vars['SlideShow']['count']; ?>
;
  var is_cached = new Array(count), item_map = new Array(count);
  for (i=0; i < count; i++) is_cached[i] = new Array(0,0,0,0,0,0);
  <?php $_from = $this->_tpl_vars['SlideShow']['itemList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['i'] => $this->_tpl_vars['it']):
?>
  item_map[<?php echo $this->_tpl_vars['i']; ?>
] = new Array(<?php $_from = $this->_tpl_vars['it']['sizeClassMap']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['j'] => $this->_tpl_vars['idx']):
 if ($this->_tpl_vars['j'] > 0): ?>,<?php endif;  echo $this->_tpl_vars['idx'];  endforeach; endif; unset($_from); ?>);
  <?php endforeach; endif; unset($_from); ?>
  <?php echo '
  function random_int(i) {
    return Math.floor(i*(Math.random()%1));
  }
  var random_order = new Array(count);
  for (i=0; i < count; i++) random_order[i] = i;
  for (i=count-1; i > 0; i--) {
    j = random_int(i+1);
    k = random_order[i];
    random_order[i] = random_order[j];
    random_order[j] = k;
  }
  function move_index(by) {
    if (iDir==0/*random*/) {
      random_index = 0;
      for (i = 0; i < count; i++)
	if (random_order[i] == index) {
	  random_index = i;
	  break;
	}
      return random_order[(random_index+by+count)%count];
    }
    else return (index+(by*iDir)+count)%count;
  }
  function preload(i) {
    if (!is_cached[i][iSize]) {
      is_cached[i][iSize] = 1;
      image.src = document.getElementById(\'item_\'+i+\'_\'+item_map[i][iSize]).href;
    }
  }
  function slide_view_start() {
    if (bShowText) show_text();
    preload(move_index(1));
    if (timer) { clearInterval(timer); clearTimeout(timer); } // Avoid extra timers in opera
    if (!bPause) timer = setTimeout(\'goto_next_photo()\', iDelay);
  }
  function goto_next_photo() {
    index = move_index(1);
    if (bCanBlend) apply_filter();
    document.images.slide.src =
      document.getElementById(\'item_\'+index+\'_\'+item_map[index][iSize]).href;
    linkStop.href = document.getElementById(\'href_\'+index).href;
    if (bCanBlend) document.images.slide.filters[0].Play();
  }
  function show_text() {
    spanTitle.innerHTML = document.getElementById(\'title_\'+index).innerHTML;
    spanSummary.innerHTML = document.getElementById(\'summary_\'+index).innerHTML;
    spanDate.innerHTML = document.getElementById(\'date_\'+index).innerHTML;
    spanDescription.innerHTML = document.getElementById(\'description_\'+index).innerHTML;
  }
  function text_onoff() {
    bShowText = bShowText ? 0 : 1;
    if (bShowText) show_text(); else {
      spanTitle.innerHTML = spanSummary.innerHTML =
      spanDate.innerHTML = spanDescription.innerHTML = \'\';
    }
    textBanner.style.display = bShowText ? \'block\' : \'none\';
    spanText.innerHTML = bShowText ? '; ?>
'<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Hide More Info','forJavascript' => '1'), $this);?>
'
				   : '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show More Info','forJavascript' => '1'), $this);?>
'; <?php echo '
  }
  function start_stop() {
    bPause = bPause ? 0 : 1;
    if (bPause) clearTimeout(timer);
    else goto_next_photo();
    spanPause.innerHTML = bPause ? '; ?>
'<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Resume','forJavascript' => '1'), $this);?>
'
				 : '<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pause','forJavascript' => '1'), $this);?>
'; <?php echo '
  }
  function jump(by) {
    index = move_index(by);
    clearTimeout(timer);
    goto_next_photo();
  }
  function apply_filter() {
    f = filters[document.getElementById(\'filter\').selectedIndex];
    if (f == \'RANDOM\') f = filters[random_int(filters.length-1)];
    document.images.slide.style.filter = f;
    document.images.slide.filters[0].Apply();
  }
  function new_size(size) {
    iSize = size;
    jump(-1);
  }
  function new_order(direct) {
    iDir = direct;
  }
  function new_delay(delay) {
    iDelay = delay*1000;
    jump(-1);
  }
  function tools_onoff() {
    bShowTools = bShowTools ? 0 : 1;
    toolBar.style.display = bShowTools ? \'block\' : \'none\';
    toolText.innerHTML = bShowTools ? '; ?>
'<?php echo $this->_reg_objects['g'][0]->text(array('text' => "[-]",'forJavascript' => '1'), $this);?>
'
				    : '<?php echo $this->_reg_objects['g'][0]->text(array('text' => "[+]",'forJavascript' => '1'), $this);?>
'; <?php echo '
  }
  '; ?>

  // ]]>
</script>

<div style="float:left">
  <a onclick="tools_onoff();return false">
    <span id="tools" style="margin:0;padding:0">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "[-]"), $this);?>

    </span>
  </a>
</div>

<div id="gsContent" class="gcBorder1">
  <div id="toolbar" class="gbBlock gcBackground1">
    <a id="stop" href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.ShowItem",'arg2' => "itemId=".($this->_tpl_vars['SlideShow']['itemList'][$this->_tpl_vars['SlideShow']['start']]['id'])), $this);?>
">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Stop'), $this);?>

    </a>
    &nbsp;
    <a href="#" onclick="start_stop();return false">
      <span id="pause"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Pause'), $this);?>
</span>
    </a>
    &nbsp;
    <a href="#" onclick="jump(-2);return false">
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Back One Image'), $this);?>

    </a>
    &nbsp;
    <a href="#" onclick="text_onoff();return false">
      <span id="moreInfo"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Show More Info'), $this);?>
</span>
    </a>
    &nbsp;

    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "delay: "), $this);?>

    <select onchange="new_delay(this.value)">
      <option value="1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '1 seconds'), $this);?>
</option>
      <option value="3"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '3 seconds'), $this);?>
</option>
      <option value="5"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '5 seconds'), $this);?>
</option>
      <option value="10"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '10 seconds'), $this);?>
</option>
      <option selected="selected" value="15"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '15 seconds'), $this);?>
</option>
      <option value="20"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '20 seconds'), $this);?>
</option>
    </select>
    &nbsp;

    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "direction: "), $this);?>

    <select onchange="new_order(this.value)">
      <option value="1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'forward'), $this);?>
</option>
      <option value="-1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'reverse'), $this);?>
</option>
      <option value="0"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'random'), $this);?>
</option>
    </select>
    &nbsp;

    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "max size: "), $this);?>

    <select onchange="new_size(this.value)">
      <option value="0"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '320x320'), $this);?>
</option>
      <option value="1"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '640x640'), $this);?>
</option>
      <option value="2"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '800x800'), $this);?>
</option>
      <option value="3"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '1024x1024'), $this);?>
</option>
      <option value="4"><?php echo $this->_reg_objects['g'][0]->text(array('text' => '1280x1280'), $this);?>
</option>
      <option value="5"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'no limit'), $this);?>
</option>
    </select>
    &nbsp;

    <script type="text/JavaScript"><?php echo '
      // <![CDATA[
      if (bCanBlend) {
	document.write(\'&nbsp; ';  echo $this->_reg_objects['g'][0]->text(array('text' => "fade: ",'forJavascript' => '1'), $this); echo '<select id="filter">\');
	for (i = 0; i < filterNames.length; i++) {
	  document.write(\'<option>\'+filterNames[i]);
	}
	document.write(\'</select>\');
      }
      '; ?>

      // ]]>
    </script>
  </div>

  <div class="gbItemImage">
    <img id="slide" alt="" src=""/>
  </div>

  <div id="textBanner" class="gbBlock gcBackground1" style="display:none">
    <div class="giTitle" id="title"></div>
    <div class="giDescription" id="summary"></div>
    <div class="giInfo summary" id="date"></div>
    <div class="giInfo summary" id="description"></div>
  </div>
</div>

<script type="text/JavaScript">
  // <![CDATA[
  linkStop = document.getElementById('stop');
  spanPause = document.getElementById('pause');
  spanText = document.getElementById('moreInfo');
  toolText = document.getElementById('tools');
  toolBar = document.getElementById('toolbar');
  textBanner = document.getElementById('textBanner');
  spanTitle = document.getElementById('title');
  spanSummary = document.getElementById('summary');
  spanDate = document.getElementById('date');
  spanDescription = document.getElementById('description');
  document.images.slide.onload = slide_view_start;
  document.images.slide.onerror = goto_next_photo;
  document.images.slide.src =
  document.getElementById('item_<?php echo $this->_tpl_vars['SlideShow']['start']; ?>
_'+item_map[<?php echo $this->_tpl_vars['SlideShow']['start']; ?>
][iSize]).href;
  // ]]>
</script>
<?php endif; ?>