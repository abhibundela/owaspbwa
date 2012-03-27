<div class="page-title" style="padding:10px;width:75%;margin-left:auto;margin-right:auto;text-align:center;">Mutillidae: Deliberately Vulnerable PHP Scripts Of OWASP Top 10</div>

<div style="font-family: Arial;">

	<p class="label">Latest Version / Installation</p>
	<ul>
		<li><a class="label" style="text-decoration: none;" href="http://www.irongeek.com/i.php?page=security/mutillidae-deliberately-vulnerable-php-owasp-top-10" target="_blank">Latest Version</a></li>
		<li><a class="label" style="text-decoration: none;" href="./index.php?page=installation.php">Installation Instructions</a></li>
		<li><a class="label" style="text-decoration: none;" href="./index.php?page=usage-instructions.php">Usage Instructions</a></li>
		<li><a class="label" style="text-decoration: none;" href="./index.php?page=php-errors.php">Get rid of those pesky PHP errors</a></li>		
		<li><a class="label" style="text-decoration: none;" href="./index.php?page=change-log.htm">Change Log</a></li>
		<li><a class="label" style="text-decoration: none;" href="./index.php?page=notes.php">Notes</a></li>		
	</ul>
	
	<div style="text-align: center;margin-left: auto; margin-right: auto;">
		<div>&nbsp;</div>
		<table>
			<tr>
				<td colspan="2" style="text-align: center;" class="report-header">
					Samurai WTF and Backtrack contains all the tools needed or you may build your own collection	
				</td>
			</tr>
			<tr>
				<td>
					<a href="http://www.backtrack-linux.org/" target="_blank">
						<img alt="Backtrack" style="border-width:0px;" width="90px" height="69px" src="./images/backtrack-4-r2-logo-90-69.png" />
					</a>
				</td>
				<td>
					<a href="http://samurai.inguardians.com/" target="_blank">
						<img alt="Samurai Web Testing Framework" style="border-width:0px;" width="160px" height="107px" src="./images/samurai-wtf-logo-320-214.jpeg" />
					</a>
					<div class="label">Samurai Web Testing Framework</div>
				</td>
			</tr>
		</table>
		<div>
			<a href="http://www.eclipse.org/pdt/" target="_blank" style="margin-right:10px;">
				<img alt="Eclipse PDT" style="border-width: 0px;" height="100px" width="100px" src="./images/bui_eclipse_pos_logo_fc_med.jpg" />
			</a>
			<a href="http://www.php.net/" target="_blank" style="margin-left:30px;">
				<img alt="PHP-MySQL" style="border-width: 0px;" height="88px" width="100px" src="./images/php-mysql-logo-176-200.jpeg" />
			</a>
			<a href="http://www.quest.com/toad-for-mysql/" target="_blank" style="margin-left:30px;">
				<img alt="PHP-MySQL" style="border-width: 0px;" height="80px" width="77px" src="./images/toad-for-mysql-77-80.jpg" />
			</a>			
			<a href="http://www.hackersforcharity.org/" target="_blank" style="margin-left:30px;">
				<img alt="Hackers for Charity" style="border-width:0px;" width="256px" height="100px" src="./images/IhackBanner2x_final_print.jpg" />
			</a>
		</div>
	</div>
</div>

<?php
	if ($_SESSION["showhints"]){
		echo '
			<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>Security Misconfiguration and Error Handling</b>
						  	This is not directly a vulnerability with the web app, but with 
						  	how it is installed or how the web server is configured. 
						  	Things to check for would be items like:
							</li>
							<li>1. Is the webserver software (Apache, IIS, etc) up to date?
							</li>
							<li>2. How about the libraries your application uses? Are they up to date? Problems could exist because of code you never wrote, but were included as a library.
							</li>
							<li>3. Is you web app running on a box with unneeded services? The web app may be fine, but some other vulnerable service could let someone in.
							</li>
							<li>4. Make sure you are not using default passwords.
							</li>
							<li>5. How does your server handle errors? Sometimes too much information is given back to the attacker via error message and banners. No reason to help out your attackers. Mutillidae has his issue in spades, just type a single quote into some of the forms to see what I mean.
							</li>
							<li>6. Some functions are rather dangerous. If the configuration was hardened many of the problems under "Malicious File Execution" would be harder to exploit since an attacker could not directly tell PHP to grab a file from an offsite URL.
							</li>
							<li>
							Also, depending on your application software stack, there could be a sorts of recommended ways to harden configuration. In the case of PHP, Madirish has a guide that may help: <a href="http://www.madirish.net/?article=229">http://www.madirish.net/?article=229</a>
							</li>
				  		</ul>
					</td>
				</tr>
			</table>'; 
	}// end if
// End hints section
?>
