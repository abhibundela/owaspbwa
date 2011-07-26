<h2>Database Value Changes</h2>

<p id="message">OrangeHRM version 2.4.2 contains following database value changes. You can select or skip these changes.</p>

<form name="frmDbValueChanges" method="post" action="./">
<table width="400" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Data encryption for Employee SSN Number and Employee Basic Salary. Note that encryption is done only at database level
	and values will be shown as they were in the application.</td>
    <td><input name="chkEncryption" type="checkbox" value="Enable"></td>
  </tr>
</table>
<div style="text-align:center">
<input type="hidden" name="hdnState" value="dbValueChanges" />
<input type="submit" name="btnSubmit" value="Continue"  size="40" />
</div>
</form>

