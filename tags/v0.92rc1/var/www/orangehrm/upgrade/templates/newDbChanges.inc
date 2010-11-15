<h2>New Database Schema Changes</h2>

<p id="message">Upgrader is now applying database schema changes introduced by version 2.4.2</p>

<table width="400" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Creating new database tables</td>
    <td><div id="tables"></div></td>
  </tr>
  <tr>
    <td>Applying new database alterations</td>
    <td><div id="alter"></div></td>
  </tr>
  <tr>
    <td>Storing default data</td>
    <td><div id="store"></div></td>
  </tr>
</table>

<form name="frmNewDbChanges" method="post" action="./">
<input type="hidden" name="hdnState" value="" />
<input type="submit" name="btnSubmit" value="Continue"  size="40" id="btnSubmit" style="display:none" />
</form>

<script language="javascript" type="text/javascript">
newDbChanges();
</script>