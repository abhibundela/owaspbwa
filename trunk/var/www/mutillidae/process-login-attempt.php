<?php
    try {
	   	switch ($_SESSION["security-level"]){
	   		case "0": // This code is insecure
	   		case "1": // This code is insecure
				/*
				 * Grab username and password from parameters. 
				 * Notice in insecure mode, we take parameters from "REQUEST" which
				 * could be GET OR POST. This is not correct. The page
				 * intends to receive parameters from POST and should
				 * restrict parameters to POST only.
				 */ 
				$username = $_REQUEST["username"];
				$password = $_REQUEST["password"];
	   			
	   			$lQueryString = 	"SELECT * FROM accounts WHERE username='". 
			   				$username.
			   				"' AND password='". 
			   				$password.
			   				"'";		
	   			$lProtectCookies = FALSE;
	   		break;
		    		
			case "2":
			case "3":
			case "4":
	   		case "5": // This code is fairly secure
	   			/* Restrict paramters to POST */
				$username = $_POST["username"];
				$password = $_POST["password"];
	   			
	   			/* 
	  			 * Note: While escaping works ok in some case, it is not the best defense.
	  			 * Using stored procedures is a much stronger defense.
	  			 */
	   			$lQueryString  = 	"SELECT * FROM accounts WHERE username='". 
				   			$MySQLHandler->escapeDangerousCharacters($username) .
				   			"' AND password='". 
				   			$MySQLHandler->escapeDangerousCharacters($password). 
				   			"'";
	   			$lProtectCookies = TRUE;
	   		break;
	   	}// end switch

		$LogHandler->writeToLog("Attempt to log in by user: " . $username);
		
		$lQueryResult = $MySQLHandler->executeQuery($lQueryString);
	    if ($lQueryResult->num_rows > 0) {
		    $row = $lQueryResult->fetch_object();
			$failedloginflag=0;
			$_SESSION['loggedin'] = 'True';
			$_SESSION['uid'] = $row->cid;
			$_SESSION['logged_in_user'] = $row->username;
			$_SESSION['logged_in_usersignature'] = $row->mysignature;
			$_SESSION['is_admin'] = $row->is_admin;

			/*
			/* Set client-side auth token. if we are in insecure mode, we will
			 * pay attention to client-side authorization tokens. If we are secure,
			 * we dont use client-side authortization tokens and we ignore any
			 * attempts to use them.
			 * 
			 * If in secure mode, we want the cookie to be protected
			 * with HTTPOnly flag. There is some irony here. In secure code,
			 * we are to ignore authorization cookies, so we are protecting
			 * a cookie we know we are going to ignore. But the point is to
			 * provide an example to developers of proper coding techniques.
			 * 
			 * Note: Ideally this cookie must be protected with SSL also but
			 * again this is just a demo. Once your in SSL mode, maintain SSL
			 * and escalate any requests for HTTP to HTTPS.
			 */
			if ($lProtectCookies){
				$lUsernameCookie = $Encoder->encodeForURL($row->username);
				setcookie("username", $lUsernameCookie, 0, "", "", FALSE, TRUE);
				setcookie("uid", $row->cid, 0, "", "", FALSE, TRUE);
			}else {
				//setrawcookie() allows for response splitting
				$lUsernameCookie = $row->username;
				setrawcookie("username", $lUsernameCookie);
				setrawcookie("uid", $row->cid);
			}// end if
			
			$LogHandler->writeToLog("Logged in user: " . $row->username . " (" . $row->cid . ")");
			
			header('Location: index.php', true, 302);
		} else {
			$LogHandler->writeToLog("Failed login for user: " . $row->username);
			$failedloginflag=1;
    	}// end if ($lQueryResult->num_rows > 0)
	    
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
	}// end try
?>