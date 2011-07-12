<h2>Data Incompatibilities Found</h2>

<p>Upgrader found following data incompatibilities. Upgrading is not possible with these incompatibilities. Please corrrect them and re-run the upgrader.</p>

<ul style="color:#CC0000">
<?php
foreach ($upgrader->errorArray as $error) {
	echo "<li>$error</li>";
}
?>
</ul>

<form name="frmDataError" method="post" action="./">
<input type="hidden" name="hdnState" value="dataError" />
<input type="submit" name="btnSubmit" value="Back to OrangeHRM Home"  size="80" />
</form>