<?php 
	 /* Known Vulnerabilities
	 * 
	 * Page itself is a vulnerability which shows source code. Very bad idea.
	 * Filename injection (Insecure Direct Object Reference)
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
				$lBeSmart = FALSE;
	   		break;
	    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
				$lBeSmart = TRUE;
	   		break;
	   	}// end switch ($_SESSION["security-level"])
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error in text file viewer. Cannot load file.");
	}// end try
	
	//initialize custom error handler
    require_once 'classes/DirectoryIterationHandler.php';
	if (!isset($DirectoryIterationHandler)){
		$DirectoryIterator = new DirectoryIterationHandler(".");
	}// end if
?>

<div class="page-title">Source Code Viewer</div>

<?php include_once './includes/back-button.inc';?>

<form 	action="index.php?page=source-viewer.php" 
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
			<td colspan="2" class="form-header">To see the source of the file, choose and click "View File".<br />Note that not all files are listed.</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td class="label">Source File Name</td>
			<td>
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page']?>">
				<?php 
					if($lBeSmart){
						// CSRF Defense
					    require_once 'classes/CSRFTokenStructure.php';
						$lCSRFTokenStructure = new CSRFTokenStructure($_SERVER['PHP_SELF'], $_SESSION['logged_in_user']);
						$_SESSION['source-viewer-csrf-token'] = $lCSRFTokenStructure;						
						echo '<input type="hidden" name="CSRFToken" value="'.$lCSRFTokenStructure->getCSRFToken().'"/>';
					}//end if
				?>
				<select name="phpfile" id="id_file_select">
				<?php 
					$_SESSION['source-viewer-files-array'] = "";						
					if(!$lBeSmart){
						// Just print raw filenames as values
						foreach ($DirectoryIterator as $fileInfo) {
							$lPHPFileName = $fileInfo->getFilename();
							if ($fileInfo->GetExtension() == "php" and !$fileInfo->isDot() and ($lPHPFileName != "set-up-database.php")) {
						   		echo '<option value="' . $lPHPFileName . '">' . $lPHPFileName . "</option>";
							}// end if
						}// end for each
					}else{
						// Tokenization Defense
						$aAllowedPHPFiles = array();
						$lCounter = 0;
						foreach ($DirectoryIterator as $fileInfo) {
							$lPHPFileName = $fileInfo->getFilename();
							if ($fileInfo->GetExtension() == "php" and !$fileInfo->isDot() and ($lPHPFileName != "set-up-database.php")) {
						   		echo '<option value="' . $lCounter . '">' . $lPHPFileName . "</option>";
								$aAllowedPHPFiles[$lCounter]=$lPHPFileName;
								$lCounter += 1;							
							}// end if
						}// end for each
						$_SESSION['source-viewer-files-array'] = $aAllowedPHPFiles;
					}// end if
				?>
				</select>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="source-file-viewer-php-submit-button" class="button" type="submit" value="View File" />
			</td>
		</tr>
		<tr><td></td></tr>
	</table>
</form>

<?php
	try {	    	
		if (isset($_POST['source-file-viewer-php-submit-button'])){

			if (!$lBeSmart) {
	   			/* This code is insecure. Direct object references in the form of the "file"
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
	   			 * "phpfile" is expected to be integer, it should be validated as such. If string, then 
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

				// CSRF Defense
			    //NOT YET IMPLEMENTED
			    // Check that the token for this page and this request matches
			    //Token good for one request from one page only
				
				// Grab inputs
				$pPHPFile = $_REQUEST['phpfile'];
				
				// Insecure Mode: Skip validation
				$lFilename = $pPHPFile;

				$LogHandler->writeToLog($conn, "Page source-viewer.php loaded file: " . $lFilename);

			}elseif ($lBeSmart){
	   			/* The "phpfile" is expected to be integer, so validate as such. Also,
	   			 * dont use _REQUEST as this would allow a POSTed "phpfile" to be sent 
	   			 * along with a URL query parameter "phpfile" as well. This type of sloppy
	   			 * variable fetching can result in HTTP Parameter Pollution. 
	   			 */
	   			$pPHPFile=$_POST["phpfile"];
	   			$laAllowedPHPFiles = $_SESSION['source-viewer-files-array'];
	   			$lArrayCount = count($laAllowedPHPFiles);
	   			
	   			if (!is_array($laAllowedPHPFiles)){
	   				throw new Exception('Validation Failed: Did not receive allowed values array.');
	   			}// end if

	   			if (!($lArrayCount > 0)){
	   				throw new Exception('Validation Failed: Array is empty.');
	   			}// end if
	   			
	   			/* We expect small int. validate positive integer between 0-9.
	   			 * Regex pattern makes sure the user doesnt send in characters that
	   			 * are not actually digits but can be cast to digits.
	   			 */	
	   			$isDigits = (preg_match("/\d{1,4}/", $pPHPFile) == 1);    			
	   			if (!($isDigits && $pPHPFile >= 0 && $pPHPFile < $lArrayCount)){
	   				throw(new Exception("Expected integer input. Cannot process request. Support team alerted."));
	   			}// end if

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
				$lFilename = $laAllowedPHPFiles[$pPHPFile];

				$LogHandler->writeToLog($conn, "Page source-viewer.php loaded file: " . $lFilename);				
		   	}// end if $lBeSmart

		   	// try to display the file
		   	try {
	   			echo '<p class="label">File: '.$lFilename.'</p>';
	   			echo '<pre>';
				highlight_file($lFilename);
				echo '</pre>';
			}catch(Exception $e){
				echo $CustomErrorHandler->FormatError($e, "Error trying to print file. Cannot load file.");
			}// end try
					   	
		}// end if (isset($_POST['source-file-viewer-php-submit-button']))
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error in source file viewer. Cannot load file.");
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
									So, this script shows the source of most of the php files in this site, but not 
									all. Giving even this level of information about the application is a bad idea.
									What would be another good script to checkout? A script that you see in the 
									menu list on the left, but not the dropdown box? Using GET instead of POST
									makes this one easier to abuse, but with "Tamper Data" or "Paros" you could
									still screw with POST data.
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
							files have to be PHP source files or could they be any local file?</li> 
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section
?>
