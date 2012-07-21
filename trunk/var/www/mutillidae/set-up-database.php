<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="./styles/global-styles.css" />
	</head>
	<body>
		<div>&nbsp;</div>
		<div class="page-title">Setting up the database...</div><br /><br />
		<span style="text-align: center;">
			<div>&nbsp;</div>
			<div class="label">If you see no error messages, it should be done.</div>
			<div>&nbsp;</div>
			<div class="label"><a href="index.php">Continue back to the frontpage.</a></div>
		</span>
		<br /><br />
		<script>
			try{
				window.sessionStorage.clear();
				window.localStorage.clear();
			}catch(e){
				alert("Error clearing HTML 5 Local and Session Storage" + e.toString());
			};
		</script>
		<div class="database-success-message">HTML 5 Local and Session Storage cleared unless error popped-up already.</div>
<?php

//initialize custom error handler
require_once 'classes/CustomErrorHandler.php';
if (!isset($CustomErrorHandler)){
	$CustomErrorHandler = 
	new CustomErrorHandler("owasp-esapi-php/src/", 0);
}// end if

require_once 'classes/MySQLHandler.php';
$MySQLHandler = new MySQLHandler("owasp-esapi-php/src/", $_SESSION["security-level"]);
$lErrorDetected = FALSE;

