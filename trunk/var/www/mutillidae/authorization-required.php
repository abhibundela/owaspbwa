<?php 
	try {
		$LogHandler->writeToLog("User attempted to access forbidden page.");	
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $query);
	}// end try		
?>

<div class="page-title">Authorization Required</div>

<?php include_once './includes/back-button.inc';?>

<table style="margin-left:auto; margin-right:auto;">
	<tr>
		<td class="error-message">
			Authorization Error: 403 - Page Requires Higher Privileges Than Current User Possesses
		</td>
	</tr>
</table>