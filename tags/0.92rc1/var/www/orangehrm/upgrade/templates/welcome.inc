<h2>Welcome to OrangeHRM 2.4.2 Upgrader</h2>

<p>Please enter your admin login details to proceed with the upgrader</p>

	<form name="frmAdminLogin" method="post" action="./">
	<table width="200" border="0" cellspacing="5" cellpadding="5" align="center">
  <tr>
    <td>Username:</td>
    <td><input type="text" name="txtUsername" size="20" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input type="password" name="txtPassword" size="20" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="btnLogin" value="Login"  size="40" /></td>
    </tr>
</table>
<input type="hidden" name="hdnState" value="authAdmin" />
<input type="hidden" name="hdnInProgress" value="authAdmin" />
</form>