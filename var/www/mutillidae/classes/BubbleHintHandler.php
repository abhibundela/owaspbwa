<?php
class BubbleHintHandler {
	protected $encodeOutput = FALSE;
	protected $stopSQLInjection = FALSE;
	protected $mSecurityLevel = 0;
	protected $mHintLevel = 0;
	protected $mHintsEnabled = TRUE; // Decides if system wants to see hints
	protected $mDisplayHints = TRUE; // Decides if user wants to see hints

	// private objects
	protected $mMySQLHandler = null;
	protected $mESAPI = null;
	protected $mEncoder = null;
	
	private function doSetSecurityLevel($pSecurityLevel){
		$this->mSecurityLevel = $pSecurityLevel;
		
		switch ($this->mSecurityLevel){
	   		case "0": // This code is insecure, we are not encoding output
			case "1": // This code is insecure, we are not encoding output
				$this->encodeOutput = FALSE;
				$this->stopSQLInjection = FALSE;
				$this->mHintsEnabled = TRUE;
	   		break;
		    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	  			// If we are secure, then we encode all output.
	   			$this->encodeOutput = TRUE;
	   			$this->stopSQLInjection = TRUE;
	   			$this->mHintsEnabled = FALSE;
	   		break;
	   	}// end switch		
	}// end function
				
	public function __construct($pPathToESAPI, $pSecurityLevel){
		
		$this->doSetSecurityLevel($pSecurityLevel);
		
		//initialize OWASP ESAPI for PHP
		require_once $pPathToESAPI . 'ESAPI.php';
		$this->ESAPI = new ESAPI($pPathToESAPI . 'ESAPI.xml');
		$this->Encoder = $this->ESAPI->getEncoder();

		/* Initialize MySQL Connection handler */
		require_once 'MySQLHandler.php';
		$this->mMySQLHandler = new MySQLHandler($pPathToESAPI, $pSecurityLevel);
		
	}// end function
	
	/* PHP cant remeber any information in class after request is over, hence the 
	disgusting hack of using the session to remember settings. Its total hack but
	PHP is limited.
	*/
	public function showHints(){
		$this->mDisplayHints = $_SESSION[BubbleHintHandler][mDisplayHints] = TRUE;
	}// end function

	public function hideHints(){
		$this->mDisplayHints = $_SESSION[BubbleHintHandler][mDisplayHints] = FALSE;
	}// end function
	
	public function hintsAreDispayed(){
		//return $this->mDisplayHints;
		return $_SESSION[BubbleHintHandler][mDisplayHints];
	}// end function
	
	public function setSecurityLevel($pSecurityLevel){
		$this->doSetSecurityLevel($pSecurityLevel);
		$this->mMySQLHandler->setSecurityLevel($pSecurityLevel);
	}// end function

	public function getSecurityLevel($pSecurityLevel){
		return $this->mSecurityLevel;
	}// end function
	
	public function setHintLevel($pHintLevel){
		$this->mHintLevel = $pHintLevel;
	}// end function

	public function getHintLevel($pHintLevel){
		return $this->mHintLevel;
	}// end function
	
	public function getHint($pTipKey){
		
		//if system has disabled hints. return innocuous message.
		if (!$this->mHintsEnabled){
			return "Not allowed to give hints at this security level.";
		}// end if
		
		// if user doesnt want to see hints, return nothing
		if (!$this->mDisplayHints){
			return "";
		}// end if

		$lQuery  = "SELECT tip FROM balloon_tips WHERE tip_key ='" . $pTipKey . "' AND hint_level = " . $this->mHintLevel . ";";
		
		try{
    		$lResult = $this->mMySQLHandler->executeQuery($lQuery);
    		$lDataRow = $lResult->fetch_object();
			return $lDataRow->tip;
		} catch (Exception $e) {
			throw(new Exception("Error attempting to read from get hint table: ".$e->getMessage(), $e->getCode(), $e));
		}// end try		
	}// end method

}// end class