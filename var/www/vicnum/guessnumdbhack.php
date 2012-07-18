<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Guessnum players  who have hacked the game and the database</title>
</head>
<body bgcolor=white text=navy >

<h2>Find the Guessnum player with the worst possible score (if there is a tie find the older record). Place another record in the database with that player's name concatenated to your name and with a positive score. </h2>


<hr size="3" color="#FF00FF"></h2>

<pre>
<?php
   $connection = mysql_connect("localhost","vicnum","vicnum");

   mysql_select_db("vicnum", $connection);

   $result = mysql_query ("SELECT name,guess,count,tod FROM
                          guessnumresults WHERE (count >0  AND name like  \"55Broadway%\") order by tod", $connection);
   $cnt = mysql_num_rows($result); 

print "<H2>Below are all $cnt Guessnum players who have clearly hacked the game and the database.\n<hr>" ;
   
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
