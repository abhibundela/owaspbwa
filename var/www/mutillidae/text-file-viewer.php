<?php 
	/* Known Vulnerabilities
	 * SQL Injection, (Fix: Use Schematized Stored Procedures)
	 * Cross Site Scripting, (Fix: Encode all output)
	 * Cross Site Request Forgery, (Fix: Tokenize transactions)
	 * Insecure Direct Object Reference, (Fix: Tokenize Object References)
	 * Denial of Service, (Fix: Truncate Log Queries)
	 * Loading of Local Files, (Fix: Tokenize Object Reference - Filename references in this case)
	 * Improper Error Handling, (Fix: Employ custom error handler)
	 * SQL Exception, (Fix: Employ custom error handler)
	 * HTTP Parameter Pollution (Fix: Scope request variables)
	 */
	try {	    	
		switch ($_SESSION["security-level"]){
   			case "0": // This code is insecure
   			case "1": // This code is insecure
				$lUseTokenization = FALSE;
	   		break;
	    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
				$lUseTokenization = TRUE;
	   		break;
	   	}// end switch ($_SESSION["security-level"])
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error in text file viewer. Cannot load file.");
	}// end try
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lHTMLandXSSandSQLInjectionPointBallonTip = $BubbleHintHandler->getHint("HTMLandXSSandSQLInjectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[HTMLandXSSandSQLInjectionPoint]').attr("title", "<?php echo $lHTMLandXSSandSQLInjectionPointBallonTip; ?>");
		$('[HTMLandXSSandSQLInjectionPoint]').balloon();
	});
</script>

<div class="page-title">Hacker Files of Old</div>

<?php include_once './includes/back-button.inc';?>

<form 	action="index.php?page=text-file-viewer.php" 
		method="post" 
		enctype="application/x-www-form-urlencoded">
		
	<table style="margin-left:auto; margin-right:auto;">
		<tr id="id-bad-cred-tr" style="display: none;">
			<td colspan="2" class="error-message">
				Validation Error: Bad Selection
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" class="form-header">Take the time to read some of these great old school hacker text files.<br />Just choose one form the list and submit.</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Text File Name</td>
			<td>
				<select size="1" name="textfile" id="id_textfile_select" HTMLandXSSandSQLInjectionPoint="1">
					<option value="<?php if ($lUseTokenization){echo 1;}else{echo 'http://www.textfiles.com/hacking/auditool.txt';}?>">Intrusion Detection in Computers by Victor H. Marshall (January 29, 1991)</option>
					<option value="<?php if ($lUseTokenization){echo 2;}else{echo 'http://www.textfiles.com/hacking/atms';}?>">An Overview of ATMs and Information on the Encoding System</option>
					<option value="<?php if ($lUseTokenization){echo 3;}else{echo 'http://www.textfiles.com/hacking/backdoor.txt';}?>">How to Hold Onto UNIX Root Once You Have It</option>
					<option value="<?php if ($lUseTokenization){echo 4;}else{echo 'http://www.textfiles.com/hacking/hack1.hac';}?>">The Basics of Hacking, by the Knights of Shadow (Intro)</option>
					<option value="<?php if ($lUseTokenization){echo 5;}else{echo 'http://www.textfiles.com/hacking/hacking101.hac';}?>">HACKING 101 - By Johnny Rotten - Course #1 - Hacking, Telenet, Life</option>
				</select>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="text-file-viewer-php-submit-button" class="button" type="submit" value="View File" />
			</td>
		</tr>
		<tr><td></td></tr>
		<tr><td class="label" colspan="2">For other great old school hacking texts, check out <a href="http://www.textfiles.com/">http://www.textfiles.com/</a>.</td></tr>
		<tr><td></td></tr>
	</table>
</form>

