<h2>Locating Configuration Files</h2>

<p id="message">Upgrader is now locating configuration files.</p>

<table width="400" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Creating Conf.php</td>
    <td><div id="conf"></div></td>
  </tr>
  <tr>
    <td>Copying mailConf.php</td>
    <td><div id="mail"></div></td>
  </tr>
 <?php if($oldVersion == '2.3' || $oldVersion == '2.4' || $oldVersion == '2.4.0.1' || $oldVersion == '2.4.1') { ?>
  <tr>
    <td>Copying key.ohrm</td>
    <td><div id="enckey"></div></td>
  </tr>
  <?php } ?>
  <tr>
    <td>Creating upgradeConf.php</td>
    <td><div id="upgrade"></div></td>
  </tr>
</table>

<form name="frmUpgraderFinished" method="post" action="./">
<input type="hidden" name="hdnState" value="" />
<input type="submit" name="btnSubmit" value="Finish"  size="40" id="btnSubmit" style="display:none" />
</form>

<script language="javascript" type="text/javascript">
var actions = new Array();
<?php if($oldVersion == '2.3' || $oldVersion == '2.4' || $oldVersion == '2.4.0.1' || $oldVersion == '2.4.1') { ?>
	actions[0] = "conf";
	actions[1] = "mail";
	actions[2] = "enckey";
	actions[3] = "upgrade";
<?php  } elseif ($oldVersion == '2.2.2.2') { ?>
 	actions[0] = "conf";
 	actions[1] = "mail";
	actions[2] = "upgrade";
 <?php } ?>
setData(actions) ;
locateConfFiles();
</script>
