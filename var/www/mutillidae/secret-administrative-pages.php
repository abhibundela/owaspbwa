<div class="page-title">Login</div>

<?php include_once './includes/back-button.inc';?>

<table style="margin-left:auto; margin-right:auto; width: 600px;">
	<tr>
		<td class="form-header">"Secret" administrative or configuration pages</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td>Showing server configurations on pages allowed through the firewall is a bad idea. "Hiding" pages by not linking to them so you believe you are the only one who knows the URL doesnt work. There are tools to brute force the URL, shoulder surfing, log history, browser history, router-firewall-proxy history, scanners, guessing and other methods can get these URLs. or admin functions, create a second site inside the firewall to segregate these pages from the Internet facing site.</td>
	</tr>
	<tr><td></td></tr>
	<tr>
		<td style="text-align:center;">
			I wonder what clever name the server admin would give to a PHP page that shows server configuration information? Hint: What is the function in PHP that dumps server configuration information into a nice table? Enable hints if you need more help.
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
			<table style="margin-left:auto; margin-right:auto; width: 600px;">
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li>
						  		The phpinfo function dumps PHP server configuration information to a nice table.
							</li>
						  	<li>PHP pages typically end in the PHP extension.</li>
							<li>There are bots on the Internet trolling sites looking for a special page "phpinfo.php"</li>
							<li>Try brute forcing the page names in the page parameter with Burp-Intruder in sniper mode</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
	// End hints section
?>