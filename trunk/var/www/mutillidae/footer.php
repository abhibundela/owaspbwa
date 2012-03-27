			<!-- End Content -->
		</blockquote>
			</td>
		</tr>
	</table>

<?php 
		   	switch ($_SESSION["security-level"]){
		   		case "0": // This code is insecure
		   		case "1": // This code is insecure
		   			// DO NOTHING
		   			$lUserAgentString = $_SERVER['HTTP_USER_AGENT'];
					$lPHPVersion = "PHP Version: " . phpversion();
		   		break;
			    
		   		case "2":
		   		case "3":
		   		case "4":
		   		case "5": // This code is fairly secure
		  			/* 
		  			 * All information coming to the server in HTTP requests is under the 
		  			 * complete control of the user. This includes any information normally
		  			 * being thought of as "sent by the browser". HTTP requests are simply
		  			 * streams of strings formatting according to HTTP specifications. Anyone
		  			 * can format a string properly. A browser is not required. Try reading the RFC
		  			 * for HTTP to see how to construct the strings. Check out user-agent switchers
		  			 * like the add-on for Firefox to change only the user-agent string without
		  			 * having to create the entire header. Try Tamper Data to get control of all
		  			 * the HTTP headers in the request. Try netcat to create your own HTTP header 
		  			 * from scratch. When you get really comfortable. Try sending HTTP requests 
		  			 * via Telnet.
		  			 * 
		  			 * This code is secure because we escape all output according to context. This
		  			 * information is being output to HTML so we HTML encode the information. If we
		  			 * were outputing into JavaScript we would not use HTML encoding. We would use
		  			 * JavaScript string encoding. There are 5 contexts to be particuarly careful about.
		  			 * HTML, HTML attributes, JavaScript, CSS, and URL query parameters.
		  			 */
		   			$lUserAgentString = $Encoder->encodeForHTML($_SERVER['HTTP_USER_AGENT']);
					$lPHPVersion = "PHP Version: Not Available (Secure mode doesn't blab the server version)";
		   		break;
		   	}// end switch
	echo '<div class="footer">Browser: '.$lUserAgentString.'</div>';
	echo '<div class="footer">'.$lPHPVersion.'</div>';
?>

	<div class="footer">
		The newest version of 
		<a href="http://www.irongeek.com/i.php?page=security/mutillidae-deliberately-vulnerable-php-owasp-top-10" target="_blank">
			Mutillidae
		</a> 
		can downloaded from <a href="http://irongeek.com" target="_blank">Irongeek's Site</a>
	</div>
</body>
</html>