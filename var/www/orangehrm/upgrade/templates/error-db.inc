<h2>Given Database Is Not Accessible</h2>

<p>Upgrader found following error in the given database. Please correct it and re-enter database name.</p>

<ul style="color:#CC0000">
<?php
foreach ($upgrader->errorArray as $error) {
	echo "<li>$error</li>";
}
?>
</ul>

<form name="frmDbError" method="post" action="./">
<input type="hidden" name="hdnState" value="upgradeStart" />
<input type="submit" name="btnSubmit" value="Back"  size="40" />
</form>