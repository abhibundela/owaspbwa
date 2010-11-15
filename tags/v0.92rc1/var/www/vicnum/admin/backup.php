<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Copying Vicnum table</title>
</head>
<body bgcolor=white text=navy >
<?php 
$table = $_GET["table"];
?>
<?php
   $connection = mysql_connect("localhost","vicnum","vicnum");

   mysql_select_db("vicnum", $connection);

   $query = "CREATE TABLE $table  like results";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());
   $query = "INSERT $table  SELECT * FROM results";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());
?>
<h3>database table copied to <?php print "$table"; ?>

<p><p>
<hr size="3" color="#FF00FF">
<pre>




<h4>The Vicnum project was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
