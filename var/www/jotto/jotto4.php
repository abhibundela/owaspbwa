<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Writing Jotto results to database</title>
</head>
<body bgcolor=white text=navy >
<?php 
$name = $_COOKIE['oreos'] ;
$cnt = $_COOKIE['chipsahoy'] ;
$guess = $_COOKIE['mallomar'] ;
$guess =  str_rot13($guess);
?>
<table  CELLPADDING="1" WIDTH="100%"> 
  <tr> 
    <td WIDTH="90%"><h2><u>Jotto</h2></u> 
  </tr> 
</table> 
<hr size="3" color="#FF00FF"> 
<pre>
<?php
//  Following prevents stored xss
$lessthan = "/\</";
if (preg_match($lessthan,$name))  {
 print "<h2> name changed to unknown <p>"; $name = "unknown" ;$cnt =99; }
   $connection = mysql_connect("localhost","jotto","jotto");

   mysql_select_db("jotto", $connection);

   $query = "SELECT name,count,tod FROM results WHERE results.name  = '$name' AND results.guess = '$guess'";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());

   if  (mysql_num_rows($result) < 1 ) {

   $query = "INSERT INTO results(name,guess,count) VALUES(\"$name\",\"$guess\",$cnt)";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());

print "<H2>Your Results have been stored in our database.\nBelow please find the top 10 Jotto players.\nThanks for playing Jotto !\n\n<hr>" ;  }

else {echo "<H2><b>Duplicate names with the same guess value are not allowed!</b><p>Below find the top 10 scores for legitimate Jotto players.<p><hr size=\"3\" color=\"#FF00FF\">"; }
   $result = mysql_query ("SELECT name,guess,count,tod FROM
                          results where count > 0 order by count,tod limit 10", $connection);

   while ($row = mysql_fetch_array($result, MYSQL_NUM))
   {
     print "$row[0]\t has guessed $row[1] in $row[2] guess(es) on $row[3] \n";
   }
print "<H2>Click <a href=\"index.html\">here</a> to play again. You can also search for your favorite Jotto player by entering the player's name below<br>and then clicking on the SEARCH button.<form action=\"jotto5.php\" method=\"post\">
Jotto Player: <input type\"text\" name=player size=30> <input type=submit value=SEARCH>";
?>
</pre>
<p><p>
<hr size="3" color="#FF00FF">
<pre>

<h4>The Vicnum project was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
