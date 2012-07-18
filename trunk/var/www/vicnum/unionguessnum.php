<?php
// retrieving posted info 
$input = $_POST['player'] ;
$unionname = $_COOKIE['unionname'] ;
if($unionname == "") $unionname = "blank"  ;
//block stored XSS 
$tags = "/<>/";
if (preg_match($tags,$unionname))  {
$unionname = "XSS_unknown" ; }
?>
<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Guessnum results from the database</title>
 
</head>
<body background="images/ctech.png" text=navy>
<table  CELLPADDING="1" WIDTH="100%"> 
  <tr> 
    <td WIDTH="80%"><img src="images/guessnumwelcome.png">
  </tr> 
</table> 
<hr size="3" color="#FF00FF"> 
<h2><u>Search Results</u></h2>
<pre>

<?php 

echo "<h3>You are   $unionname :" ;
echo "<h3>You have requested results for Guessnum player $input :" ;

   $connection = mysql_connect("localhost","vicnum","vicnum");
   mysql_select_db("vicnum", $connection);

   $query = "SELECT * FROM guessnumresults ";
   $totalresult = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());
   $total = mysql_num_rows($totalresult);
 

   $query = "SELECT name,guess,count,tod FROM guessnumresults WHERE name  = '$input'";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());

   if  (mysql_num_rows($result) > $total ) {
   $query = "INSERT INTO unionresults(name,unionquery) VALUES(\"$unionname\",\"$input\")";
   $unionresult = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());
	}   

if  (mysql_num_rows($result) > 0 ) {
   while ($row = mysql_fetch_array($result, MYSQL_NUM))
   {

      print "\n\n";

     print "$row[0] has guessed $row[1] in $row[2] guess(es) on $row[3] \n";
   }
}
else
{
echo ' but no entries were found.<br><p>' ;
}
?>
</pre>
<hr size="3" color="#FF00FF">
 <a href="index.html">Play Again</a>
<p><p>
<hr size="3" color="#FF00FF">
<pre>

<h4>Guessnum is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>

