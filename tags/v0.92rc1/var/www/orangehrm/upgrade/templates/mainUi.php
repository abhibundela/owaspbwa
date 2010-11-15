<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OrangeHRM Web Installation Wizard</title>
<link href="../favicon.ico" rel="icon" type="image/gif"/>
<link href="favicon.ico" rel="icon" type="image/gif"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if ($dataImportAjax == 'Yes') { ?><script language="javascript" type="text/javascript" src="templates/dataImport-ajax.js"></script><?php } ?>
<?php if ($dbChangesAjax == 'Yes') { ?><script language="javascript" type="text/javascript" src="templates/newDbChanges-ajax.js"></script><?php } ?>
<?php if ($confFilesAjax == 'Yes') { ?><script language="javascript" type="text/javascript" src="templates/locateConfFiles-ajax.js"></script><?php } ?>
<link href="templates/upgraderStyle.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="body">
<img src="../themes/beyondT/pictures/orange3.png" alt="OrangeHRM" name="logo"  width="264" height="62" border="0" id="logo" style="margin-left: 10px;" title="OrangeHRM">
<table border="0" cellpadding="0" cellspacing="0">
<tr>

<?php
	$tocome = '';
	for ($i=0; $i < count($steps); $i++) {
		if ($currScreen == $i) {
			$tabState = 'Active';
		} else {
			$tabState = 'Inactive';
		}
?>

    <td nowrap="nowrap" class="left_<?php echo $tabState?>">&nbsp;</td>
    <td nowrap="nowrap" class="middle_<?php echo $tabState.$tocome?>"><?php echo $steps[$i]?></td>
	<td nowrap="nowrap" class="right_<?php echo $tabState?>">&nbsp;</td>

<?php
	if ($tabState == 'Active') {
		$tocome = '_tocome';
	}
}
?>

</tr>
</table>

<div id="content">
<?php require_once $currPage ?>
</div>

<div id="footer"><a href="http://www.orangehrm.com" target="_blank">OrangeHRM</a> Upgrader ver 2.4.2 &copy; OrangeHRM Inc 2005 - 2008 All rights reserved.</div>
</div>
</body>
</html>

