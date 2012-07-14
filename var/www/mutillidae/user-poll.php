
<?php
	/* Known Vulnerabilities: 
		Cross Site Scripting, 
		Cross Site Request Forgery,
		Application Exception Output,
		HTML injection,
		HTTP Parameter Pollution
	*/
		
	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
   			// DO NOTHING: This is insecure		
			$lEncodeOutput = FALSE;
			$lHTTPParameterPollutionDetected = FALSE;			
		break;
	    		
		case "2":
		case "3":
		case "4":
		case "5": // This code is fairly secure
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

			// Detect multiple params with same name (HTTP Parameter Pollution)
			$lQueryString  = explode('&', $_SERVER['QUERY_STRING']);
			$lKeys = array();
			$lPair = array();
			$lParameter = "";
			
			foreach ($lQueryString as $lParameter){
				$lPair = explode('=', $lParameter);
				array_push($lKeys, $lPair[0]);
			}

			$lCountUnique = count(array_unique($lKeys));
			$lCountTotal = count($lKeys);
			
			$lHTTPParameterPollutionDetected = ($lCountUnique < $lCountTotal);
			
   		break;
   	}// end switch		

   	$lUserChoiceMessage = "No choice selected";

   	if (!$lHTTPParameterPollutionDetected && isSet($_GET["user-poll-php-submit-button"])){
		try {

			$lUserChoiceMessage = "Your choice was {$_GET["choice"]}";
			
		} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, $query);
		}// end try
	}// end if isSet($_POST["user-poll-php-submit-button"])
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lParameterPollutionInjectionPointBallonTip = $BubbleHintHandler->getHint("ParameterPollutionInjectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[ParameterPollutionInjectionPoint]').attr("title", "<?php echo $lParameterPollutionInjectionPointBallonTip; ?>");
		$('[ParameterPollutionInjectionPoint]').balloon();
	});
</script>

<div class="page-title">User Poll</div>

<?php include_once './includes/back-button.inc';?>

<fieldset>
	<legend>User Poll</legend>
	<form 	action="index.php" 
			method="GET"
			enctype="application/x-www-form-urlencoded" 
			id="idPollForm">
		<input type="hidden" name="page" value="user-poll.php" />
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-bad-vote-tr" style="display: none;">
				<td class="error-message">
					Validation Error: HTTP Parameter Pollution Detected. Vote cannot be trusted.
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td id="id-poll-form-header-td" class="form-header">Choose Your Favorite Security Tool</td>
			</tr>
			<tr><td></td></tr>
			<tr><th class="label">Initial your choice to make your vote count</th></tr>
			<tr><td></td></tr>
			<tr>
				<td>
					<input name="choice" id="id_choice" type="radio" value="nmap" checked="checked" />&nbsp;&nbsp;nmap<br />
					<input name="choice" id="id_choice" type="radio" value="wireshark" />&nbsp;&nbsp;wireshark<br />
					<input name="choice" id="id_choice" type="radio" value="tcpdump" />&nbsp;&nbsp;tcpdump<br />
					<input name="choice" id="id_choice" type="radio" value="netcat" />&nbsp;&nbsp;netcat<br />
					<input name="choice" id="id_choice" type="radio" value="metasploit" />&nbsp;&nbsp;metasploit<br />
					<input name="choice" id="id_choice" type="radio" value="kismet" />&nbsp;&nbsp;kismet<br />
					<input name="choice" id="id_choice" type="radio" value="Cain" />&nbsp;&nbsp;Cain<br />
					<input name="choice" id="id_choice" type="radio" value="Ettercap" />&nbsp;&nbsp;Ettercap<br />
					<input name="choice" id="id_choice" type="radio" value="Paros" />&nbsp;&nbsp;Paros<br />
					<input name="choice" id="id_choice" type="radio" value="Burp Suite" />&nbsp;&nbsp;Burp Suite<br />
					<input name="choice" id="id_choice" type="radio" value="Sysinternals" />&nbsp;&nbsp;Sysinternals<br />
					<input name="choice" id="id_choice" type="radio" value="inSIDDer" />&nbsp;&nbsp;inSIDDer
				</td>
			</tr>
			<tr>
				<td class="label">
					Your Initials:<input type="text" name="initials" ParameterPollutionInjectionPoint="1" />
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td style="text-align:center;">
					<input name="user-poll-php-submit-button" class="button" type="submit" value="Submit Vote" />
				</td>
			</tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
			<tr>
				<td class="report-header" ReflectedXSSExecutionPoint="1">
				<?php 
					if (!$lEncodeOutput){
						echo $lUserChoiceMessage; 
					}else{
						echo $Encoder->encodeForHTML($lUserChoiceMessage);
					}// end if 
				?>
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<?php
	if ($lHTTPParameterPollutionDetected) {
		echo '<script>document.getElementById("id-bad-vote-tr").style.display="";</script>'; 
	}// end if ($lHTTPParameterPollutionDetected)
?>

<?php
	/* Display current user's choice */
	try {

	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try	
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
							  	HTTP Parameter Pollution involves sending in duplicate parameters 
							  	in order to take advantage of how the application server reacts to 
							  	parsing multiple parameters with the same name.
							</li>
						  	<li>
							  	Each brand of web application server acts a little different when 
							  	two or more parameters with the same name are submitted.
							</li>
							<li>This page implements "GET for POST" to make this exercise easier</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/http-parameter-pollution-tutorial.inc';
	}// end if
	
?>

<script type="text/javascript">
	try{
		document.getElementById("id_choice").focus();
	}catch(e){
		alert('Error trying to set focus on field choice: ' + e.message);
	}// end try
</script>