try{
	$MySQLHandler->openDatabaseConnection();
	echo "<div class=\"database-success-message\">Connected to MySQL database</div>";	
		
	try{
		$lQueryString = "DROP DATABASE IF EXISTS mutillidae";
		$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
		if (!$lQueryResult) {
			$lErrorDetected = TRUE;
		}else{
			echo "<div class=\"database-success-message\">Executed query 'DROP DATABASE IF EXISTS' with result ".$lQueryResult."</div>";
		}// end if
	}catch(Exception $e){
		//DO NOTHING. THIS IS HERE DUE TO A MYSQL BUG THAT HAS NOT BEEN PATCHED YET.
	}//end try
	
	$lQueryString = "CREATE DATABASE mutillidae";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE DATABASE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "USE mutillidae";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'USE DATABASE' with result ".$lQueryResult."</div>";
	}// end if
			
	$lQueryString = 'CREATE TABLE blogs_table( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'blogger_name TEXT, '.
	         'comment TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';	
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = 'CREATE TABLE accounts( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'username TEXT, '.
	         'password TEXT, '.
			 'mysignature TEXT, '.
			 'is_admin VARCHAR(5),'.
			 'PRIMARY KEY(cid))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = 'CREATE TABLE hitlog( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'hostname TEXT, '.
	         'ip TEXT, '.
			 'browser TEXT, '.
			 'referer TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';		 
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "INSERT INTO accounts (username, password, mysignature, is_admin) VALUES
		('admin', 'admin', 'Monkey!', 'TRUE'),
		('adrian', 'somepassword', 'Zombie Films Rock!', 'TRUE'),
		('john', 'monkey', 'I like the smell of confunk', 'FALSE'),
		('jeremy', 'password', 'd1373 1337 speak', 'FALSE'),
		('bryce', 'password', 'I Love SANS', 'FALSE'),
		('samurai', 'samurai', 'Carving Fools', 'FALSE'),
		('jim', 'password', 'Jim Rome is Burning', 'FALSE'),
		('bobby', 'password', 'Hank is my dad', 'FALSE'),
		('simba', 'password', 'I am a cat', 'FALSE'),
		('dreveil', 'password', 'Preparation H', 'FALSE'),
		('scotty', 'password', 'Scotty Do', 'FALSE'),
		('cal', 'password', 'Go Wildcats', 'FALSE'),
		('john', 'password', 'Do the Duggie!', 'FALSE'),
		('kevin', '42', 'Doug Adams rocks', 'FALSE'),
		('dave', 'set', 'Bet on S.E.T. FTW', 'FALSE'),
		('user', 'user', 'User Account', 'FALSE'),
		('ed', 'pentest', 'Commandline KungFu anyone?', 'FALSE')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString ="INSERT INTO `blogs_table` (`cid`, `blogger_name`, `comment`, `date`) VALUES
		(1, 'adrian', 'Well, I''ve been working on this for a bit. Welcome to my crappy blog software. :)', '2009-03-01 22:26:12'),
		(2, 'adrian', 'Looks like I got a lot more work to do. Fun, Fun, Fun!!!', '2009-03-01 22:26:54'),
		(3, 'anonymous', 'An anonymous blog? Huh? ', '2009-03-01 22:27:11'),
		(4, 'ed', 'I love me some Netcat!!!', '2009-03-01 22:27:48'),
		(5, 'john', 'Listen to Pauldotcom!', '2009-03-01 22:29:04'),
		(6, 'jeremy', 'Why give users the ability to get to the unfiltered Internet? It''s just asking for trouble. ', '2009-03-01 22:29:49'),
		(7, 'john', 'Chocolate is GOOD!!!', '2009-03-01 22:30:06'),
		(8, 'admin', 'Fear me, for I am ROOT!', '2009-03-01 22:31:13'),
		(9, 'dave', 'Social Engineering is woot-tastic', '2009-03-01 22:31:13'),
		(10, 'kevin', 'Read more Douglas Adams', '2009-03-01 22:31:13'),
		(11, 'kevin', 'You should take SANS SEC542', '2009-03-01 22:31:13'),
		(12, 'asprox', 'Fear me, for I am asprox!', '2009-03-01 22:31:13')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = 'CREATE TABLE credit_cards( '.
			 'ccid INT NOT NULL AUTO_INCREMENT, '.
	         'ccnumber TEXT, '.
	         'ccv TEXT, '.
			 'expiration DATE, '.
			 'PRIMARY KEY(ccid))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString ="INSERT INTO `credit_cards` (`ccid`, `ccnumber`, `ccv`, `expiration`) VALUES
		(1, '4444111122223333', '745', '2012-03-01 10:01:12'),
		(2, '7746536337776330', '722', '2015-04-01 07:00:12'),
		(3, '8242325748474749', '461', '2016-03-01 11:55:12'),
		(4, '7725653200487633', '230', '2017-06-01 04:33:12'),
		(5, '1234567812345678', '627', '2018-11-01 13:31:13')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString = 
			'CREATE TABLE pen_test_tools('.
			'tool_id INT NOT NULL AUTO_INCREMENT, '.
	        'tool_name TEXT, '.
	        'phase_to_use TEXT, '.
			'tool_type TEXT, '.
			'comment TEXT, '.
			'PRIMARY KEY(tool_id))';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString ="INSERT INTO `pen_test_tools` (`tool_id`, `tool_name`, `phase_to_use`, `tool_type`, `comment`) VALUES
		(1, 'WebSecurify', 'Discovery', 'Scanner', 'Can capture screenshots automatically'),
		(2, 'Grendel-Scan', 'Discovery', 'Scanner', 'Has interactive-mode. Lots plug-ins. Includes Nikto. May not spider JS menus well.'),
		(3, 'Skipfish', 'Discovery', 'Scanner', 'Agressive. Fast. Uses wordlists to brute force directories.'),
		(4, 'w3af', 'Discovery', 'Scanner', 'GUI simple to use. Can call sqlmap. Allows scan packages to be saved in profiles. Provides evasion, discovery, brute force, vulneraility assessment (audit), exploitation, pattern matching (grep).'),
		(5, 'Burp-Suite', 'Discovery', 'Scanner', 'GUI simple to use. Provides highly configurable manual scan assistence with productivity enhancements.'),
		(6, 'Netsparker Community Edition', 'Discovery', 'Scanner', 'Excellent spider abilities and reporting. GUI driven. Runs on Windows. Good at SQLi and XSS detection. From Mavituna Security. Professional version available for purchase.'),
		(7, 'NeXpose', 'Discovery', 'Scanner', 'GUI driven. Runs on Windows. From Rapid7. Professional version available for purchase. Updates automatically. Requires large amounts of memory.'),
		(8, 'Hailstorm', 'Discovery', 'Scanner', 'From Cenzic. Professional version requires dedicated staff, multiple dediciated servers, professional pen-tester to analyze results, and very large license fee. Extensive scanning ability. Very large vulnerability database. Highly configurable. Excellent reporting. Can scan entire networks of web applications. Extremely expensive. Requires large amounts of memory.'),
		(9, 'Tamper Data', 'Discovery', 'Interception Proxy', 'Firefox add-on. Easy to use. Tampers with POST parameters and HTTP Headers. Does not tamper with URL query parameters. Requires manual browsing.'),		
		(10, 'DirBuster', 'Discovery', 'Fuzzer', 'OWASP tool. Fuzzes directory names to brute force directories.'),
		(11, 'SQL Inject Me', 'Discovery', 'Fuzzer', 'Firefox add-on. Attempts common strings which elicit XSS responses. Not compatible with Firefox 8.0.'),
		(12, 'XSS Me', 'Discovery', 'Fuzzer', 'Firefox add-on. Attempts common strings which elicit responses from databases when SQL injection is present. Not compatible with Firefox 8.0.'),
		(13, 'GreaseMonkey', 'Discovery', 'Browser Manipulation Tool', 'Firefox add-on. Allows the user to inject JavaScripts and change page.'),
		(14, 'NSLookup', 'Reconnaissance', 'DNS Server Query Tool', 'DNS query tool can query DNS name or reverse lookup on IP. Set debug for better output. Premiere tool on Windows but Linux perfers Dig. DNS traffic generally over UDP 53 unless response long then over TCP 53. Online version combined with anonymous proxy or TOR network may be prefered for stealth.'),
		(15, 'Whois', 'Reconnaissance', 'Domain name lookup service', 'Whois is available in Linux naitvely and Windows as a Sysinternals download plus online. Whois can lookup the registrar of a domain and the IP block associated. An online version is http://network-tools.com/'),
		(16, 'Dig', 'Reconnaissance', 'DNS Server Query Tool', 'The Domain Information Groper is prefered on Linux over NSLookup and provides more information natively. NSLookup must be in debug mode to give similar output. DIG can perform zone transfers if the DNS server allows transfers.'),
		(17, 'Fierce Domain Scanner', 'Reconnaissance', 'DNS Server Query Tool', 'Powerful DNS scan tool. FDS is a Perl program which scans and reverse scans a domain plus scans IPs within the same block to look for neighoring machines. Available in the Samurai and Backtrack distributions plus http://ha.ckers.org/fierce/'),
		(18, 'host', 'Reconnaissance', 'DNS Server Query Tool', 'A simple DNS lookup tool included with BIND. The tool is a friendly and capible command line tool with excellent documentation. Does not posess the automation of FDS.'),
		(19, 'zaproxy', 'Reconnaissance', 'Interception Proxy', 'OWASP Zed Attack Proxy. An interception proxy that can also passively or actively scan applications as well as perform brute-forcing. Similar to Burp-Suite without the disadvantage of requiring a costly license.'),
		(20, 'Google intitle', 'Discovery', 'Search Engine','intitle and site directives allow directory discovery. GHDB available to provide hints. See Hackers for Charity site.')";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString = 
			'CREATE TABLE captured_data('.
				'data_id INT NOT NULL AUTO_INCREMENT, '.
				'ip_address TEXT, '.
				'hostname TEXT, '.
				'port TEXT, '.
				'user_agent_string TEXT, '.
				'referrer TEXT, '.
				'data TEXT, '.
			 	'capture_date DATETIME, '.
				'PRIMARY KEY(data_id)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString = 
			'CREATE TABLE balloon_tips('.
				'tip_key VARCHAR(64) NOT NULL, '.
				'hint_level INT, '.
				'tip TEXT, '.
				'PRIMARY KEY(tip_key, hint_level)'.
			')';
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lQueryResult."</div>";
	}// end if

	$lQueryString ="INSERT INTO `balloon_tips` (`tip_key`, `hint_level`, `tip`) VALUES
			('ParameterPollutionInjectionPoint', 0, 'User input is not evaluated for duplicate parameters'),
			('ParameterPollutionInjectionPoint', 1, 'If user input contains the same variable more than once, the system will only accept one of the values. This can be used to trick the system into accepting a correct value and a mallicious value but only counting the mallicious value.'),
			('ParameterPollutionInjectionPoint', 2, 'Send two copies of the same parameter. Note carefully if the system uses the first, second, or both values. Some systems will concatenate the values together. If the system uses the first value, inject the value you want the system to count first.'),
			('CSSInjectionPoint', 0, 'User input is incorporated into the style sheet returned from the server'),
			('CSSInjectionPoint', 1, 'User input is incorporated into the style sheet returned from the server without being properly encoded. This allows an attacker to inject cross-site scripts or HTML into the input and break out of the style-sheet context. Arbitrary JavaScript and HTML can be injected.'),
			('CSSInjectionPoint', 2, 'Locate the input parameter that is incorporated into the style sheet. Determine what chracters are needed to properly complete the style so it is sytactically correct. Inject this closing statement along with a JavaScript or HTML to be executed.'),
			('JSONInjectionPoint', 0, 'User input is incorporated into the JSON returned from the server'),
			('JSONInjectionPoint', 1, 'User input is incorporated into the JSON returned from the server without being properly encoded. This allows an attacker to inject JSON into the input and break out of the JSON context. Arbitrary JavaScript can be injected.'),
			('JSONInjectionPoint', 2, 'Locate the input parameter that is incorporated into the JSON. Determine what chracters are needed to properly complete the JSON so it is sytactically correct. Inject this closing statement along with a JavaScript to be executed.'),
			('DOMXSSExecutionPoint', 0, 'This location contains dynamic output modified by the DOM'),
			('DOMXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting when user input is incorporated into the DOM'),
			('DOMXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting because user-input is incorporated into the DOM without properly encoding the user input first. Determine which input field contributes output here and inject HTML or scripts'),
			('ArbitraryRedirectionPoint', 0, 'Arbitrary redirection is a type of insecure direct object reference'),
			('ArbitraryRedirectionPoint', 1, 'See if a URL can be injected in place of the intended URL'),
			('ArbitraryRedirectionPoint', 2, 'Try injecting a URL into the parameter which contains the page to which the site thinks the user should be redirected to. It may be neccesary to use a complete URL including the protocol.'),
			('SQLInjectionPoint', 0, 'SQL Injection may occur on any page interacting with a database'),
			('SQLInjectionPoint', 1, 'Try injecting single-quotes and other special control characters'),
			('SQLInjectionPoint', 2, 'Try injecting single-quotes and other special control characters to produce an error if possible. Note any queries in the error to assist in injecting a complete query. Try using SQLMAP to inject queries.'),
			('CookieTamperingAffectedArea', 0, 'Cookies may store system state information'),
			('CookieTamperingAffectedArea', 1, 'Inspect the value of the cookies with a Firefox add-on like Cookie-Manager or a non-transparent proxy like Burp or Zap'),
			('CookieTamperingAffectedArea', 2, 'Change the value of the cookies to see what affect is produced on the site. Also watch how the values of the cookies change after using different site features.'),
			('JavaScriptInjectionPoint', 0, 'This location does not use JavaScript string encoding'),
			('JavaScriptInjectionPoint', 1, 'This location is vulnerable to JavaScript string injection. The first step is to determine which parameter is output here'),
			('JavaScriptInjectionPoint', 2, 'Locate the input parameter that is output to this location and inject raw JavaScript commands. Use the view-source to see if the syntax of the injection is correct'),
			('LocalFileInclusionVulnerability', 0, 'Perhaps a file other than the one intended could be included in this page'),
			('LocalFileInclusionVulnerability', 1, 'This page is vulnerable a local file inclusion vulnerability because it does not strongly validate that only explicitly named-pages are allowed.'),
			('LocalFileInclusionVulnerability', 2, 'Identify the input parameter that accepts the filename to be included then change that parameter to a system file such as /etc/passwd or C:\\boot.ini'),
			('HTMLandXSSandSQLInjectionPoint', 0, 'Inputs are usually a good place to start testing for cross-site scripting, HTML injection and SQL injection'),
			('HTMLandXSSandSQLInjectionPoint', 1, 'This input is vulnerable to multiple types of injection including cross-site scripting, HTML injection and SQL injection'),
			('HTMLandXSSandSQLInjectionPoint', 2, 'To get started with cross-site scripting and HTML injection, inject a JavaScript or HTML code then view-source on the resulting page to see if the script syntax is correct. For SQL injection, start by injecting a single-quote to produce an error.'),
			('OSCommandInjectionPoint', 0, 'Inputs are usually a good place to start testing for command injection'),
			('OSCommandInjectionPoint', 1, 'This input is vulnerable to multiple types of injection'),
			('OSCommandInjectionPoint', 2, 'This input is vulnerable to command injection plus may provide an injection point for reflected cross-site scripting. Try stating with \"127.0.0.1 && dir\".'),
			('XSRFVulnerabilityArea', 0, 'HTML forms are vulnerable to cross-site request forgery by default although sensitive forms may be protected'),
			('XSRFVulnerabilityArea', 1, 'This form is vulnerable to cross-site request forgery. Knowing the form action and inputs is the first step.'),
			('XSRFVulnerabilityArea', 2, 'Use this form to commit cross-site request forgery. Capture a legitimate request in Burp/Zap then create a cross-site script that sends the equivilent request when a user executes the cross-site script.'),
			('ReflectedXSSExecutionPoint', 0, 'This location contains dynamic output'),
			('ReflectedXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting'),
			('ReflectedXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting. Determine which input field contributes output here and inject scripts'),
			('HTMLEventReflectedXSSExecutionPoint', 0, 'This location contains dynamic output'),
			('HTMLEventReflectedXSSExecutionPoint', 1, 'Lack of output encoding controls often result in cross-site scripting; in this case via HTML Event injection.'),
			('HTMLEventReflectedXSSExecutionPoint', 2, 'This output is vulnerable to cross-site scripting because the input is not encoded prior to be used as a value in an HTML event. Determine which input field contributes output here and inject scripts.')
			;";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "
	CREATE PROCEDURE getBestCollegeBasketballTeam ()
	BEGIN
		SELECT 'Kentucky Wildcats';
	END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "
		CREATE PROCEDURE authenticateUserAndReturnProfile (p_username text, p_password text)
		BEGIN
			SELECT  accounts.cid, 
		          accounts.username, 
		          accounts.password, 
		          accounts.mysignature
		  FROM accounts
		    WHERE accounts.username = p_username
		      AND accounts.password = p_password;
		END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lQueryResult."</div>";
	}// end if
	
	$lQueryString = "
		CREATE PROCEDURE mutillidae.insertBlogEntry (
		  pBloggerName text,
		  pComment text
		)
		BEGIN
		
		  INSERT INTO blogs_table(
		    blogger_name, 
		    comment, 
		    date
		   )VALUES(
		    pBloggerName, 
		    pComment, 
		    now()
		  );
		
		END;
	";
	$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	if (!$lQueryResult) {
		$lErrorDetected = TRUE;
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lQueryResult."</div>";
	}// end if
	
	$MySQLHandler->closeDatabaseConnection();

} catch (Exception $e) {
	$lErrorDetected = TRUE;
	echo $CustomErrorHandler->FormatError($e, $lQueryString);
}// end try

// if no errors were detected, send the user back to the page that requested the database be reset.
//We use JS instead of HTTP Location header so that HTML5 clearing JS above will run
if(!$lErrorDetected){
	echo "<script>if(confirm(\"No PHP or MySQL errors were detected when resetting the database.\\n\\nClick OK to proceed or Cancel to stay on this page.\")){document.location=\"".$_SERVER["HTTP_REFERER"]."\"};</script>";
	//header("Location: ".$_SERVER["HTTP_REFERER"], true, 302);
}// end if

$CustomErrorHandler = null;
?>

	</body>
</html>