<?php
	try {	    	
		if (isset($_POST['text-file-viewer-php-submit-button'])){

			if (!$lUseTokenization) {
		   			/* This code is insecure. Direct object references in the form of the "textfile"
		   			 parameter give the user complete control of the input. Contrary to popular belief, 
		   			 input validation, blacklisting, etc is not the best defense. The best defenses are 
		   			 provably secure 100% of the time. For direct object references, there are two defenses.
		   			 Authorization via ACL or Entitlements is used when transaction requires authentication.
		   			 This transaction (forwarding URL) does not require authentication so the other method is used;
		   			 mapping. Mapping substitutes a harmless token for the direct object. The direct object in 
		   			 this case is the page the user is being forwarded to. We will use mapping to secure this code.
		   			 
		   			 Note: Some sites try to use validation to defend against Insecure Direct Object References.
		   			 Validation fails in many cases due to weak validators.
		   			
		   			 Note: For static links, the best defense is to simply hardcode the links in an anchor tag.
		   			 This exercise will use mapping to show how it works, but it should be recognized that 
		   			 for giving the user links to click, hardcoding is the best defense.
		   			*/
		   			
		   			/* insecure: $_REQUEST would take input from GET or POST. This can result in an HTTP Parameter Polution
		   			 * attack. If a site uses POST, then grab input from _POST. Use _GET for gets. HPP can
		   			 * occur more easily when input is ambiguous.
		   			 * 
		   			 * Also, the web is weakly typed. All data is strings. It doesnt matter what the developers
		   			 * thinks the input is (int, string, char, etc.). The fact is that HTTP is text. if the 
		   			 * "textfile" is expected to be integer, it should be validated as such. If string, then 
		   			 * validate as string.
		   			 * 
		   			 *  Definition of validation. Perform all of:
		   			 *  
		   			 *  check data type
		   			 *  check data length
		   			 *  check character set
		   			 *  check pattern
		   			 *  check range
		   			 */
					$pTextFile = $_REQUEST['textfile'];
		   			if ($pTextFile <>"") {
		   				$handle = fopen($pTextFile, "r");
		   				echo '<span ReflectedXSSExecutionPoint=\"1\" class="label">File: '.$pTextFile.'</span>';
		   				echo '<pre>';
		   				echo stream_get_contents($handle);
						echo '</pre>';
						fclose($handle);
					}// end if

		   			$LogHandler->writeToLog("Displayed contents of URL: " . $pTextFile);

			}elseif ($lUseTokenization){
		   			/* The "textfile" is expected to be integer, so validate as such. Also,
		   			 * dont use _REQUEST as this would allow a POSTed "textfile" to be sent 
		   			 * along with a URL query parameter "textfile" as well. This type of sloppy
		   			 * variable fetching can result in HTTP Parameter Pollution. 
		   			 */
		   			$pTextFile=$_POST["textfile"];
		
		   			/* We expect small int. validate positive integer between 0-9.
		   			 * Regex pattern makes sure the user doesnt send in characters that
		   			 * are not actually digits but can be cast to digits.
		   			 */	
		   			$isDigits = (preg_match("/\d{1,2}/", $pTextFile) == 1);    			
		   			if ($isDigits && $pTextFile > 0 && $pTextFile < 11){
						$lURL = "";
						/* Insecure Direct Object References are patched
						 * by removing the direct object reference all together.
						 * Web applications are "fronts" for services. Some web
						 * sites offer web pages, some offer XML, SOAP, or other
						 * services. In any case, the web site should not "give away"
						 * information about internal objects such as database IDs,
						 * redirection URLs, system file names, or application
						 * paths/configuration.
						 * 
						 * Offer the user harmless tokens instead of actual 
						 * objects. In this case, we use integers to map to
						 * the direct object, which is the forwarding URL.
						 */ 
		   				switch($pTextFile){
		   					case 1: $lURL = "http://www.textfiles.com/hacking/auditool.txt";break;
		   					case 2: $lURL = "http://www.textfiles.com/hacking/atms";break;
		   					case 3: $lURL = "http://www.textfiles.com/hacking/backdoor.txt";break;
		   					case 4: $lURL = "http://www.textfiles.com/hacking/hack1.hac";break;
		   					case 5: $lURL = "http://www.textfiles.com/hacking/hacking101.hac";break;
		   				}// end switch($pTextFile)

		   				$LogHandler->writeToLog("Displayed contents of URL: " . $lURL);
		   			
						try{
						    // open file handle
			   				$handle = fopen($lURL, "r");
			   				echo '<span ReflectedXSSExecutionPoint=\"1\" class="label">File: '.$pTextFile.'</span>';
			   				echo '<pre>';
			   				echo stream_get_contents($handle);
							echo '</pre>';
							fclose($handle);
						}catch(Exception $e){
							echo $CustomErrorHandler->FormatError($e, "Error opening file stream. Cannot load file.");
						}// end try					    
		   			}else{
		   				throw(new Exception("Expected integer input. Cannot process request. Support team alerted."));
		   			}// end if
		   	}// end if $lUseTokenization
		   	
		}// end if (isset($_POST['text-file-viewer-php-submit-button']))
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error in text file viewer. Cannot load file.");
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
						  	<li><b>For Malicious File Execution/Insecure Direct Object Reference:</b>
								Hum, looks like I\'m grabbing files from another site. Could we use this as 
								a proxy? Tip: Try the Tamper Data FireFox plugin or maybe Paros Proxy.
							</li>
						  	<li>I wonder what the traffic generated by this page looks like? Wireshark is a good tool to examine network traffic.</li>
							<li>Some code contains naive protections such as limiting the width of HTML fields. 
								If your If you find that you need more room, try using a tool like Firebug to 
								change the size of the field to be as long as you like. As you advance, 
								try using tools like netcat to make your own POST requests without having 
								to use the login web page at all.
							</li>
							<li>You can use a page normally but then simply change the parameters is Tamper Data. 
							Because Tamper Data is allowing the user to manipulate the request after the request has 
							left the browser, any HTML or JavaScript has already run and is completely useless as a 
							security measure. Any use of HTML or JavaScript for security purposes is useless anyway. 
							Some developers still fail to recognize this fact to this day.
							</li>
							<li>So if this page is grabbing files, loading them, then displaying there contents, is it possible to use this page to grab any HTML file from any site?</li>
							<li>There is nothing special about HTML files except their format. 
							Functions that load files usually do not care about the files contents. 
							(An exception might be .NET Framework\'s MSXML.loadfile() which is simply a 
							shortcut function to both load and parse XML in one call.) If the loader doesn\'t 
							care what it loads, could this page be used to load any arbitrary file? Do the 
							files have to be remote or could they be local?</li> 
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>