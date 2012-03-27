<?php 
	try{
		$lResult = $conn->close();
		if (!$lResult) {
		   	throw (new Exception("Error executing query. Connection error: ".$conn->connect_errorno." - ".$conn->connect_error." Error: ".$conn->errorno." - ".$conn->error, $conn->errorno));
		}// end if
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to close MySQL connection.");
	}// end try		
?>