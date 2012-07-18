<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd">
<html>
<?php 
$admin = $_GET["admin"] ;
$unionname = $_GET["unionname"] ;
setcookie("unionname",$unionname) ;
?>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Union Challenge result</title>
</head>
<body background="images/ctech.png" text=navy>
<table  CELLPADDING="1" WIDTH="100%"> 
  <tr> 
    <td WIDTH="80%"><img src="images/unionwelcome.png">
  </tr> 
</table> 
<hr size="3" color="#FF00FF"> 
<pre>
<?php 
print "<H2> $unionname,\n" ;  
   $connection = mysql_connect("localhost","vicnum","vicnum");
   mysql_select_db("vicnum", $connection);
   $query = "SELECT name,tod FROM unionresults ";
   $result = mysql_query($query) or die ("ERROR in $query" . " " . mysql_error());

   if  (mysql_num_rows($result) < 1 ) {

print "<H2>No one has successfully completed the Union Challenge\n" ;  }

else {
   if ($admin=="Q") {$result = mysql_query ("SELECT name,unionquery,tod FROM
                          unionresults order by tod ", $connection);
   		while ($row = mysql_fetch_array($result, MYSQL_NUM))
   		{
     		print "<H2>$row[0]\t used $row[1] on $row[2] \n";
   		}	
		   }
   else {$result = mysql_query ("SELECT name,tod FROM
                          unionresults order by tod ", $connection);
	 }
   		while ($row = mysql_fetch_array($result, MYSQL_NUM))
   		{
     		print "<H2>$row[0]\t completed the challenge on $row[1] \n";
   		}
   	}
?>
<hr size="3" color="#FF00FF">
<H2>You can search for your favorite Guessnum player by entering the player's name below<br>and then clicking on the SEARCH button.<form action="unionguessnum.php" method="post">
Guessnum Player: <input type=text name=player size=50> <input type=submit value=SEARCH>
</form>
</pre>
<hr size="3" color="#FF00FF">
<pre>

<H2>You can also search for your favorite Jotto player by entering the player's name below<br>and then clicking on the SEARCH button.<form action="unionjotto.php" method="post">
Jotto Player: <input type=text name=player size=50> <input type=submit value=SEARCH>
</form>
<hr size="3" color="#FF00FF">
<h4>The Union Challenge is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
