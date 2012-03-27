<div class="page-title">Usage Instructions</div>

<?php include_once './includes/back-button.inc';?>

<ul>
	<li>Created by <a href="http://Irongeek.com">Irongeek.com</a>.</li>
	<li>If you would like to learn about other deliberately vulnerable web 
		applications, check out <a href="http://www.irongeek.com/i.php?page=security/deliberately-insecure-web-applications-for-learning-web-app-security">
		Deliberately Insecure Web Applications For Learning Web App Security</a>. 
	</li>
	<li>If you would like to help in writing the hints sections, please
		<a href="http://www.irongeek.com/i.php?page=contact">email</a>. Your name 
		and a link to your site will be added to the credits page.
	</li>
	<li><u><font color="#FF0000">Do NOT&nbsp; run this code on a production network</font></u>. Either run it on a 
	private network, or restrict your web server software to only use the local 
	loopback address. By default Mutillidae only allows access from localhost 
	(127.*.*.*). Edit the .htaccess file to change this behavior (not recommended on a public network). 
	If for some reason .htaccess is not parsed you can 
	restrict the IP by finding the &quot;Listen&quot; line in the http.conf file and changing 
	it to read: <font color="#008080">Listen 127.0.0.1:80</font>
	</li>
</ul>
