<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure.
				$lEnableJavaScriptValidation = FALSE;
    		break;

    		case "1": // This code is insecure.
				$lEnableJavaScriptValidation = TRUE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
    			$lEnableJavaScriptValidation = TRUE;
    		break;
    	}// end switch
	} catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error setting up configuration.");
	}// end try	
?>

<script type="text/javascript">
<!--
	<?php 
		if ($_SESSION["loggedin"]=="True") {
			echo "var l_loggedIn = true;" . PHP_EOL;
		}else {
			echo "var l_loggedIn = false;" . PHP_EOL;
		}// end if
	
		if (isset($failedloginflag)){
			echo 'var l_failedLogInFlag = "'.$failedloginflag.'";' . PHP_EOL;
		}else{
			echo 'var l_failedLogInFlag = "0";'.PHP_EOL;
		}// end if
 
		if($lEnableJavaScriptValidation){
			echo "var lValidateInput = \"TRUE\"" . PHP_EOL;
		}else{
			echo "var lValidateInput = \"FALSE\"" . PHP_EOL;
		}// end if		
	?>

	function onSubmitOfLoginForm(/*HTMLFormElement*/ theForm){
		try{
			var lUnsafeCharacters = /[`~!@#$%^&*()-_=+\[\]{}\\|;':",./<>?]/;

			if(lValidateInput == "TRUE"){
				if (theForm.username.value.length > 15 || 
					theForm.password.value.length > 15){
						alert('Username too long. We dont want to allow too many characters.\n\nSomeone might have enough room to enter a hack attempt.');
						return false;
				}// end if
				
				if (theForm.username.value.search(lUnsafeCharacters) > -1 || 
					theForm.password.value.search(lUnsafeCharacters) > -1){
						alert('Dangerous characters detected. We can\'t allow these. This all powerful blacklist will stop such attempts.\n\nMuch like padlocks, filtering cannot be defeated.\n\nBlacklisting is l33t like l33tspeak.');
						return false;
				}// end if
			}// end if(lValidateInput)
			
			return true;
		}catch(e){
			alert("Error: " + e.message);
		}// end catch
	}// end function onSubmitOfLoginForm(/*HTMLFormElement*/ theForm)
//-->
</script>

<div class="page-title">Login</div>

<?php include_once './includes/back-button.inc';?>

<div id="id-log-in-form-div" style="display: none; text-align:center;">
	<form 	action="index.php?page=login.php" 
			method="post" 
			enctype="application/x-www-form-urlencoded" 
			onsubmit="return onSubmitOfLoginForm(this);"
			id="idLoginForm">
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-bad-cred-tr" style="display: none;">
				<td colspan="2" class="error-message">
					Authentication Error: Bad user name or password
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" class="form-header">Please sign-in</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label">Name</td>
				<td><input type="text" name="username" maxlength="20" size="20"></td>
			</tr>
			<tr>
				<td class="label">Password</td>
				<td><input type="password" name="password" maxlength="20" size="20"></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="login-php-submit-button" class="button" type="submit" value="Login" />
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center; font-style: italic;">
					Dont have an account? <a href="?page=register.php">Please register here</a>
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="id-log-out-div" style="text-align: center; display: none;">
	<table align="center">
		<tr>
			<td colspan="2" class="hint-header">You are logged in as <?php echo $_SESSION['logged_in_user']; ?></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input class="button" type="button" value="Logout" onclick="document.location='index.php?do=logout'" />
			</td>
		</tr>
	</table>	
</div>

<script type="text/javascript">
	if(l_failedLogInFlag=="1"){
		document.getElementById("id-bad-cred-tr").style.display="";
	}// end if l_failedLogInFlag

	if (!l_loggedIn){
		document.getElementById("id-log-in-form-div").style.display="";
		document.getElementById("id-log-out-div").style.display="none";
	}else{
		document.getElementById("id-log-in-form-div").style.display="none";
		document.getElementById("id-log-out-div").style.display="";		
	}// end if l_loggedIn	
</script>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>For SSL Injection:</b>The old "\' or 1=1 -- " is a classic, but there are others. Check out who 
								you are logged in as after you do the injection.
							</li>
						  	<li><b>For Session and Authentication:</b>As for playing with sessions, try a 
								<a href="https://addons.mozilla.org/en-US/firefox/addon/4510">cookie editor</a> 
								to change your UID.
							</li>
							<li><b>For Insecure Authentication:</b>Try sniffing the traffic with Wireshark, Cain, Dsniff or Ettercap.</li>
							<li>Some code contains naive protections such as limiting the width of HTML fields. 
								If your If you find that you need more room, try using a tool like Firebug to 
								change the size of the field to be as long as you like. As you advance, 
								try using tools like netcat to make your own POST requests without having 
								to use the login web page at all.
							</li>
							<li>You can use the login page normally but then simply change the parameters is Tamper Data. 
							Because Tamper Data is allowing the user to manipulate the request after the request has 
							left the browser, any HTML or JavaScript has already run and is completely useless as a 
							security measure. Any use of HTML or JavaScript for security purposes is useless anyway. 
							Some developers still fail to recognize this fact to this day.
							</li>
							<li>
							Try SQL injection probing by entering single-quotes, double-quotes, 
							paranthesis, double-dash (--), hyphen-asterik (/*), and 
							closing-parenthesis-hyphen-hyphen ()--)
							</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/sql-injection-tutorial.inc';
	}// end if
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/insufficient-transport-layer-protection.inc';
	}// end if	
?>

<script type="text/javascript">
	try{
		document.getElementById("idLoginForm").username.focus();
	}catch(e){
		alert('Error trying to set focus on field idLoginForm: ' + e.message);
	}// end try
</script>
