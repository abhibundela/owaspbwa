
<!-- Bubble hints code -->
<?php 
	try{
   		$lArbitraryRedirectionPointBallonTip = $BubbleHintHandler->getHint("ArbitraryRedirectionPoint");
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ArbitraryRedirectionPoint]').attr("title", "<?php echo $lArbitraryRedirectionPointBallonTip; ?>");
		$('[ArbitraryRedirectionPoint]').balloon();
	});
</script>

<div class="page-title">Credits</div>

<?php include_once './includes/back-button.inc';?>

<?php 
   	switch ($_SESSION["security-level"]){
   		case "0": // This code is insecure
   		case "1": // This code is insecure
   			/* This code is insecure. Direct object references in the form of the "forwardurl"
   			 parameter give the user complete control of the input. Contrary to popular belief, 
   			 input validation, blacklisting, etc is not the best defense. The best defenses are 
   			 probably secure 100% of the time. For direct object references, there are two defenses.
   			 Authorization via ACL or Entitlements is used when transaction requires authentication.
   			 This transaction (forwarding URL) does not require authentication so the other method is used;
   			 mapping. Mapping substitutes a harmless token for the direct object. The direct object in 
   			 this case is the page the user is being forwarded to. We will use mapping to secure this code.
   			
   			 Note: For static links, the best defense is to simply hardcode the links in an anchor tag.
   			 This exercise will use mapping to show how it works, but it should be recognized that 
   			 for giving the user links to click, hardcoding is the best defense.
   			*/ 
   			echo '
				<div class="label" style="text-align: center;">Created by Irongeek.com. Developed by Adrian &quot;<a href="http://www.irongeek.com">Irongeek</a>&quot; Crenshaw and <a href="mailto:mutillidae-development@gmail.com">Jeremy Druin</a></div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
   				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.irongeek.com/">Adrian Crenshaw</a> would like to thank 
				the following people for helping him with the Mutillidae project:</div>
				<div ArbitraryRedirectionPoint=\"1\">&nbsp;</div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.owasp.org">OWASP</a> for making the vulnerability&nbsp; 
				list I based this on.</div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.issa-kentuckiana.org/">ISSA Kentuckiana</a></div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.owasp.org/index.php/Louisville">OWASP Louisville </a></div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.pocodoy.com/blog/">Brian Blankenship</a> for his support 
				of the idea.</div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.room362.com/">Mubix</a> for confirming the name</div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://www.isd-podcast.com/">InfoSec Daily Podcast</a></div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=http://pauldotcom.com/">PaulDotCom Podcast</a></div>
				<div ArbitraryRedirectionPoint=\"1\">All sorts of folks at <a href="index.php?page=redirectandlog.php&forwardurl=http://www.php.net/">PHP.net </a>for code snippets: kaigillmann </div>
				<div ArbitraryRedirectionPoint=\"1\"><a href="index.php?page=redirectandlog.php&forwardurl=https://addons.mozilla.org/en-US/firefox/collections/jdruin/pr/">Professional Web Application Developer Quality Assurance Pack</a> by <a href="mailto:mutillidae-development@gmail.com">Jeremy Druin</a></div>
   			';
   		break;
    		
   		case "2":
   		case "3":
   		case "4":
   		case "5": // This code is fairly secure
   			echo '
				<div class="label" style="text-align: center;">Created by Irongeek.com. Developed by Adrian &quot;<a href="http://www.irongeek.com">Irongeek</a>&quot; Crenshaw and <a href="mailto:mutillidae-development@gmail.com">Jeremy Druin</a></div>
				<div>&nbsp;</div>
				<div>&nbsp;</div>
   				<div><a href="index.php?page=redirectandlog.php&forwardurl=1">Adrian Crenshaw</a> would like to thank 
				the following people for helping him with the Mutillidae project:</div>
				<div>&nbsp;</div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=2">OWASP</a> for making the vulnerability list I based this on.</div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=3">ISSA Kentuckiana</a></div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=4">OWASP Louisville </a></div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=5">Brian Blankenship</a> for his support 
				of the idea.</div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=6">Mubix</a> for confirming the name</div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=7">InfoSec Daily Podcast</a></div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=8">PaulDotCom Podcast</a></div>
				<div>All sorts of folks at <a href="index.php?page=redirectandlog.php&forwardurl=9">PHP.net </a>for code snippets: kaigillmann </div>
				<div><a href="index.php?page=redirectandlog.php&forwardurl=10">Professional Web Application Developer Quality Assurance Pack</a> by <a href="mailto:mutillidae-development@gmail.com">Jeremy Druin</a></div>
			';
  		break;
   	}// end switch
?>

<?php
	// Begin hints section
	if ($_SESSION["showhints"]) {
		echo '
		<br/>
		<table>
				<tr><td class="hint-header">Hints</td></tr>
				<tr>
					<td class="hint-body">
						<ul class="hints">
						  	<li><b>For Unvalidated Redirects and Forwards:</b> 
						  	Unvalidated redirects can make the job of Phishers easier 
						  	since the URL can be made to look like part of a trusted site. 
						  	Notice how this page used “redirectandlog.php?forwardurl=” 
						  	to send a user to another site, and log where it went. 
						  	A Phisher could use this forward mechanism to make a 
						  	Phishing URL look more legitimate.
							</li>
						</ul>
					</td>
				</tr>
			</table>'; 
	}//end if ($_SESSION["showhints"])
?>