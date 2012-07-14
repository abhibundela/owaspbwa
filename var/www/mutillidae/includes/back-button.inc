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

<?php 
	try{
   		$lHTMLEventReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("HTMLEventReflectedXSSExecutionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try

?>

<script type="text/javascript">
	$(function() {
		$('[HTMLEventReflectedXSSExecutionPoint]').attr("title", "<?php echo $lHTMLEventReflectedXSSExecutionPointBallonTip; ?>");
		$('[HTMLEventReflectedXSSExecutionPoint]').balloon();
	});
</script>

<div style="margin: 5px;">
	<span style="font-weight: bold;" HTMLEventReflectedXSSExecutionPoint="1">
	<a 	onclick="document.location.href='<?php echo $lHTTPReferer; ?>';" 
		style="cursor:pointer;">
		<img	src="./images/back-button-128px-by-128px.png" 
				alt="Back" 
				width="64px" 
				height="64px" 
				align="middle" 
		/>
		&nbsp;
		Back
	</a>
	</span>
</div>