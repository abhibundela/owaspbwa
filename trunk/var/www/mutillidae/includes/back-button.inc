<?php

	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   			// DO NOTHING: This is insecure		
			$lEncodeOutput = FALSE;
		break;
	    		
   		case "5": // This code is fairly secure
  			/* 
  			 * NOTE: Input validation is excellent but not enough. The output must be
  			 * encoded per context. For example, if output is placed in HTML,
  			 * then HTML encode it. Blacklisting is a losing proposition. You 
  			 * cannot blacklist everything. The business requirements will usually
  			 * require allowing dangerous charaters.Output encoding is the answer. 
  			 * Validate what you can, encode it all.
  			 */
			$lEncodeOutput = TRUE;
   		break;
   	}// end switch		

   	if (!$lEncodeOutput) {
   		$lHTTPReferer = $_SERVER['HTTP_REFERER'];
   	} else {
   		$lHTTPReferer = $Encoder->encodeForJavaScript($_SERVER['HTTP_REFERER']);
   	}// end if
?>

<div style="margin: 5px;">
	<a 	onclick="document.location.href='<?php echo $lHTTPReferer; ?>';" 
		style="cursor:pointer;">
		<img	src="./images/back-button-128px-by-128px.png" 
				alt="Back" 
				width="64px" 
				height="64px" 
				align="middle" 
		/>
		&nbsp;
		<span style="font-weight: bold;">Back</span>
	</a>
</div>
