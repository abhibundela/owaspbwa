<style type="text/css">
#errMsg {
    color:#AC0000;
}
</style>

<script language="JavaScript" type="text/javascript">

function validate() {

	resetErrors();

    if (document.getElementById("newDbName").value == "") {
		document.getElementById("errMsg").innerHTML = "Database name can not be empty";
		document.getElementById("newDbName").style.border = "2px solid #AC0000";
		document.getElementById("newDbName").focus();
		return false;
    } else {
    	document.getElementById("btnSubmit").disabled = true;
        return true;
    }

}

function resetErrors() {

	document.getElementById("errMsg").innerHTML = "";
	document.getElementById("newDbName").style.border = "1px inset #000000";

}

</script>

<h2>Provide New Database Name</h2>

<p>Please provide the name of new database for upgrade. Note that new database should be an empty database and should be under database user <b>"<?php echo $oldConfObj->dbuser; ?>"</b> (database user of current database).</p>

<div style="text-align:center">
<form name="frmDbInfo" method="post" action="./" onsubmit="return validate()">
<input type="hidden" name="hdnState" value="dbInfo" />
<input type="text" name="newDbName" size="40" id="newDbName" /><div id="errMsg"></div><br />
<input type="submit" name="btnSubmit" value="Continue" id="btnSubmit" size="40" />
</form>
</div>