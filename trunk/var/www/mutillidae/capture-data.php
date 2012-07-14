<?php
	 /* Known Vulnerabilities
	 * 
	 * SQL Injection, (Fix: Use Schematized Stored Procedures)
	 * Cross Site Scripting, (Fix: Encode all output)
	 * Cross Site Request Forgery, (Fix: Tokenize transactions)
	 * Denial of Service, (Fix: Truncate Log Queries)
	 * Improper Error Handling, (Fix: Employ custom error handler)
	 * SQL Exception, (Fix: Employ custom error handler)
	 */

	/* ------------------------------------------
	 * Constants used in application
	 * ------------------------------------------ */
	include_once './includes/constants.php';

	/* ------------------------------------------
	 * initialize OWASP ESAPI for PHP
	 * ------------------------------------------ */
    require_once 'owasp-esapi-php/src/ESAPI.php';
	if (!isset($ESAPI)){
		$ESAPI = new ESAPI('owasp-esapi-php/src/ESAPI.xml');
		$Encoder = $ESAPI->getEncoder();
	}// end if
	
	/* ------------------------------------------
	 * initialize custom error handler
	 * ------------------------------------------ */
    require_once 'classes/CustomErrorHandler.php';
	if (!isset($CustomErrorHandler)){
		$CustomErrorHandler = 
		new CustomErrorHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if	

	/* ------------------------------------------
 	* initialize log error handler
 	* ------------------------------------------ */
    require_once 'classes/LogHandler.php';
	if (!isset($LogHandler)){
		$LogHandler = 
		new LogHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if	

	/* ------------------------------------------
 	* initialize MySQL handler
 	* ------------------------------------------ */
	require_once 'classes/MySQLHandler.php';
	if (!is_object($MySQLHandler)){
		$MySQLHandler = new MySQLHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if	
	
	/* ------------------------------------------
 	* initialize balloon-hint handler
 	* ------------------------------------------ */
	require_once 'classes/BubbleHintHandler.php';
	if (!is_object($BubbleHintHandler)){
		$BubbleHintHandler = new BubbleHintHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
	}// end if	
		
	require_once 'classes/ClientInformationHandler.php';
	$lClientInformationHandler = new ClientInformationHandler();
	
	/* Grab as much information about visiting browser as possible. Most of this
	 * is available in the HTTP request header.
	 */
	$lClientHostname = $lClientInformationHandler->getClientHostname();
	$lClientIP = $lClientInformationHandler->getClientIP();
	$lClientUserAgentString = $lClientInformationHandler->getClientUserAgentString();
	$lClientReferrer = $lClientInformationHandler->getClientReferrer();
	$lClientPort = $lClientInformationHandler->getClientPort();
	
	if ($lProtectAgainstSQLInjection) {
		$lClientHostname = $MySQLHandler->escapeDangerousCharacters($lClientHostname);
		$lClientUserAgentString = $MySQLHandler->escapeDangerousCharacters($lClientUserAgentString);
		$lClientReferrer = $MySQLHandler->escapeDangerousCharacters($lClientReferrer);
	}// end if $lProtectAgainstSQLInjection	

	$lCapturedData = $MySQLHandler->escapeDangerousCharacters($lCapturedData);
	
	try {	    	
		switch ($_SESSION["security-level"]){
	   		case "0": // this code is insecure
	   		case "1": // this code is insecure 
				$lProtectAgainstSQLInjection = FALSE;
	   		break;//case "0"
	    		
	   		case "2":
	   		case "3":
	   		case "4":	
	   		case "5": // This code is fairly secure
				$lProtectAgainstSQLInjection = TRUE;
	   		break;//case "5"
	   	}// end switch ($_SESSION["security-level"])
		
	   	// Declare a temp varaible to hold our collected data
	   	$lCapturedData = "";
	   	
	   	// Capture GET and POST parameters
		foreach ( $_REQUEST as $k => $v ) {
			$lCapturedData .= "$k = $v" . PHP_EOL;
		}// end for each
	   	
		//Capture cookies
		foreach ( $_COOKIE as $k => $v ) {
			$lCapturedData .= "$k = $v" . PHP_EOL;
		}// end for each

		if ($lProtectAgainstSQLInjection) {
			$lClientHostname = $MySQLHandler->escapeDangerousCharacters($lClientHostname);
			$lCapturedData = $MySQLHandler->escapeDangerousCharacters($lCapturedData);
			$lClientUserAgentString = $MySQLHandler->escapeDangerousCharacters($lClientUserAgentString);
			$lClientReferrer = $MySQLHandler->escapeDangerousCharacters($lClientReferrer);
		}// end if $lProtectAgainstSQLInjection	
	
		$lQueryString = 
			"INSERT INTO captured_data(" . 
				"ip_address, hostname, port, user_agent_string, referrer, data, capture_date" . 
			") VALUES ('".
				$lClientIP . "', '".
				$lClientHostname . "', '".
				$lClientPort . "', '".
				$lClientUserAgentString . "', '".
				$lClientReferrer . "', '".
				$lCapturedData . "', ".
				" now()" .
			")";

		$result = $MySQLHandler->executeQuery($lQueryString);
		
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
	}// end try		

	$lFilename = "captured-data.txt";
	try{
		$lmDateTime = new DateTime();
		$lCurrentDateTimeArray = getdate();
		$lCurrentDateTime = date('m-d-Y H:i:s', mktime($lCurrentDateTimeArray['hours'], $lCurrentDateTimeArray['minutes'], $lCurrentDateTimeArray['seconds'], $lCurrentDateTimeArray['mon'], $lCurrentDateTimeArray['mday'], $lCurrentDateTimeArray['year']));
		$lFileHandle = fopen($lFilename, "a");		
		fwrite($lFileHandle, PHP_EOL);
		fwrite($lFileHandle, "--------------------------------------------------".PHP_EOL);
		fwrite($lFileHandle, "Client IP: ".$lClientIP.PHP_EOL);
		fwrite($lFileHandle, "Timestamp: ".$lCurrentDateTime." GMT".PHP_EOL);
		fwrite($lFileHandle, "--------------------------------------------------".PHP_EOL);
		fwrite($lFileHandle, "Client Hostname: ".$lClientHostname.PHP_EOL);
		fwrite($lFileHandle, "Client User Agent: ".$lClientUserAgentString.PHP_EOL);
		fwrite($lFileHandle, "Client Referrer: ".$lClientReferrer.PHP_EOL);
		fwrite($lFileHandle, "Client Port: ".$lClientPort.PHP_EOL);
		fwrite($lFileHandle, "Captured Data: ".$lCapturedData);
		fwrite($lFileHandle, "--------------------------------------------------".PHP_EOL);
		fwrite($lFileHandle, PHP_EOL);
		fclose($lFileHandle);
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error trying to save captured data from capture.php into file " . $lFilename);
	}// end try
	
	try {
		$LogHandler->writeToLog("Captured user data");
		$LogHandler->writeToLog("Captured Client IP: ".$lClientIP);
		$LogHandler->writeToLog("Captured Client Hostname: ".$lClientHostname);
		$LogHandler->writeToLog("Captured Client User Agent: ".$lClientUserAgentString);
		$LogHandler->writeToLog("Captured Client Referrer: ".$lClientReferrer);
		$LogHandler->writeToLog("Captured Client Port: ".$lClientPort);
		$LogHandler->writeToLog("Captured Data: ".$lCapturedData);		
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try
	
    /* ------------------------------------------
     * LOG USER VISIT TO PAGE
     * ------------------------------------------ */
	require_once ("log-visit.php");
    
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

<link rel="stylesheet" type="text/css" href="./styles/global-styles.css" />
<div class="page-title">Capture Data</div>

<?php include_once './includes/back-button.inc';?>

<!-- BEGIN HTML OUTPUT  -->

<table style="margin-left:auto; margin-right:auto; width: 600px;">
	<tr>
		<td class="form-header">Data Capture Page</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td SQLInjectionPoint="1">
			This page is designed to capture any parameters sent and store them in a file and a database table. It loops through
			the POST and GET parameters and records them to a file named <?php print $lFilename; ?>. On this system, the 
			file should be found at <?php print pathinfo($_SERVER["SCRIPT_FILENAME"], PATHINFO_DIRNAME) . "/" . $lFilename; ?>. The page
			also tries to store the captured data in a database table named captured_data and <a href="./index.php?page=show-log.php">logs</a> the captured data. There is another page named
			<a href="index.php?page=captured-data.php">captured-data.php</a> that attempts to list the contents of this table.
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<th ReflectedXSSExecutionPoint="1">
			The data captured on this request is: <?php print $lCapturedData; ?>
		</th>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td style="text-align:center;">
			Would it be possible to hack the hacker? Assume the hacker will view the captured requests with a web browser.
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<div>&nbsp;</div>
			<div>&nbsp;</div>
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<br/>
						<span class="report-header">Cross Site Scripting</span>
						<br/><br/>
						This page is the easiest in the site to inject XSS. The page reflects any input. This input
						could be from the Cookies, and URL query parameter, or any POSTed parameter.
						<br/><br/>
						<span class="report-header">Cross Site Scripting Via URL query parameters</span>
						<br/><br/>
						Try make up any URL query parameter and inject a script. In reality, just inject a script 
						as the variable. This page is very easy to inject. 
						<br/><br/>
						<span class="report-header">Cross Site Scripting Via POST parameters</span>
						<br/><br/>
						Use Burp-Suite to create POST parameters. Make one of the parameters a cross site script.
						<br/><br/>
						<span class="report-header">Cross Site Scripting Via Cookie</span>
						<br/><br/>
						Use Cookie Manager or Burp-Suite to create a cross site script. When this page
						prints the value of the cookie to the screen, the script will execute.
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>
