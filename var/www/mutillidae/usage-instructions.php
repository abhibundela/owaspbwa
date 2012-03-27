<div class="page-title">Usage Instructions</div>

<?php include_once './includes/back-button.inc';?>

<span>
	Mutillidae implements the <a href="http://www.owasp.org/index.php/OWASP_Top_Ten_Project" target="_blank">OWASP Top 10</a> in PHP.
	<br/><br/>
	Go to the <a href="http://www.owasp.org/index.php/OWASP_Top_Ten_Project" target="_blank">OWASP Top 10</a> page to read about a vulnerability, then choose it from 
	the list on the left to try it out. Hints may help.
	<br/><br/>
	Mutillidae currently has two modes: secure and insecure (default). In insecure mode, the 
	project works like Mutillidae 1.0. Pages are vulnerable to at least the topic they 
	fall under in the menu. Most pages are vulnerable to much more. In secure mode, 
	Mutillidae attempts to protect the pages with server side scripts. Also, hints are disabled in
	secure mode. In the interest of makign as many challenges as possible, this can be defeated. 
	<br/><br/>
	In Mutillidae 2.0, the code has been commented to allow the user to see how the defense works.
	To get the most out of the project, avoid reading the source code until after learning how
	to exploit it. But if you get stuck, the comments should help. Learning how the attack
	works should help to understand the defense.
</span>
