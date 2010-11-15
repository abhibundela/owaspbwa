<h2>Congratulations! You're Ready to Upgrade</h2>

<p>This upgrader will install an entire new instance of OrangeHRM 2.4.2 under /newversion/. It will import data from current OrangeHRM installation. But it won't affect
	existing installation in any way. After a successful installation, you can login to new instance at /newversion/ (Ex: If your current version is at http://www.example.com/orangehrm
	then newversion would be available at http://www.example.com/orangehrm/newversion/).
</p>

<p>If you are satisfied with new instance, you can delete old installation files, old database and move contents under newversion one level up. So that you can have OrangeHRM 2.4.2 in the same location where old installation was.
	If you are not satisfied with new installation, simply delete all the content under  /newversion/ and corresponding database.
</p>

<p>Check <a href="http://www.orangehrm.com/upgrade-instructions.shtml" target="_blank">Upgrade Instructions</a> at our web site for more details.</p>

<form name="frmUpgradeStart" method="post" action="./">
<input type="hidden" name="hdnState" value="upgradeStart" />
<input type="submit" name="btnSubmit" value="Continue"  size="40" />
</form>