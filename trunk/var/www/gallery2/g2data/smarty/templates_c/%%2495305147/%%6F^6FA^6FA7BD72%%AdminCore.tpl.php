<?php /* Smarty version 2.6.10, created on 2012-07-13 15:00:49
         compiled from gallery:modules/core/templates/AdminCore.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'gallery:modules/core/templates/AdminCore.tpl', 51, false),array('function', 'cycle', 'gallery:modules/core/templates/AdminCore.tpl', 154, false),array('modifier', 'escape', 'gallery:modules/core/templates/AdminCore.tpl', 156, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'General Settings'), $this);?>
 </h2>
</div>

<?php if (! empty ( $this->_tpl_vars['status'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php if (isset ( $this->_tpl_vars['status']['saved'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Settings saved successfully'), $this);?>
 <br/>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['addedDir'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Added local upload directory successfully'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['removedDir'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Removed local upload directory successfully'), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['emailTestSuccess'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Test email sent successfully'), $this);?>

  <?php endif; ?>
</h2></div>
<?php endif;  if (! empty ( $this->_tpl_vars['form']['error'] )): ?>
<div class="gbBlock"><h2 class="giError">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There was a problem processing your request, see below for details."), $this);?>

</h2></div>
<?php endif;  if (isset ( $this->_tpl_vars['status']['emailTestError'] ) || isset ( $this->_tpl_vars['form']['emailTestError'] )): ?>
<div class="gbBlock"><h2 class="giError">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Error sending test email, see below for details."), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Language Settings'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Select language defaults for Gallery. Individual users can override this setting in their personal preferences or via the language selector block if available. Gallery will try to automatically detect the language preference of each user if the browser preference check is enabled."), $this);?>

  </p>

  <?php if (isset ( $this->_tpl_vars['AdminCore']['can']['translate'] )): ?>
  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Default language'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[default][language]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['languageList'],'selected' => $this->_tpl_vars['form']['default']['language']), $this);?>

      </select>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Check Browser Preference'), $this);?>

    </td><td>
      <input type="checkbox"<?php if ($this->_tpl_vars['form']['language']['useBrowserPref']): ?> checked="checked"<?php endif; ?>
	     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[language][useBrowserPref]"), $this);?>
"/>
    </td>
  </tr></table>
  <?php else: ?>
    <div class="giWarning">
      <?php ob_start(); ?>
	<a href="http://php.net/gettext"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'gettext'), $this);?>
</a>
      <?php $this->_smarty_vars['capture']['gettext'] = ob_get_contents(); ob_end_clean(); ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your webserver does not support localization.  Please instruct your system administrator to reconfigure PHP with the %s option enabled.",'arg1' => $this->_smarty_vars['capture']['gettext']), $this);?>

    </div>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Date Formats'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Specify how dates and times are displayed by Gallery.  See %sphp.net%s for details of how to enter a format string.  Note that the display of some tokens varies according to the active language.",'arg1' => "<a href=\"http://php.net/strftime\">",'arg2' => "</a>"), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <th>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Style'), $this);?>

    </th><th>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Format'), $this);?>

    </th><th>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Sample'), $this);?>

    </th></tr><tr><td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Date'), $this);?>

    </td><td>
      <input type="text" size="12"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[format][date]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['format']['date']; ?>
"/>
    </td><td>
      <?php echo $this->_reg_objects['g'][0]->date(array('format' => $this->_tpl_vars['form']['format']['date']), $this);?>

    </td></tr><tr><td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Time'), $this);?>

    </td><td>
      <input type="text" size="12"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[format][time]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['format']['time']; ?>
"/>
    </td><td>
      <?php echo $this->_reg_objects['g'][0]->date(array('format' => $this->_tpl_vars['form']['format']['time']), $this);?>

    </td></tr><tr><td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Date/Time"), $this);?>

    </td><td>
      <input type="text" size="12"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[format][datetime]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['format']['datetime']; ?>
"/>
    </td><td>
      <?php echo $this->_reg_objects['g'][0]->date(array('format' => $this->_tpl_vars['form']['format']['datetime']), $this);?>

    </td></tr>
  </table>
</div>

<?php if (isset ( $this->_tpl_vars['AdminCore']['can']['setPermissions'] )): ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Filesystem Permissions'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Specify the default permissions for files and directories that Gallery creates. This doesn't apply to files/directories that Gallery has already created."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'New directories'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[permissions][directory]"), $this);?>
">
	<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['AdminCore']['permissionsDirectoryList'],'selected' => $this->_tpl_vars['form']['permissions']['directory'],'output' => $this->_tpl_vars['AdminCore']['permissionsDirectoryList']), $this);?>

      </select>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'New files'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[permissions][file]"), $this);?>
">
	<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['AdminCore']['permissionsFileList'],'selected' => $this->_tpl_vars['form']['permissions']['file'],'output' => $this->_tpl_vars['AdminCore']['permissionsFileList']), $this);?>

      </select>
    </td>
  </tr></table>
</div>
<?php endif; ?>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Local Server Upload Paths'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Specify the legal directories on the local server where a user can store files and then upload them into Gallery using the <i>Upload from Local Server</i> feature.  The paths you enter here and all the files and directories under those paths will be available to any Gallery user who has upload privileges, so you should limit this to directories that won't contain sensitive data (eg. /tmp or /usr/ftp/incoming)"), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Path'), $this);?>
 </th>
    <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Action'), $this);?>
 </th>
  </tr>

  <?php $_from = $this->_tpl_vars['AdminCore']['localServerDirList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['index'] => $this->_tpl_vars['dir']):
?>
  <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
    <td>
      <?php echo ((is_array($_tmp=$this->_tpl_vars['dir'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

    </td><td>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=core.AdminCore",'arg2' => "form[action][removeUploadLocalServerDir]=1",'arg3' => "form[uploadLocalServer][selectedDir]=".($this->_tpl_vars['index'])), $this);?>
">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'remove'), $this);?>

      </a>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>

  <tr>
    <td>
      <input type="text" size="40" id="newDir" autocomplete="off"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[uploadLocalServer][newDir]"), $this);?>
"
       value="<?php echo $this->_tpl_vars['form']['uploadLocalServer']['newDir']; ?>
"/>
      <?php $this->_tag_stack[] = array('autoComplete', array('element' => 'newDir'), $this); $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true); while ($_block_repeat) { ob_start();?>
	<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SimpleCallback",'arg2' => "command=lookupDirectories",'arg3' => "prefix=__VALUE__",'htmlEntities' => false), $this);?>

      <?php $_obj_block_content = ob_get_contents(); ob_end_clean(); echo $this->_reg_objects['g'][0]->autoComplete($this->_tag_stack[count($this->_tag_stack)-1][1], $_obj_block_content, $this, $_block_repeat=false);} array_pop($this->_tag_stack);?>

    </td><td>
      <input type="submit" class="inputTypeSubmit"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][addUploadLocalServerDir]"), $this);?>
"
       value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Add'), $this);?>
"/>
    </td>
  </tr></table>

  <?php if (isset ( $this->_tpl_vars['form']['error']['uploadLocalServer']['newDir']['missing'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a directory to add."), $this);?>

  </div>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['form']['error']['uploadLocalServer']['newDir']['restrictedByOpenBaseDir'] )): ?>
  <div class="giError">
    <?php ob_start(); ?>
      <a href="http://php.net/ini_set"><?php echo $this->_reg_objects['g'][0]->text(array('text' => 'open_basedir documentation'), $this);?>
</a>
    <?php $this->_smarty_vars['capture']['open_basedir'] = ob_get_contents(); ob_end_clean(); ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Your webserver is configured to prevent you from accessing that directory.  Please refer to the %s and consult your webserver administrator.",'arg1' => $this->_smarty_vars['capture']['open_basedir']), $this);?>

  </div>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['form']['error']['uploadLocalServer']['newDir']['notReadable'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The webserver does not have permissions to read that directory."), $this);?>

  </div>
  <?php endif; ?>

  <?php if (isset ( $this->_tpl_vars['form']['error']['uploadLocalServer']['newDir']['notADirectory'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The path you specified is not a valid directory."), $this);?>

  </div>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Session Settings'), $this);?>
 </h3>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Session Lifetime'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[session][lifetime]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['sessionTimeList'],'selected' => $this->_tpl_vars['form']['session']['lifetime']), $this);?>

      </select>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Inactivity Timeout'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[session][inactivityTimeout]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['sessionTimeList'],'selected' => $this->_tpl_vars['form']['session']['inactivityTimeout']), $this);?>

      </select>
    </td>
  </tr></table>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Embedded Markup'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "What kind of markup should we allow in user-entered fields?  For security reasons we do not recommend that you allow raw HTML.  BBCode is a special kind of markup that is secure and allows for simple text formatting like bold, italics, lists, images and urls."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Markup'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[misc][markup]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['embeddedMarkupList'],'selected' => $this->_tpl_vars['form']['misc']['markup']), $this);?>

      </select>
    </td>
  </tr></table>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Email'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "By default Gallery uses PHP's built in mail function to send email which requires no configuration.  To use a smtp/mail server enter the information below, including authentication information if required.  Optionally add :port after the server name to use a non-default port."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Server'), $this);?>

    </td><td>
      <input type="text" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[smtp][host]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['smtp']['host']; ?>
"/>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Username'), $this);?>

    </td><td>
      <input type="text" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[smtp][username]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['smtp']['username']; ?>
"/>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Password'), $this);?>

    </td><td>
      <input type="password" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[smtp][password]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['smtp']['password']; ?>
"/>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'SMTP From Address'), $this);?>

    </td><td>
      <input type="text" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[smtp][from]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['smtp']['from']; ?>
"/>
      <?php if (isset ( $this->_tpl_vars['form']['error']['smtp']['invalidFrom'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid email address'), $this);?>

      </div>
      <?php endif; ?>
    </td>
  </tr></table>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Send a test email to verify your settings are correct (whether using PHP mail or SMTP settings above).  Below enter a recipient email address for a test message."), $this);?>

  </p>
  <p>
    <input type="text" size="30"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[emailTest][to]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['emailTest']['to']; ?>
"/>
    &nbsp;
    <input type="submit" class="inputTypeSubmit"
     name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][emailTest]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Send Email'), $this);?>
"/>
  </p>
  <?php if (isset ( $this->_tpl_vars['form']['emailTestError']['invalidTo'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid email address'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['emailTestError'] )): ?>
    <h4> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Email Test Error'), $this);?>
 </h4>
    <div class="gcBackground1 gcBorder2"
     style="border-width: 1px; border-style: dotted; padding: 4px">
      <?php echo $this->_tpl_vars['status']['emailTestError']; ?>

      <pre><?php echo $this->_tpl_vars['status']['emailTestDebug']; ?>
</pre>
    </div>
  <?php endif; ?>
</div>

<div class="gbBlock">
  <h3>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cookies'), $this);?>

    <span id="AdminCore_cookie-toggle"
     class="giBlockToggle gcBackground1 gcBorder2" style="border-width: 1px"
     onclick="BlockToggle('AdminCore_cookieDetails', 'AdminCore_cookie-toggle')">+</span>
  </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "When Gallery is embedded in another application (portal, CMS, forum, etc.), then you have the choice between two options. Everyone else does not have to care about the cookie settings. Read on for more details."), $this);?>

  </p>
  <p id="AdminCore_cookieDetails" class="gcBorder2"
   style="display: none; border-width: 1px; border-style: dotted; padding: 4px">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "If your Gallery is embedded and you leave the following fields empty, then all DownloadItem links (the URLs of the images and other items) in the embedded Gallery have <b>an appended GALLERYSID string</b> in the URL which is <b>a minor security risk</b> when your Gallery users start copy'n'pasting image URLs in forums, guestbooks, etc. The alternative is to set the <b>cookie path</b>. Gallery will then <b>not append the GALLERYSID to the embedded DownloadItem URLs</b>. E.g. when Gallery is reachable at http://www.example.com/application/gallery2/ and the embedding application is at http://www.example.com/application/, then you have to compare the path /application/gallery2/ with /application/. The cookie path is the part of the paths that is equal, in this case it is '/application/'. Most often it is just '/'."), $this);?>
  <br/>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "The <b>cookie domain</b> is also only needed for embedded Gallery installs and only if you want to get rid of the GALLERYSID string in the embedded DownloadItem URLs. <b>In most cases, the cookie domain can be left blank.</b> Set it only, if Gallery and the embedding application are only reachable with <b>different subdomains</b>. E.g. when Gallery is at http://photos.example.com/ and the application is at http://www.example.com/, then you have to set the cookie domain example.com (the part of the host string that is common to both, Gallery and the embedding application)."), $this);?>
 <br/>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Once you change the cookie settings, <b>all registered users</b> of your Gallery will <b>have to clear their browser cookie cache</b>. If they do not, they will experience login / logout / lost session problems."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Path'), $this);?>

    </td><td>
      <input type="text" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[cookie][path]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['cookie']['path']; ?>
"/>
      <?php if (isset ( $this->_tpl_vars['form']['error']['cookie']['invalidPath'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid cookie path'), $this);?>
 <br/>
      </div>
      <?php endif; ?>
    </td>
  </tr><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Domain'), $this);?>

    </td><td>
      <input type="text" size="20"
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[cookie][domain]"), $this);?>
" value="<?php echo $this->_tpl_vars['form']['cookie']['domain']; ?>
"/>
      <?php if (isset ( $this->_tpl_vars['form']['error']['cookie']['invalidDomain'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Invalid cookie domain'), $this);?>
 <br/>
      </div>
      <?php endif; ?>
    </td>
  </tr></table>
</div>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Locking System'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Gallery uses a system of locks to prevent simultaneous changes from interfering with each other.  There are two types of locking, each with its advantages and disadvantages.  <b>File</b> based locking is fast and efficient, but won't work on NFS filesystems and will be unreliable on Windows.  <b>Database</b> locking is slower but is more reliable.  If you are unsure which to choose, we recommend using file locking.  If you're getting many lock timeouts, you can try switching to database locking instead.  It's ok to switch back and forth."), $this);?>

  </p>

  <table class="gbDataTable"><tr>
    <td>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Lock system'), $this);?>

    </td><td>
      <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[lock][system]"), $this);?>
">
	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['lockSystemList'],'selected' => $this->_tpl_vars['form']['lock']['system']), $this);?>

      </select>
    </td>
  </tr></table>
</div>

<?php if (isset ( $this->_tpl_vars['AdminCore']['can']['tweakSystemProcesses'] )): ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Helper Processes'), $this);?>
 </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Some Gallery modules will use programs on your server to do image processing, archiving and other operations.  These programs can be very computationally intensive and can impact the overall performance of a shared web server.  You can make these programs run at a lower priority so that they play nice.  If you're in a shared hosting environment and your web host is complaining, try setting your priority low."), $this);?>

  </p>

  <table class="gbDataTable">
    <tr>
      <td> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Priority'), $this);?>
 </td>
      <td>
	<select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[exec][beNice]"), $this);?>
">
	  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['AdminCore']['beNiceList'],'selected' => $this->_tpl_vars['form']['exec']['beNice']), $this);?>

	</select>
      </td>
    </tr>
  </table>
</div>
<?php endif; ?>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][reset]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>