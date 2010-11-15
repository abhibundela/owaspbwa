<?php
?>
<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Clearing Vicnum records </title>
 
</head>
<body>
<pre>

<?php 

echo "<h3>select records being deleted ....";

   // (1) Open the database connection
   $connection = mysql_connect("localhost","vicnum","vicnum");

   // (2) Select the  database
   mysql_select_db("vicnum", $connection);


   $query = "DELETE  FROM results where count > 0 and name like \"%55Broadway%\" ";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());

echo "<h3>records deleted ...";
?>
</pre>
<p><p>
<hr size="3" color="#FF00FF">
<pre>




<h4>The Vicnum project was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
