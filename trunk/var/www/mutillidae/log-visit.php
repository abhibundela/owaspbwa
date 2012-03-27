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
	try {	    	
		switch ($_SESSION["security-level"]){
	   		case "0": // this code is insecure
	   		case "1": // this code is insecure 
				$lBeSmart = FALSE;
	   		break;//case "0"
	    		
	   		case "2":
	   		case "3":
	   		case "4":	
	   		case "5": // This code is fairly secure
				$lBeSmart = TRUE;
	   		break;//case "5"
	   	}// end switch ($_SESSION["security-level"])

		// wont exist on first visit
		if (isset($_SERVER['HTTP_REFERER'])){
			$lHTTP_REFERER = $_SERVER['HTTP_REFERER'];
		}else{
			$lHTTP_REFERER = "";
		}// end if
		
		if ($lBeSmart) {
			$query = "INSERT INTO hitlog(hostname, ip, browser, referer, date) VALUES ('".
				gethostbyaddr($_SERVER['REMOTE_ADDR']) . "', '".
				$_SERVER['REMOTE_ADDR'] . "', '".
				$conn->real_escape_string($_SERVER['HTTP_USER_AGENT']) . "', '".
				$conn->real_escape_string($lHTTP_REFERER) . "', ".
				" now() )";
		}else{
			$query = "INSERT INTO hitlog(hostname, ip, browser, referer, date) VALUES ('".
				gethostbyaddr($_SERVER['REMOTE_ADDR']) . "', '".
				$_SERVER['REMOTE_ADDR'] . "', '".
				$_SERVER['HTTP_USER_AGENT'] . "', '".
				$lHTTP_REFERER . "', ".
				" now() )";
		}// end if $lBeSmart
	
		$result = $conn->query($query);
		if (!$result) {
	    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
	    }// end if
		
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try		
?>