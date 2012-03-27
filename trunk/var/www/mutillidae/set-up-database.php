<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" href="./styles/global-styles.css" />
	</head>
	<body>
		<div>&nbsp;</div>
		<div class="page-title">Setting up the database...</div><br /><br />

<?php

//initialize custom error handler
require_once 'classes/CustomErrorHandler.php';
if (!isset($CustomErrorHandler)){
	$CustomErrorHandler = 
	new CustomErrorHandler("owasp-esapi-php/src/", 0);
}// end if	

include 'config.inc';

try{

	try{	
		$lMySQLiConnection = new mysqli($dbhost, $dbuser, $dbpass);
		if (mysqli_connect_errno()) {
   		   	throw (new Exception("Error connecting to MySQL database. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
		}else{
			echo "<div class=\"database-success-message\">Connected to MySQL database</div>";	
	    }// end if
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to open MySQL connection.");
	}// end try	
		
	try{
		$lQuery = "DROP DATABASE IF EXISTS mutillidae";
		$lResult = $lMySQLiConnection->query($lQuery);
		if (!$lResult) {
		   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
		}else{
			echo "<div class=\"database-success-message\">Executed query 'DROP DATABASE IF EXISTS' with result ".$lResult."</div>";
		}// end if
	}catch(Exception $e){
		//DO NOTHING. THIS IS HERE DUE TO A MYSQL BUG THAT HAS NOT BEEN PATCHED YET.
	}//end try
	
	$lQuery = "CREATE DATABASE mutillidae";
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE DATABASE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = "USE mutillidae";
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'USE DATABASE' with result ".$lResult."</div>";
	}// end if
		
	//include 'closedb.inc';
	//echo "<div class=\"database-success-message\">Executed query 'CLOSE DATABASE' with result ".$lResult."</div>";
	
	//include 'opendb.inc';
	
	$lQuery = 'CREATE TABLE blogs_table( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'blogger_name TEXT, '.
	         'comment TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';	
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = 'CREATE TABLE accounts( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'username TEXT, '.
	         'password TEXT, '.
			 'mysignature TEXT, '.
			 'is_admin VARCHAR(5),'.
			 'PRIMARY KEY(cid))';
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = 'CREATE TABLE hitlog( '.
			 'cid INT NOT NULL AUTO_INCREMENT, '.
	         'hostname TEXT, '.
	         'ip TEXT, '.
			 'browser TEXT, '.
			 'referer TEXT, '.
			 'date DATETIME, '.
			 'PRIMARY KEY(cid))';		 
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = "INSERT INTO accounts (username, password, mysignature, is_admin) VALUES
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery ="INSERT INTO `blogs_table` (`cid`, `blogger_name`, `comment`, `date`) VALUES
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = 'CREATE TABLE credit_cards( '.
			 'ccid INT NOT NULL AUTO_INCREMENT, '.
	         'ccnumber TEXT, '.
	         'ccv TEXT, '.
			 'expiration DATE, '.
			 'PRIMARY KEY(ccid))';
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if

	$lQuery ="INSERT INTO `credit_cards` (`ccid`, `ccnumber`, `ccv`, `expiration`) VALUES
		(1, '4444111122223333', '745', '2012-03-01 10:01:12'),
		(2, '7746536337776330', '722', '2015-04-01 07:00:12'),
		(3, '8242325748474749', '461', '2016-03-01 11:55:12'),
		(4, '7725653200487633', '230', '2017-06-01 04:33:12'),
		(5, '1234567812345678', '627', '2018-11-01 13:31:13')";
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lResult."</div>";
	}// end if

	$lQuery = 
			'CREATE TABLE pen_test_tools('.
			'tool_id INT NOT NULL AUTO_INCREMENT, '.
	        'tool_name TEXT, '.
	        'phase_to_use TEXT, '.
			'tool_type TEXT, '.
			'comment TEXT, '.
			'PRIMARY KEY(tool_id))';
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if

	$lQuery ="INSERT INTO `pen_test_tools` (`tool_id`, `tool_name`, `phase_to_use`, `tool_type`, `comment`) VALUES
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'INSERT INTO TABLE' with result ".$lResult."</div>";
	}// end if

	$lQuery = 
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE TABLE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = "
	CREATE PROCEDURE getBestCollegeBasketballTeam ()
	BEGIN
		SELECT 'Kentucky Wildcats';
	END;
	";
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = "
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lResult."</div>";
	}// end if
	
	$lQuery = "
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
	$lResult = $lMySQLiConnection->query($lQuery);
	if (!$lResult) {
	   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
	}else{
		echo "<div class=\"database-success-message\">Executed query 'CREATE PROCEDURE' with result ".$lResult."</div>";
	}// end if
	
	try{
		$lResult = $lMySQLiConnection->close();
		if (!$lResult) {
		   	throw (new Exception("Error executing query. Connection error: ".$lMySQLiConnection->connect_errorno." - ".$lMySQLiConnection->connect_error." Error: ".$lMySQLiConnection->errorno." - ".$lMySQLiConnection->error, $lMySQLiConnection->errorno));
		}else{
			echo "<div class=\"database-success-message\">Executed query 'CLOSE DATABASE' with result ".$lResult."</div>";
		}// end if
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to close MySQL connection.");
	}// end try			
	
} catch (Exception $e) {
	echo $CustomErrorHandler->FormatError($e, $lQuery);
}// end try

$CustomErrorHandler = null;
?>

		<span style="text-align: center;">
			<div>&nbsp;</div>
			<div class="label">If you see no errors above, it should be done.</div>
			<div>&nbsp;</div>
			<div class="label"><a href="index.php">Continue back to the frontpage.</a></div>
		</span>
	</body>
</html>
