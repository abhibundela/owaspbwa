<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Jotto players  who have hacked the game and the database</title>
</head>
<body background="images/ctech.png" text=navy >

<h2>Place a record in the database with your name (that clearly identifies you !) concatenated to the end of the magic name and with a score of zero. You can get a hint as to that magic name by finding the worst possible jotto score.  If there is a tie find the older record.</h2>


<hr size="3" color="#FF00FF"></h2>

<pre>
<?php
   $connection = mysql_connect("localhost","vicnum","vicnum");

   mysql_select_db("vicnum", $connection);

   $result = mysql_query ("SELECT name,guess,count,tod FROM
                          jottoresults WHERE (count =0  AND name like  \"lucky%\") order by tod", $connection);
   $cnt = mysql_num_rows($result); 

print "<H2>Below are all $cnt Jotto players who have clearly hacked the game and the database.\n<hr>" ;
   
   while ($row = mysql_fetch_array($result, MYSQL_NUM))
   {
    $xrows = substr($row[0],5);
     print "$xrows has guessed $row[1] in $row[2] guesses on $row[3] \n";
   }

?>
</pre>
<p><p>
<hr size="3" color="#FF00FF">
<pre>


<h4>The Vicnum project was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>
<br></pre>
</body>
