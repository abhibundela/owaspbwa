<?php /* Smarty version 2.6.14, created on 2011-04-21 07:51:26
         compiled from tiki-install.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'tr', 'tiki-install.tpl', 3, false),array('modifier', 'default', 'tiki-install.tpl', 39, false),array('modifier', 'escape', 'tiki-install.tpl', 132, false),)), $this); ?>
<div style="margin-left:180px;margin-right:180px;">
<h1>Tiki installer v1.9.5<a title='help' href='http://doc.tikiwiki.org/Installation' target="help"><img
border='0' src='img/icons/help.gif' alt="<?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>help<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" /></a></h1>

<?php if ($this->_tpl_vars['tikifeedback']): ?>
<br /><?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['tikifeedback']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?><div class="simplebox <?php if ($this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['num'] > 0): ?> highlight<?php endif; ?>"><?php echo $this->_tpl_vars['tikifeedback'][$this->_sections['n']['index']]['mes']; ?>
</div><?php endfor; endif;  endif; ?>

<?php if ($this->_tpl_vars['virt']): ?>
<table><tr><td width="180">
<div class="box">
<div class="box-title">
<a title='help' href='http://tikiwiki.org/MultiTiki19' target="help"><img border='0' src='img/icons/help.gif' alt="<?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>help<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" /></a>
MultiTiki setup</div>
<div class="box-data">
<div><a href="tiki-install.php">default</a></div><br />
<?php $_from = $this->_tpl_vars['virt']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['i']):
?>
<div>
<tt><?php if ($this->_tpl_vars['i'] == 'y'): ?><b style="color:#00CC00;">DBok</b><?php else: ?><b style="color:#CC0000;">NoDB</b><?php endif; ?></tt>
<?php if ($this->_tpl_vars['k'] == $this->_tpl_vars['multi']): ?>
<b><?php echo $this->_tpl_vars['k']; ?>
</b>
<?php else: ?>
<a href="tiki-install.php?multi=<?php echo $this->_tpl_vars['k']; ?>
" class='linkmodule'><?php echo $this->_tpl_vars['k']; ?>
</a>
<?php endif; ?>
</div>
<?php endforeach; endif; unset($_from); ?>
</div></div>

<div class="box">
<div class="box-title">To add a new virtual host</div>
<div class="box-data">
To add a new virtual host run the setup.sh with the domain name of the new host as a last parameter.
</div>
</td>
<td valign="top">
<?php endif; ?>

<?php if ($this->_tpl_vars['multi']): ?> <h2> (MultiTiki) <?php echo ((is_array($_tmp=@$this->_tpl_vars['multi'])) ? $this->_run_mod_handler('default', true, $_tmp, 'default') : smarty_modifier_default($_tmp, 'default')); ?>
 </h2> <?php endif; ?>

<a href="tiki-install.php?restart=1<?php if ($this->_tpl_vars['multi']): ?>&amp;multi=<?php echo $this->_tpl_vars['multi'];  endif; ?>" class="link">reload</a><br /><br />


<?php if ($this->_tpl_vars['dbcon'] == 'n' || $this->_tpl_vars['resetdb'] == 'y'): ?>
<b>Tiki cannot find a database connection</b><br />
Please enter your database connection info<br /><br />
<form action="tiki-install.php" method="post">
<?php if ($this->_tpl_vars['multi']): ?><input type="hidden" name="multi" value="<?php echo $this->_tpl_vars['multi']; ?>
" /><?php endif; ?>
<table class="normal"><tr class="formcolor">
<td>Database type:</td>
<td>
<select name="db">
<?php unset($this->_sections['dbnames']);
$this->_sections['dbnames']['name'] = 'dbnames';
$this->_sections['dbnames']['loop'] = is_array($_loop=$this->_tpl_vars['dbservers']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['dbnames']['show'] = true;
$this->_sections['dbnames']['max'] = $this->_sections['dbnames']['loop'];
$this->_sections['dbnames']['step'] = 1;
$this->_sections['dbnames']['start'] = $this->_sections['dbnames']['step'] > 0 ? 0 : $this->_sections['dbnames']['loop']-1;
if ($this->_sections['dbnames']['show']) {
    $this->_sections['dbnames']['total'] = $this->_sections['dbnames']['loop'];
    if ($this->_sections['dbnames']['total'] == 0)
        $this->_sections['dbnames']['show'] = false;
} else
    $this->_sections['dbnames']['total'] = 0;
if ($this->_sections['dbnames']['show']):

            for ($this->_sections['dbnames']['index'] = $this->_sections['dbnames']['start'], $this->_sections['dbnames']['iteration'] = 1;
                 $this->_sections['dbnames']['iteration'] <= $this->_sections['dbnames']['total'];
                 $this->_sections['dbnames']['index'] += $this->_sections['dbnames']['step'], $this->_sections['dbnames']['iteration']++):
$this->_sections['dbnames']['rownum'] = $this->_sections['dbnames']['iteration'];
$this->_sections['dbnames']['index_prev'] = $this->_sections['dbnames']['index'] - $this->_sections['dbnames']['step'];
$this->_sections['dbnames']['index_next'] = $this->_sections['dbnames']['index'] + $this->_sections['dbnames']['step'];
$this->_sections['dbnames']['first']      = ($this->_sections['dbnames']['iteration'] == 1);
$this->_sections['dbnames']['last']       = ($this->_sections['dbnames']['iteration'] == $this->_sections['dbnames']['total']);
?>
<option value="<?php echo $this->_tpl_vars['dbservers'][$this->_sections['dbnames']['index']]; ?>
"><?php echo $this->_tpl_vars['dbservers'][$this->_sections['dbnames']['index']]; ?>
</option>
<?php endfor; endif; ?>
</select>
</td>
<td>The type of database you intend to use</td>
</tr>

<tr class="formcolor">
<td>Host:</td>
<td>
<input type="text" name="host" value="localhost" />
</td><td>
Hostname or IP for your MySQL database, example: localhost if running in the same machine as tiki<br />
If you use SQLite, insert the path and filename to your database file
</td>
</tr>

<tr class="formcolor">
<td>User:</td>
<td>
<input type="text" name="user" />
</td><td>
Database user
</td>
</tr>

<tr class="formcolor">
<td>Password:</td>
<td>
<input type="password" name="pass" />
</td><td>
Database password
</td>
</tr>

<tr class="formcolor">
<td>Database name:</td>
<td>
<input type="text" name="name" />
</td><td>
The name of the database where tiki will create tables. You can create the database using mysqladmin, or PHPMyAdmin or ask your
hosting service to create a MySQL database.  Normally Tiki tables won't conflict with other product names.<br />
If you use Oracle, you can put your TNS Name here and leave hostname empty
or you override tnsnames.ora and put your SID here and fill your hostname:port above.
</td>
</tr>
			
<tr class="formcolor">
<td>&nbsp;</td>
<td><input type="hidden" name="resetdb" value="<?php echo $this->_tpl_vars['resetdb']; ?>
" />
<input type="submit" name="dbinfo" /></td>
<td>&nbsp;</td>
</tr>
	  	
	  </table>
	  </form>
	<?php else: ?>
	  	  <?php if ($this->_tpl_vars['dbdone'] == 'n'): ?>
		  <?php if ($this->_tpl_vars['logged'] == 'y'): ?>
		    		    <b>Welcome to the installation & upgrade script!</b><br />
		    <br /><br />
			
		    <form method="post" action="tiki-install.php">
				<?php if ($this->_tpl_vars['multi']): ?><input type="hidden" name="multi" value="<?php echo $this->_tpl_vars['multi']; ?>
" /><?php endif; ?>
				<hr>
		    <table>
		    <tr><td style="text-align: center;" colspan="2"
 rowspan="1" height="26"><font size="5"><b>Install</b></font>
 			</td></tr>
			<tr><td>
			Create database (clean install) with profile:
			</td><td>
			<select name="profile">
			<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['profiles']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['profiles'][$this->_sections['ix']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['profiles'][$this->_sections['ix']['index']]['desc']; ?>
</option>
			<?php endfor; endif; ?>
			</select>
			<input type="submit" name="scratch" value="create" />	    
		    </td></tr>
			<td height="100" valign="top">
			</td><td height="100" valign="top">
			<a target="_new" href="http://tikiwiki.org/tiki-index.php?page=TikiProfiles" class="link">Descriptions of the available profiles</a>
			<p>
		    </td>
		    <tr><td colspan="2">

			<hr>
			<tr><td style="text-align: center;" colspan="2"
 rowspan="1" height="26"><font size="5"><b>Upgrade</b></font>
 			</td></tr>
		    <tr><td colspan="2">				
			Important: <b>backup your database</b> with mysqldump or phpmyadmin before you proceed. <br>
			</td></tr>
		    <tr><td>			
			Update database using script: 
			</td><td>
			<select name="file">
			<?php unset($this->_sections['ix']);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['files']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['files'][$this->_sections['ix']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo $this->_tpl_vars['files'][$this->_sections['ix']['index']]; ?>
</option>
			<?php endfor; endif; ?>
			</select>
			<input type="submit" name="update" value="update" />
		    </td></tr>
		    <tr><td colspan="2">
			For database update from 1.8 or later:
			<ol>
				<li>If you upgrade from 1.8.x you <b>MUST</b> run tiki_1.8to1.9 and don't need an additional script.</li>
				<li>If you upgrade from a previous 1.9.x version, use tiki_1.8to1.9, too. (ex.: 1.9.2 to 1.9.5)</li>
			</ol>
		    <tr><td colspan="2">
		    	For database update from 1.7.x, please visit <a target="help" href="http://tikiwiki.org/UpgradeTo18">Tiki database 1.7.x to 1.8x instructions</a>

			
		</td></tr>
		    <tr><td colspan="2">
		    	For information about tiki-secdb_*.sql files, please see <a target="help" href="http://tikiwiki.org/AdminSecurity">http://tikiwiki.org/AdminSecurity</a>

			
		</td></tr>		
		
		
		
		
		    </table>
		    </form><br />
			<hr>
			<br /><br /><br />
			<a href="tiki-index.php" class="link">Do nothing and enter Tiki</a><br />
			<a href="tiki-install.php?reset=yes" class="link">Reset database connection settings</a>
		  <?php else: ?>
						<b>This site has an admin account configured</b><br />
		    Please enter your admin password to continue<br /><br />

     <form name="loginbox" action="tiki-install.php" method="post"> 
			<?php if ($this->_tpl_vars['multi']): ?><input type="hidden" name="multi" value="<?php echo $this->_tpl_vars['multi']; ?>
" /><?php endif; ?>
          <table>
          <tr><td class="module"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>user<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td></tr>
          <tr><td>admin</td></tr>
          <tr><td class="module"><?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>pass<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</td></tr>
          <tr><td><input type="password" name="pass" size="20" /></td></tr>
          <tr><td><input type="submit" name="login" value="<?php $this->_tag_stack[] = array('tr', array()); $_block_repeat=true;smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_tr($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>" /></td></tr>
          </table>
      </form>

		  <?php endif; ?>
    	<?php else: ?>
    		<b>Print operations executed successfully</b><br />
    		<textarea rows="15" cols="80">
    		<?php unset($this->_sections['ix']);
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['succcommands']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    		<?php echo $this->_tpl_vars['succcommands'][$this->_sections['ix']['index']]; ?>

    		<?php endfor; endif; ?>
    		</textarea><br /><br />
    		<b>Print operations failed</b><br />
    		<textarea rows="15" cols="80">
    		<?php unset($this->_sections['ix']);
$this->_sections['ix']['loop'] = is_array($_loop=$this->_tpl_vars['failedcommands']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['ix']['name'] = 'ix';
$this->_sections['ix']['show'] = true;
$this->_sections['ix']['max'] = $this->_sections['ix']['loop'];
$this->_sections['ix']['step'] = 1;
$this->_sections['ix']['start'] = $this->_sections['ix']['step'] > 0 ? 0 : $this->_sections['ix']['loop']-1;
if ($this->_sections['ix']['show']) {
    $this->_sections['ix']['total'] = $this->_sections['ix']['loop'];
    if ($this->_sections['ix']['total'] == 0)
        $this->_sections['ix']['show'] = false;
} else
    $this->_sections['ix']['total'] = 0;
if ($this->_sections['ix']['show']):

            for ($this->_sections['ix']['index'] = $this->_sections['ix']['start'], $this->_sections['ix']['iteration'] = 1;
                 $this->_sections['ix']['iteration'] <= $this->_sections['ix']['total'];
                 $this->_sections['ix']['index'] += $this->_sections['ix']['step'], $this->_sections['ix']['iteration']++):
$this->_sections['ix']['rownum'] = $this->_sections['ix']['iteration'];
$this->_sections['ix']['index_prev'] = $this->_sections['ix']['index'] - $this->_sections['ix']['step'];
$this->_sections['ix']['index_next'] = $this->_sections['ix']['index'] + $this->_sections['ix']['step'];
$this->_sections['ix']['first']      = ($this->_sections['ix']['iteration'] == 1);
$this->_sections['ix']['last']       = ($this->_sections['ix']['iteration'] == $this->_sections['ix']['total']);
?>
    		<?php echo $this->_tpl_vars['failedcommands'][$this->_sections['ix']['index']]; ?>

    		<?php endfor; endif; ?>
    		</textarea><br /><br />
    		Your database has been configured and Tiki is ready to run, if
    		this is your first install your admin password is 'admin'. You can
    		now log in into Tiki as 'admin' - 'admin' and start configuring
    		the application.<br />
    		Note: This install script may be potentially harmful so we strongly
    		recommend you to disable the script and then proceed into Tiki. If
    		you decide to reuse later, just follow the instructions in
		tiki-install.php to restore.<br /><br />

    		READ THE FOLLOWING NOTES BEFORE ENTERING TIKI USING THE LINKS BELOW!
    		
    		<div class="rbox" name="tip">
            <div class="rbox-title" name="tip">Note</div>  
            <div class="rbox-data" name="tip">Make sure tiki gets more than 8 MB of memory for script execution. 
See file php.ini, the relevant key is memory_limit. Use something like memory_limit = 16M and restart your 
webserver. Too little memory will cause blank pages!</div>
            </div>
			
	<?php if ($this->_tpl_vars['php_memory_limit'] == ''): ?>
		<div style="border-style: solid; border-width: 1; padding: 4">
		  <p align="center">
		  <span style="padding: 2px; background-color: #00FF00">
		  Tiki has not detected your PHP memory_limit. This probably means you have no set limit (all is well). 
		  </span>
		</div>	
	
	<?php elseif ($this->_tpl_vars['php_memory_limit'] == '8M'): ?>
		<div style="border-style: solid; border-width: 1; padding: 4">
		  <p align="center">
		  <span style="text-decoration: blink; font-size: x-large; padding: 4px; background-color: #FF0000">
		  Tiki has detected your PHP memory limit at only 8 Megs
		  </span>
		</div>

	<?php else: ?>
		<div style="border-style: solid; border-width: 1; padding: 4">
		  <p align="center">
		  <span style="font-size: large; padding: 4px;">
Tiki has detected your PHP memory_limit at: <?php echo $this->_tpl_vars['php_memory_limit']; ?>
. 
		  </span>
		</div>	
	<?php endif; ?>			
			
            <br />

    		<div class="rbox" name="tip">
            <div class="rbox-title" name="tip">Note</div>  
            <div class="rbox-data" name="tip">If this is a first time installation, go to tiki-admin.php after login to start configuring your new Tiki installation.</div>
            </div>
            <br />

    		<div class="rbox" name="tip">
            <div class="rbox-title" name="tip">Note</div>  
            <div class="rbox-data" name="tip">If you did a Tiki upgrade, make sure to clean the caches (templates_c/) manually or by using the feature on admin / system.</div>
            </div>
            <br />
            <br />
            Now you may proceed by clicking one of these links:<br /><br />

    		<a href="tiki-install.php?kill=1" class="link">Click here to disable the install script and proceed into tiki.</a><br /><br />
    		<a href="tiki-index.php" class="link">Click here to proceed into tiki without disabling the script.</a><br /><br />
    		<a href="tiki-install.php?reset=yes<?php if ($this->_tpl_vars['multi']): ?>&amp;multi=<?php echo $this->_tpl_vars['multi'];  endif; ?>" class="link">Reset database connection settings.</a><br /><br />
    		<a href="tiki-install.php<?php if ($this->_tpl_vars['multi']): ?>?multi=<?php echo $this->_tpl_vars['multi'];  endif; ?>" class="link">Go back and run another install/upgrade script</a> - do not use your Back button in your browser!<br /><br />
    	<?php endif; ?>
	<?php endif; ?>
</div>

<?php if ($this->_tpl_vars['virt']): ?>
</td></tr></table>
<?php endif; ?>