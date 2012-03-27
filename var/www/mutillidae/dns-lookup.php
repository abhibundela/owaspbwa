<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure. No input validation is performed.
				// Grab inputs insecurely. $_REQUEST allows any input parameter. Not just POST.
				$targethost = $_REQUEST["target_host"]; // allow command injection
    			$targethost_validated=true; 			// do not perform validation
				$lTargetHostText = $targethost; 		//allow XSS by not encoding output
				$lEnableJavaScriptValidation = FALSE;
    		break;

    		case "1": // This code is insecure. No input validation is performed.
				// Grab inputs insecurely. $_REQUEST allows any input parameter. Not just POST.
				$targethost = $_REQUEST["target_host"]; // allow command injection
    			$targethost_validated=true; 			// do not perform validation
				$lTargetHostText = $targethost; 		//allow XSS by not encoding output
				$lEnableJavaScriptValidation = TRUE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
				/* Protect against one form of patameter pollution 
				 * by grabbing inputs only from POST parameters. */ 
				$targethost = $_POST["target_host"];
				
				/* Protect against command injection. 
				 * We validate that an IP is 4 octets, IPV6 fits the pattern, and that domain name is IANA format */
    			$targethost_validated = preg_match(IPV4_REGEX_PATTERN, $targethost) || preg_match(DOMAIN_NAME_REGEX_PATTERN, $targethost) || preg_match(IPV6_REGEX_PATTERN, $targethost);
    			
    			/* Protect against XSS by output encoding */
    			$lTargetHostText = $Encoder->encodeForHTML($targethost);
    			
    			$lEnableJavaScriptValidation = TRUE;
    		break;
    	}// end switch
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error setting up configuration on page dns-lookup.php");
	}// end try	
?>

<div class="page-title">DNS Lookup</div>

<?php include_once './includes/back-button.inc';?>

<!-- BEGIN HTML OUTPUT  -->
<script type="text/javascript">
	var onSubmitBlogEntry = function(/* HTMLForm */ theForm){

		<?php 
		if($lEnableJavaScriptValidation){
			echo "var lOSCommandInjectionPattern = /[;&]/;";
		}else{
			echo "var lOSCommandInjectionPattern = /*/;";
		}// end if

		if($lEnableJavaScriptValidation){
			echo "var lCrossSiteScriptingPattern = /[<>=()]/;";
		}else{
			echo "var lCrossSiteScriptingPattern = /*/;";
		}// end if
		?>
		
		if(theForm.target_host.value.search(lOSCommandInjectionPattern) > -1){
			alert("Ampersand and semi-colon are not allowed.\n\nDon\'t listen to security people. Everyone knows if we just filter dangerous characters, XSS is not possible.\n\nWe use JavaScript defenses combined with filtering technology.\n\nBoth are such great defenses that you are stopped in your tracks.");
			return false;
		}else if(theForm.target_host.value.search(lCrossSiteScriptingPattern) > -1){
			alert("Characters used in cross-site scripting are not allowed.\n\nDon\'t listen to security people. Everyone knows if we just filter dangerous characters, XSS is not possible.\n\nWe use JavaScript defenses combined with filtering technology.\n\nBoth are such great defenses that you are stopped in your tracks.");
			return false;			
		}else{
			return true;
		}// end if
	};// end JavaScript function onSubmitBlogEntry()
</script>

<form 	action="index.php?page=dns-lookup.php" 
			method="post" 
			enctype="application/x-www-form-urlencoded" 
			onsubmit="return onSubmitBlogEntry(this);"
			id="idDNSLookupForm">		
	<table style="margin-left:auto; margin-right:auto;">
		<tr id="id-bad-cred-tr" style="display: none;">
			<td colspan="2" class="error-message">
				Error: Invalid Input
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" class="form-header">Who would you like to do a DNS lookup on?<br/><br/>Enter IP or hostname</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Hostname/IP</td>
			<td><input type="text" id="idTargetHostInput" name="target_host" size="20"></td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="dns-lookup-php-submit-button" class="button" type="submit" value="Lookup DNS" />
			</td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
	</table>
</form>

<script type="text/javascript">
<!--
	try{
		document.getElementById("idTargetHostInput").focus();
	}catch(/*Exception*/ e){
		alert("Error trying to set focus: " + e.message);
	}// end try
//-->
</script>

<?php
	if (isset($_POST["dns-lookup-php-submit-button"])){
	    try{
	    	if ($targethost_validated){
	    		echo '<p class="report-header">Results for '.$lTargetHostText.'<p>';
    			echo '<pre class="report-header" style="text-align:left;">';
    			echo shell_exec("nslookup " . $targethost);
				echo '</pre>';
				$LogHandler->writeToLog($conn, "Executed operating system command: nslookup " . $lTargetHostText);
	    	}else{
	    		echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
	    	}// end if ($targethost_validated){

    	}catch(Exception $e){
			echo $CustomErrorHandler->FormatError($e, "Input: " . $targethost);
    	}// end try
    	
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
						  	<li>
						  		<b>For Command Injection Flaws:</b> Directly building a command to use in a
								shell? Bad idea! Try command separators like ; and && depending on if 
								you are using Linux or Windows respectively. 
							</li>
							<li>Windows uses "&&" to link commands.</li>
							<li>Linux uses "&&" to link commands and ";" as a command separater.</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/command-injection-tutorial.inc';
	}// end if
	
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>