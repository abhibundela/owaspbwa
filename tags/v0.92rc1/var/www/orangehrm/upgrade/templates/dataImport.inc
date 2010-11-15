<style type="text/css">
<!--
#progressBar {
	background-color: #FF9900;
	display: block;
	height: 10px;
}

#btnSubmit {
	display:none;
}
-->
</style>

<h2>Data Import from Current Database</h2>

<p id="message">Upgrader successfully created database tables for new installation. Now it's importing data from current database.</p>

<div id="divProgressBarContainer" class="statusValue">
	<span style="width:200px; display: block; float: left; height: 10px; border: solid 1px #000000;">
		<span id="progressBar" style="width: 0%;">&nbsp;</span>
	</span>
	&nbsp;<span id="spanProgressPercentage">0%</span>
</div>

<form name="frmDataImport" method="post" action="./">
<input type="hidden" name="hdnState" value="oldConstraints" />
<input type="submit" name="btnSubmit" value="Continue"  size="40" id="btnSubmit" />
</form>

<script language="javascript" type="text/javascript">
var tArray = new Array();
<?php
$count = count($tablesArray);
$i = 0;
for ($i; $i < $count; $i++) {
    echo "tArray[$i] = \"".$tablesArray[$i]."\"; ";
}
?>
var tCount = <?php echo $count; ?>;
setData(tArray, tCount);
dataImport();
</script>