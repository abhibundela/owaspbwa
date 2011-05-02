<?php /* Smarty version 2.6.10, created on 2011-04-20 13:27:04
         compiled from gallery:modules/core/templates/AdminModules.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cycle', 'gallery:modules/core/templates/AdminModules.tpl', 63, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Gallery Modules'), $this);?>
 </h2>
</div>

<?php if (! empty ( $this->_tpl_vars['status'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php if (isset ( $this->_tpl_vars['status']['installed'] )): ?>
    <?php if (! empty ( $this->_tpl_vars['status']['autoConfigured'] )): ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully installed and auto-configured module %s",'arg1' => $this->_tpl_vars['status']['installed']), $this);?>

    <?php else: ?>
      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully installed module %s",'arg1' => $this->_tpl_vars['status']['installed']), $this);?>

    <?php endif; ?>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['configured'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully configured module %s",'arg1' => $this->_tpl_vars['status']['configured']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['upgraded'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully upgraded module %s",'arg1' => $this->_tpl_vars['status']['upgraded']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['activated'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully activated module %s",'arg1' => $this->_tpl_vars['status']['activated']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['deactivated'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully deactivated module %s",'arg1' => $this->_tpl_vars['status']['deactivated']), $this);?>

  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['status']['uninstalled'] )): ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Successfully uninstalled module %s",'arg1' => $this->_tpl_vars['status']['uninstalled']), $this);?>

  <?php endif; ?>
</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Gallery features come as separate modules.  You can download and install modules to add more features to your Gallery, or you can disable features if you don't want to use them.  In order to use a feature, you must install, configure (if necessary) and activate it.  If you don't wish to use a feature, you can deactivate it."), $this);?>

  </p>

  <table class="gbDataTable">
    <?php $this->assign('group', ""); ?>
    <?php $_from = $this->_tpl_vars['AdminModules']['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module']):
?>
      <?php if ($this->_tpl_vars['group'] != $this->_tpl_vars['module']['group']): ?>
	<?php if (! empty ( $this->_tpl_vars['group'] )): ?>
	  <tr><td> &nbsp; </td></tr>
	<?php endif; ?>
	<tr>
	  <th colspan="6"><h2><?php echo $this->_tpl_vars['module']['groupLabel']; ?>
</h2></th>
	</tr><tr>
	  <th> &nbsp; </th>
	  <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Module Name'), $this);?>
 </th>
	  <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Version'), $this);?>
 </th>
	  <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Installed'), $this);?>
 </th>
	  <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Description'), $this);?>
 </th>
	  <th> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Actions'), $this);?>
 </th>
	</tr>
      <?php endif; ?>
      <?php $this->assign('group', $this->_tpl_vars['module']['group']); ?>

      <tr class="<?php echo smarty_function_cycle(array('values' => "gbEven,gbOdd"), $this);?>
">
	<td>
	  <?php if ($this->_tpl_vars['module']['state'] == 'install'): ?>
	  <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-install.gif"), $this);?>
" width="13" height="13"
	       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Not Installed"), $this);?>
" />
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['module']['state'] == 'active'): ?>
	  <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-active.gif"), $this);?>
" width="13" height="13"
	       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Active"), $this);?>
" />
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['module']['state'] == 'inactive'): ?>
	  <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-inactive.gif"), $this);?>
" width="13" height="13"
	       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Inactive"), $this);?>
" />
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['module']['state'] == 'upgrade'): ?>
	  <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-upgrade.gif"), $this);?>
" width="13" height="13"
	       alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Upgrade Required (Inactive)"), $this);?>
" />
	  <?php endif; ?>
	  <?php if ($this->_tpl_vars['module']['state'] == 'incompatible'): ?>
	  <img src="<?php echo $this->_reg_objects['g'][0]->url(array('href' => "modules/core/data/module-incompatible.gif"), $this);?>
" width="13"
	       height="13" alt="<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Status: Incompatible Module (Inactive)"), $this);?>
" />
	  <?php endif; ?>
	</td>

	<td>
	  <?php echo $this->_tpl_vars['module']['name']; ?>

	</td>

	<td align="center">
	  <?php echo $this->_tpl_vars['module']['version']; ?>

	</td>

	<td align="center">
	  <?php echo $this->_tpl_vars['module']['installedVersion']; ?>

	</td>

	<td>
	  <?php echo $this->_tpl_vars['module']['description']; ?>

	  <?php if ($this->_tpl_vars['module']['state'] == 'incompatible'): ?>
	    <br/>
	    <span class="giError">
	      <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Incompatible module!"), $this);?>

	      <?php if ($this->_tpl_vars['module']['api']['required']['core'] != $this->_tpl_vars['module']['api']['provided']['core']): ?>
		<br/>
		<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Core API Required: %s (available: %s)",'arg1' => $this->_tpl_vars['module']['api']['required']['core'],'arg2' => $this->_tpl_vars['module']['api']['provided']['core']), $this);?>

	      <?php endif; ?>
	      <?php if ($this->_tpl_vars['module']['api']['required']['module'] != $this->_tpl_vars['module']['api']['provided']['module']): ?>
		<br/>
		<?php echo $this->_reg_objects['g'][0]->text(array('text' => "Module API Required: %s (available: %s)",'arg1' => $this->_tpl_vars['module']['api']['required']['module'],'arg2' => $this->_tpl_vars['module']['api']['provided']['module']), $this);?>

	      <?php endif; ?>
	    </span>
	  <?php endif; ?>
	</td>

	<td>
	  <?php if (( ! empty ( $this->_tpl_vars['module']['action'] ) )): ?>
	    <?php $_from = $this->_tpl_vars['module']['action']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['actions'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['actions']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['action']):
        $this->_foreach['actions']['iteration']++;
 echo '';  if (! ($this->_foreach['actions']['iteration'] <= 1)):  echo '<br/>';  endif;  echo '';  if (( empty ( $this->_tpl_vars['action']['controller'] ) )):  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=".($this->_tpl_vars['action']['view'])), $this); echo '">';  echo $this->_tpl_vars['action']['text'];  echo '</a>';  else:  echo '<a href="';  echo $this->_reg_objects['g'][0]->url(array('arg1' => "controller=".($this->_tpl_vars['action']['controller']),'arg2' => "moduleId=".($this->_tpl_vars['action']['moduleId']),'arg3' => "action=".($this->_tpl_vars['action']['action'])), $this); echo '">';  echo $this->_tpl_vars['action']['text'];  echo '</a>';  endif;  echo '';  endforeach; endif; unset($_from); ?>
	  <?php else: ?>
	    &nbsp;
	  <?php endif; ?>
	</td>
      </tr>
    <?php endforeach; endif; unset($_from); ?>
  </table>
</div>