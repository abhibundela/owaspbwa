<h2>Problems in Database Value Changes</h2>

<p>Upgrader found following errors when applying database value changes. You may delete this database and start with a new database.</p>

<ul style="color:#CC0000">
<?php
foreach ($upgrader->errorArray as $error) {
	echo "<li>$error</li>";
}
?>
</ul>

<form name="frmDataError" method="post" action="./">
<input type="hidden" name="hdnState" value="upgradeStart" />
<input type="submit" name="btnSubmit" value="Back"  size="40" />
</form>