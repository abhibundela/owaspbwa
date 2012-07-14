<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure
    		case "1": // This code is insecure
    			$lFormMethod = "GET";
				$lUserInfoSubmitButton = $_REQUEST["user-info-php-submit-button"];
    		break;
	    		
			case "2":
			case "3":
			case "4":
    		case "5": // This code is fairly secure
    			$lFormMethod = "POST";
    			$lUserInfoSubmitButton = $_POST["user-info-php-submit-button"];
    		break;
    	}//end switch
   	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
   	}// end try;
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lSQLInjectionPointBallonTip = $BubbleHintHandler->getHint("SQLInjectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[SQLInjectionPoint]').attr("title", "<?php echo $lSQLInjectionPointBallonTip; ?>");
		$('[SQLInjectionPoint]').balloon();
	});
</script>

<div class="page-title">View your details</div>

<?php include_once './includes/back-button.inc';?>

<form 	action="./index.php?page=user-info.php"
		method="<?php echo $lFormMethod; ?>" 
		enctype="application/x-www-form-urlencoded" >
	<input type="hidden" name="page" value="user-info.php" />	
	<table style="margin-left:auto; margin-right:auto;">
		<tr id="id-bad-cred-tr" style="display: none;">
			<td colspan="2" class="error-message">
				Authentication Error: Bad user name or password
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" class="form-header">Please enter username and password<br/> to view account details</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Name</td>
			<td><input SQLInjectionPoint="1" type="text" name="username" size="20"></td>
		</tr>
		<tr>
			<td class="label">Password</td>
			<td><input SQLInjectionPoint="1" type="password" name="password" size="20"></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="user-info-php-submit-button" class="button" type="submit" value="View Account Details" />
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

<?php
	if (isset($lUserInfoSubmitButton) && !empty($lUserInfoSubmitButton)){

		try {	    	
		
	    	switch ($_SESSION["security-level"]){
	    		case "0": // This code is insecure
	    		case "1": // This code is insecure
	    			//Accept data from either GET or POST to make this target soft for SQLMAP
					$lUsername = $_REQUEST["username"];
					$lPassword = $_REQUEST["password"];
			
					try {
						$LogHandler->writeToLog("Recieved request to display user information for: " . $lUsername);					
					} catch (Exception $e) {
						//do nothing
					}
	    			
	    			$lQueryString  = "SELECT * FROM accounts WHERE username='". 
	    			$lUsername .
	    			"' AND password='" . 
	    			$lPassword . 
	    			"'";
					$lEncodeOutput = FALSE;
	    		break;
	    		
				case "2":
				case "3":
				case "4":
	    		case "5": // This code is fairly secure
	    			/* 
	    			 * Note: While escaping works ok in some case, it is not the best defense.
	    			 * Using stored procedures is a much stronger defense.
	    			 */ 
					$lUsername = $_POST["username"];
					$lPassword = $_POST["password"];
			
					$LogHandler->writeToLog("Recieved request to display user information for: " . $lUsername);
	    			
					$lQueryString  = "SELECT * FROM accounts WHERE username='".
	    			$MySQLHandler->escapeDangerousCharacters($lUsername).
	    			"' AND password='".
	    			$MySQLHandler->escapeDangerousCharacters($lPassword).
	    			 "'";

		  			/* 
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
		  			 */
		   			// encode the output following OWASP standards
		   			// this will be HTML encoding because we are outputting data into HTML
	    			$lEncodeOutput = TRUE;
	    		break;
	    	}// end switch

    		$lQueryStringResult = $MySQLHandler->executeQuery($lQueryString);
    		
			if ($lQueryStringResult->num_rows > 0) {
				echo '<p class="report-header">Results for '.$row->username.'. '.$lQueryStringResult->num_rows.' records found.<p>';
			    while($row = $lQueryStringResult->fetch_object()){
			    	
			    	try {
						$LogHandler->writeToLog("Displayed user-information for: " . $row->username);				
			    	} catch (Exception $e) {
			    		// do nothing
			    	}
					
					if(!$lEncodeOutput){
						$lUsername = $row->username;
						$lPassword = $row->password;
						$lSignature = $row->mysignature;
					}else{
						$lUsername = $Encoder->encodeForHTML($row->username);
						$lPassword = $Encoder->encodeForHTML($row->password);
						$lSignature = $Encoder->encodeForHTML($row->mysignature);			
					}// end if
					
					echo "<span style=\"font-weight:bold;\">Username=</span><span ReflectedXSSExecutionPoint=\"1\">{$lUsername}</span><br/>";
					echo "<span style=\"font-weight:bold;\">Password=</span><span ReflectedXSSExecutionPoint=\"1\">{$lPassword}</span><br/>";
					echo "<span style=\"font-weight:bold;\">Signature=</span><span ReflectedXSSExecutionPoint=\"1\">{$lSignature}</span><br/><br/>";
				}// end while

			} else {
				echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
			}// end if ($lQueryStringResult->num_rows > 0)
				
    	} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, $lQueryString);
       	}// end try;
    	
	}// end if (isset($_POST)) 
