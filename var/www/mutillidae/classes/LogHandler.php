<?php
class LogHandler {
	//default insecure: no output encoding.
	protected $encodeOutput = FALSE;
	protected $stopSQLInjection = FALSE;
	protected $mSecurityLevel = 0;
	protected $ESAPI = null;
	protected $Encoder = null;

	private function doSetSecurityLevel($pSecurityLevel){
		$this->mSecurityLevel = $pSecurityLevel;
		
		switch ($this->mSecurityLevel){
	   		case "0": // This code is insecure, we are not encoding output
			case "1": // This code is insecure, we are not encoding output
				$this->encodeOutput = FALSE;
				$this->stopSQLInjection = FALSE;
	   		break;
		    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	  			// If we are secure, then we encode all output.
	   			$this->encodeOutput = TRUE;
	   			$this->stopSQLInjection = TRUE;
	   		break;
	   	}// end switch		
	}// end function
		
	public function __construct($pPathToESAPI, $pSecurityLevel){
		
		$this->doSetSecurityLevel($pSecurityLevel);
		
		//initialize OWASP ESAPI for PHP
		require_once $pPathToESAPI . 'ESAPI.php';
		$this->ESAPI = new ESAPI($pPathToESAPI . 'ESAPI.xml');
		$this->Encoder = $this->ESAPI->getEncoder();
	}// end function
	   	
	public function setSecurityLevel($pSecurityLevel){
		$this->doSetSecurityLevel($pSecurityLevel);
	}// end function
	
	public function writeToLog($MySQLConnection, $TransactionDescription){

		if (!$this->encodeOutput){
			$lUserAgent = $_SERVER['HTTP_USER_AGENT'];
		}else{
			/* Cross site scripting defense */
   			// encode the entire message following OWASP standards
   			// this is HTML encoding because we are outputting data into HTML
			$lUserAgent = $this->Encoder->encodeForHTML($_SERVER['HTTP_USER_AGENT']);
		}// end if
		
		/*Here we are protecting against SQL injection and other types of 
		 * database injection.	   				 *
		 *  
		 * Note: This is fairly secure, but $MySQLConnection->real_escape_string is not the best
		 * defense. A parameterized stored procedure would be better.
		 */
		if (!$this->stopSQLInjection) {
			/* gethostbyaddr() causing a lot of issues because there is not
			 * a way to set timeout settings. It is being removed for now.
			 */
			//$lClientName = gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$lClientName = $_SERVER['REMOTE_ADDR'];
			$lClientIP = $_SERVER['REMOTE_ADDR'];
			//$lUserAgent = $lUserAgent;
			//$TransactionDescription = $TransactionDescription;
		}else{
			//$lClientName = $MySQLConnection->real_escape_string(gethostbyaddr($_SERVER['REMOTE_ADDR']));
			$lClientName = $MySQLConnection->real_escape_string($_SERVER['REMOTE_ADDR']);
			$lClientIP = $MySQLConnection->real_escape_string($_SERVER['REMOTE_ADDR']);
			$lUserAgent = $MySQLConnection->real_escape_string($lUserAgent);
			$TransactionDescription = $MySQLConnection->real_escape_string($TransactionDescription);
		}// end if
		
		$query = "INSERT INTO hitlog(hostname, ip, browser, referer, date) VALUES ('".
			$lClientName . "', '".
			$lClientIP . "', '".
			$lUserAgent . "', '".
			$TransactionDescription . "', ".
			" now() )";
			
		$result = $MySQLConnection->query($query);
		if ($MySQLConnection->errorno > 0) {
	    	throw (new Exception('Error: '.$MySQLConnection->error, $MySQLConnection->errorno));
	    }// end if $MySQLConnection->errorno>0
	}// end method
	
}// end class