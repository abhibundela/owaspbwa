<?php 
	try{
		switch ($_SESSION["security-level"]){
	   		case "0": // This code is insecure
	   		case "1": // This code is insecure
	   			// DO NOTHING: This is insecure		
				$lEncodeOutput = FALSE;
			break;
		    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	  			/* 
	  			 * NOTE: Input validation is excellent but not enough. The output must be
	  			 * encoded per context. For example, if output is placed	 in HTML,
	  			 * then HTML encode it. Blacklisting is a losing proposition. You 
	  			 * cannot blacklist everything. The business requirements will usually
	  			 * require allowing dangerous charaters. In the example here, we can 
	  			 * validate username but we have to allow special characters in passwords
	  			 * least we force weak passwords. We cannot validate the signature hardly 
	  			 * at all. The business requirements for text fields will demand most
	  			 * characters. Output encoding is the answer. Validate what you can, encode it
	  			 * all.
	  			 * 
	  			 * For JavaScript, always output using innerText (IE) or textContent (FF),
	  			 * Do NOT use innerHTML. Using innerHTML is weak anyway. When 
	  			 * attempting DHTML, program with the proper interface which is
	  			 * the DOM. Thats what it is there for.
	  			 */
	   			// encode the output following OWASP standards
	   			// this will be HTML encoding because we are outputting data into HTML
				$lEncodeOutput = TRUE;
	   		break;
	   	}// end switch		
	
		if ($lEncodeOutput){
			$lPage = $Encoder->encodeForHTML($_GET['page']);
		}else{
			$lPage = $_REQUEST['page'];
		}// end if
	
    } catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
    }// end try;
?>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lLocalFileInclusionVulnerabilityBallonTip = $BubbleHintHandler->getHint("LocalFileInclusionVulnerability");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try	
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[LocalFileInclusionVulnerability]').attr("title", "<?php echo $lLocalFileInclusionVulnerabilityBallonTip; ?>");
		$('[LocalFileInclusionVulnerability]').balloon();
	});
</script>

<div class="page-title">Arbitrary File Inclusion</div>

<?php include_once './includes/back-button.inc';?>

<table style="margin-left:auto; margin-right:auto;width:600px;">
	<tr>
		<td colspan="2" class="form-header">Arbitrary File Inclusion</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr style="text-align: left;">
		<td ReflectedXSSExecutionPoint="1" colspan="2" class="label">Current Page: <?php echo $lPage; ?></td>
	</tr> 
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td LocalFileInclusionVulnerability="1" colspan="2" class="label">
			Notice that the page displayed by Mutillidae is decided 
			by the value in the "page" variable. What could possibly go wrong? 
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

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
								Parameter pollution can occur for several reasons. One is that developers
								sometimes fetch values using the "REQUEST" array. This allows the user to inject
								variables into either GET or POST and have the application process them. To
								cause parameter pollusion, a user can send parameters via POST which the developer
								thinks should be passed via the URL. The user could also pass a variable using both
								GET and POST. The application can be tricked by the bogus parameters.
							</li>
							<li>
								Although not the intended theme of this page, it does display output
								based on user input. Could it be vulnerable to Cross Site Scripting?
							</li>
							<li>
								Arbitrary File Inclusion: The page displayed in Mutillidae is determined 
								by the value of the "page" parameter. What would happen the "page" 
								parameter was changed to a filename which is on the server but not
								intended to be served? This defect can be combined with other defects. 
								For example, the "page" parameter might be able to be passed in via either
								GET or POST due to the parameters pollutition flaw. Using the parent
								traversal operator ("..") can help break out of the web server file
								folders. Also, direct file paths can be tried. For example, if Mutillidae
								is running on a Windows XP system, the following values for "page" 
								can be tried.
								<ul>
									<li>C:\boot.ini</li>
									<li>..\..\..\..\boot.ini</li>
								<ul>
							</li>
				  		</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
?>
