<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Guessnum players  who have clearly hacked </title>
</head>
<body bgcolor=white text=navy >

<h2><u>Guessnum Players with a guess count of zero and a guess value > 999</u></h2>
<hr size="3" color="#FF00FF"></h2>

<pre>
<?php
   $connection = mysql_connect("localhost","vicnum","vicnum");

   mysql_select_db("vicnum", $connection);

   $result = mysql_query ("SELECT name,guess,count,tod FROM
                          guessnumresults WHERE (count =0  AND guess>999) order by tod", $connection);
   $cnt = mysql_num_rows($result); 

print "<H2>Below are all $cnt Guessnum players in the database who have clearly hacked the game.\n<hr>" ;
   
   while ($row = mysql_fetch_array($result, MYSQL_NUM))
   {
     print "$row[0] has guessed $row[1] in $row[2] guesses on $row[3] \n";
   }

?>
</pre>
<p><p>
<hr size="3" color="#FF00FF">
<pre>


<h4>Guessnum is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>
<br></pre>
</body>