?>

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
							<li>You can use the login page normally but then simply change the parameters with Tamper Data. 
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
							<li>The first step is not gaining access but recon. Gaining access is actually fairly late
							in the process. To do recon with respect to SQL injection, try to cause errors to see how the 
							application reacts. Some applications (many actually) fail to install custom error pages
							as required. Try to find out what database is running then inject special characters for that database. 
							After special characters, try fuzzing major characters sets. Finally, if the application
							still has not produced useful error messages, then try timing attacks. Your goal is to get a
							reaction. Well built sites wont act differently even when a database error occurs.
							</li>
							<li>After performing error recon and blind timing attacks, an entry point may be found to begin
							data extraction. Initially the best data to extract is data about the database itself. Try to 
							answer the questions of what tables, views, columns, functions, procedures, system procedures,
							and other objects exist.
							
							From the MySQL reference documentation: Metadata is data about the data, such as the name of a database or table, the data type of a column, or access privileges. Other terms that sometimes are used for this information are data dictionary and system catalog.
							INFORMATION_SCHEMA is the information database, the place that stores information about all the other databases that the MySQL server maintains. Inside INFORMATION_SCHEMA there are several read-only tables. They are actually views, not base tables, so there are no files associated with them.
							In effect, we have a database named INFORMATION_SCHEMA, although the server does not create a database directory with that name. It is possible to select INFORMATION_SCHEMA as the default database with a USE statement, but it is possible only to read the contents of tables. You cannot insert into them, update them, or delete from them.
							
							Defense: Web apps should not actual have access to any tables or other objects. Web apps should only have one privilege; EXECUTE.
							Even then, the web app should only be able to execute on one schema and that schema should only contain the procedures
							needed explicitly by the application. The procs will still have access to the tables in the table schema because
							databases run procs with the authroity of the owner; not the caller. It works as if the database sets the "suid" bit on procs. 
							Oracle and SQL Server do allow settings which alter this default behavior; for example causing the procs to run as the 
							caller. 
							</li>
							<li>MySQL information schema tables that would likely be useful to recon:
								<ul>
								<li>TABLES Table</li>
								<li>COLUMNS Table</li>
								<li>USER_PRIVILEGES Table</li>
								<li>ROUTINES Table</li>
								<li>VIEWS Table</li>
								<li>TRIGGERS Table</li>
								</ul>
							</li>
							<li>Attempt to recon what type of database is running then study the system functions, tables,
							and procedures that come with that platform. The built-in functions can come in handy.
							<li>
							<li>SQL Servers accept batch queries but MySQL and Oracle do not. However, Oracle is susceptable
							to all forms of SQL Injection all the same and provides the greatest number of system
							functions to exploit. MySQL has fewer functions but the ones provided are very useful.
							</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/sql-injection-tutorial.inc';
	}// end if	

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>