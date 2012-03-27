<?php 
	try{	
		$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		if (!$conn) {
   		   	throw (new Exception("Error connecting to MySQL database. Connection error: ".$conn->connect_errorno." - ".$conn->connect_error." Error: ".$conn->errorno." - ".$conn->error, $conn->errorno));
	    }// end if
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to open MySQL connection.");
	}// end try		
?>