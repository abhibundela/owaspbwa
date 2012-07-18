<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Jotto players with perfect scores </title>
</head>
<body background="images/ctech.png" text=navy >

<h2><u>Top Jotto Players</u></h2>
<hr size="3" color="#FF00FF"></h2>

<pre>
<?php
   $connection = mysql_connect("localhost","vicnum","vicnum");

   mysql_select_db("vicnum", $connection);

   $result = mysql_query ("SELECT name,guess,count,tod FROM
                          jottoresults WHERE count =1 order by tod", $connection);
   $cnt = mysql_num_rows($result); 

print "<H2>Below please find all $cnt Jotto players in the database with perfect scores.\n<hr>" ;
   
   while ($row = mysql_fetch_array($result, MYSQL_NUM))
   {
     print "$row[0] has guessed $row[1] in $row[2] guess on $row[3] \n";
   }

?>
</pre>
<p><p>
<hr size="3" color="#FF00FF">
<pre>


<h4>Jotto is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>
<br></pre>
</body>
