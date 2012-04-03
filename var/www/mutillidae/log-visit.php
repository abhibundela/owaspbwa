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
		$LogHandler->writeToLog($conn, "User visited");	
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try
			
?>