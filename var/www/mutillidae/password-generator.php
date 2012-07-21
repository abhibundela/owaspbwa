<?php
	try {	    	
    	switch ($_SESSION["security-level"]){
	    		case "0": // This code is insecure.
	    		case "1": // This code is insecure.
					// Grab inputs insecurely. $_REQUEST allows any input paramter. Not just POST.
					$lUsernameForJS = $_REQUEST["username"]; // allow javascript and xss injection
	    		break;

	    		case "2":
	    		case "3":
	    		case "4":
	    		case "5": // This code is fairly secure
					/* Protect against one form of patameter pollution 
					 * by grabbing inputs only from GET parameters. */ 
					$lUsernameForJS = $Encoder->encodeForJavaScript($_GET["username"]);
	    		break;
	    	}// end switch
	    	
	    	$lPasswordJSMessage = "This password is for {$lUsernameForJS}";
	    	
    	} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, "Input: " . $lUsernameForHTML);
    	}// end try
?>

<?php 
	try{
   		$lHTMLEventReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("HTMLEventReflectedXSSExecutionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[HTMLEventReflectedXSSExecutionPoint]').attr("title", "<?php echo $lHTMLEventReflectedXSSExecutionPointBallonTip; ?>");
		$('[HTMLEventReflectedXSSExecutionPoint]').balloon();
	});
</script>

<script>
	function onSubmitOfGeneratorForm(/*HTMLFormElement*/ theForm){
		try{

		    var lPasswordText = "";
		    var lPasswordCharset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()_-+=[]{}\|;',./:?";

		    for( var i=0; i < 15; i++ ){
		    	lPasswordText += lPasswordCharset.charAt(Math.floor(Math.random() * lPasswordCharset.length));
		    }// end for i
			
			document.getElementById("idPasswordInput").innerHTML = "Password: <span style=\"color:red;border-width:1px;border-color:black;\">" + lPasswordText + "</span>";
			document.getElementById("idPasswordTableRow").style.display = "";
			return false;

		}catch(e){
			alert("Error: " + e.message);
		}// end catch
	}// end function onSubmitOfGeneratorForm(/*HTMLFormElement*/ theForm)
</script>

<div class="page-title">Password Generator</div>

<?php include_once './includes/back-button.inc';?>

<div id="id-generator-form-div">
	<form 	enctype="application/x-www-form-urlencoded" 
			id="idGeneratorForm">
		<table style="margin-left:auto; margin-right:auto;">
			<tr>
				<td class="form-header">Password Generator</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label"  style="text-align: center;">
					Making strong passwords is important.
					<br/>
					Click the button below to generate a password.
				</td>
			</tr>
			<tr><td></td></tr>
			<tr style="text-align: center;">
				<td id="idUsernameInput" HTMLEventReflectedXSSExecutionPoint="1" class="label"></td>
			</tr>
			<tr id="idPasswordTableRow" style="display: none;">
				<td class="label" id="idPasswordInput"></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td style="text-align:center;">
					<input name="password-generator-php-submit-button" class="button" type="button" value="Generate Password" onclick="onSubmitOfGeneratorForm(this.form);" />
				</td>
			</tr>
			<tr><td></td></tr>
		</table>
	</form>
</div>

<script>
	try{
		document.getElementById("idUsernameInput").innerHTML = "<?php echo $lPasswordJSMessage; ?>";
	}catch(e){
		alert("Error: " + e.message);
	}// end catch
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
						  	<li>
							  	<b>JavaScript Injection:</b>JS injection is closely related to HTML injection and 
							  	Cross Site Scripting. All are a violation of context in which the input is able
							  	to break out of the current context and switch to another context. Alternatively
							  	an injection may stay in the current context but modify the source code.
							</li>
						  	<li>
							  	An example of breaking context is injecting script tags into HTML output. The developer
							  	believes the context should be HTML (perhaps a table), but the input of 
							  	script tags (with embedded script) causes the browser to stop processing HTML
							  	and switch to processing script. The context switch occurs when the browser 
							  	stops executing the HTML instructions and instead executes the JS.
							</li>
							<li>
								Injection within context could be injecting HTML into HTML output. Although
								the page source code is altered, the context remains the same.
							</li>
							<li>
								The defect on this page which allows JS injection does not break out of context.
							</li>
							<li>
								The JS that builds the password is a diversion. The injection point is elsewhere.
							</li>
							<li>
								To find the injection, you need a canary. First, identify the input. Does this page 
								take input from a form, URL parameter, cookie, or other input? HTTPFox is a good tool
								to see all the input as you "GET" a page (the request) and all the output the server 
								responds with (the response). 
							</li>
							<li>
								Once you find the page input, try injecting a simple canary like "CANARY-INPUT-1"
								then search the resulting page to see where the canary showed up. 
							</li>
							<li>
								Searching for a canary on the actual browser output is not a good idea. Use the browsers
								"view source" to see the "real" response. Tools like HTTPFox are great for this as well.
								Tools with more features like Burp are even better but have more of a learning curve.
								Burp will remember the source of each page you visit as you spider the site.
							</li>
							<li>
								Once the canary(ies) is located, identify what characters need to be injected to 
								"end" the current instruction. Identify the characters that are needed to block out
								any instruction that comes after the canary. Put your injection in the middle.
							</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section
?>