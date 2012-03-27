<div class="page-title">Register for an Account</div>

<?php include_once './includes/back-button.inc';?>

<?php 
	if (isset($_POST["register-php-submit-button"])){
		
		try {
			$username = $_POST["username"];
			$password = $_POST["password"];
			$confirm_password = $_POST["confirm_password"];
			$mysignature = $_POST["my_signature"];
			$lValidationFailed = false;
			
		   	switch ($_SESSION["security-level"]){
		   		case "0": // This code is insecure
		   		case "1": // This code is insecure
		   			// DO NOTHING: This is equivalent to using client side security		
					$query = "INSERT INTO accounts (username, password, mysignature) VALUES ('" . 
					$username ."', '" . 
					$password . "', '" . 
					$mysignature .
					"')";
					$lUsernameText = $username; //allow XSS by not encoding
		   		break;
			    		
		   		case "2":
		   		case "3":
		   		case "4":
	   			case "5": // This code is fairly secure
		  			/* 
		  			 * Definition: Validation
		  			 * 
		  			 * Check for data type AND
		  			 * Check for data length AND
		  			 * Check for range AND
		  			 * Check for pattern
		  			 * 
		  			 * Validation is not a "thing". It is a process.
		  			 * 
		  			 * NOTE: Input validation is excellent but not enough. The output must be
		  			 * encoded per context. For example, if output is placed in HTML,
		  			 * then HTML encode it. Blacklisting is a losing proposition. You 
		  			 * cannot blacklist everything. The business requirements will usually
		  			 * require allowing dangerous charaters. In the example here, we can 
		  			 * validate username but we have to allow special characters in passwords
		  			 * least we force weak passwords. We cannot validate the signature hardly 
		  			 * at all. The business requirements for text fields will demand most
		  			 * characters. Output encoding is the answer. Validate what you can, encode it
		  			 * all.
		  			 * 
		  			 * Concerning SQL Injection, use parameterized stored procedures. Parameterized
		  			 * queries is not good enough. You cannot use least privilege with queries.
		  			 */
		   			$query = "INSERT INTO accounts (username, password, mysignature) VALUES ('" . 
					$conn->real_escape_string($username)  ."', '" . 
					$conn->real_escape_string($password) . "', '" . 
					$conn->real_escape_string($mysignature) .
					"')";
					$lUsernameText = $Encoder->encodeForHTML($username);
		   		break;
		   	}// end switch

			$LogHandler->writeToLog($conn, "Attempting to add account for: " . $username);				
		   	
		   	if (strlen($username) == 0) {
		   		$lValidationFailed = true;
				echo '<h1 class="error-message">Username cannot be blank</h1>';
		   	}// end if
					
		   	if ($password != $confirm_password ) {
				$lValidationFailed = true;
		   		echo '<h1 class="error-message">Passwords do not match</h1>';
		   	}// end if
				
		   	if (!$lValidationFailed){
				$result = $conn->query($query);
				if ($conn->affected_rows == -1) {
			    	throw (new Exception('Error inserting records: '.$conn->error, $conn->errorno));
			    }// end if
				echo '<h2 class="success-message">Account created for ' . $lUsernameText .'. '.$conn->affected_rows.' rows inserted.</h2>';
				$LogHandler->writeToLog($conn, "Added account for: " . $username);
		   	}// end if (!$lValidationFailed)
			
		} catch (Exception $e) {
			$LogHandler->writeToLog($conn, "Failed to add account for: " . $username);
			echo $CustomErrorHandler->FormatError($e, $query);
		}// end try
			
	}// end if (isset($_POST["register-php-submit-button"])){
?>

<div id="id-registration-form-div">
	<form action="index.php?page=register.php" method="post" enctype="application/x-www-form-urlencoded">
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-bad-cred-tr" style="display: none;">
				<td colspan="2" class="error-message">
					Authentication Error: Bad user name or password
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" class="form-header">Please choose your username, password and signature</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label">Username</td>
				<td><input type="text" name="username" size="20"></td>
			</tr>
			<tr>
				<td class="label">Password</td>
				<td><input type="password" name="password" size="20"></td>
			</tr>
			<tr>
				<td class="label">Confirm Password</td>
				<td><input type="password" name="confirm_password" size="20"></td>
			</tr>
			<tr>
				<td class="label">Signature</td>
				<td><textarea rows="10" cols="50" name="my_signature"></textarea></td>
			</tr>			
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="register-php-submit-button" class="button" type="submit" value="Create Account" />
				</td>
			</tr>
			<tr><td></td></tr>
		</table>
	</form>
</div>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>For XSS:</b>XSS is easy stuff. This one shows off stored XSS (someone can
								run across it later in another app that uses the same database). Check out
								the "User Info" page for the results of this stored XSS.
								"&lt;script&gt;alert("XSS");&lt;/script&gt;" is the classic XSS demo, but 
								there are far more interesting things you could do which I plan show in a
								video later. Also, check out 
								<a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheet Sheet</a>
								for more ways you can encode XSS attacks that may allow you to get around 
								some filters.
							</li>
						  	<li><b>For SQL Injection:</b> Mostly errors, but they reveal too much information about 
								the application.
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

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/sql-injection-tutorial.inc';
		include_once './includes/cross-site-scripting-tutorial.inc';
		include_once './includes/cross-site-request-forgery-tutorial.inc';
	}// end if
?>