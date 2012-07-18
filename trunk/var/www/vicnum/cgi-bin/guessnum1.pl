#!/usr/bin/perl 
use CGI ':standard' ;
use MIME::Base64 ;
$player=param('player') ;
$admin=param('admin') ;

if ($admin eq 'Y')  {
#print "Going to admin page ...... \n " ; 
$url =  $ENV{'SERVER_ADDR'};
print "Location:  http://$url/admin.html\n\n";
		}
my $guess = "" ; 
my $nc = "" ; 
while (length($guess) < 3) {
my $randnum = 0 ;
$randnum = int(rand(10)) ;
$guess .= $randnum unless ($guess =~ /$randnum/)  ; }
#  code to obfuscate  
$nc = encode_base64($guess); 
print header; 
print "<head><title> Let's Play Guessnum </title></head>" ;
print "<body background=\"../images/ctech.png\" text=navy onLoad=sf()>" ;
#  code to block xss  
$_ = $player ;
# code below prevents entering <> in a name
#if (/[\<\>]/) { print "<h2>invalid input - hit the back button and try again\n"; exit ;}

print <<mk
<table  CELLPADDING="1" WIDTH="100%"> 
  <tr> 
    <td WIDTH="80%"><img src="../images/guessnum.png">
  </tr> 
</table> 
<hr size="3" color="#FF00FF"> 
<h2>Welcome $player - the computer has chosen a number 
<script language="JavaScript">
function sf() {document.F.userguess.focus();}
</script>

<script language="JavaScript">
function checktag(){
var RE = /\\D/;
 if (RE.test(document.F.userguess.value)) {
  alert("Only numbers are valid in this field");
	return false;
 } else {
  	return true;
 }
}
</script>
<p>
Enter your first guess and then click on the Guess button  </h2>
<form NAME="F" ONSUBMIT="return checktag()" ACTION="guessnum2.pl" METHOD="post">
<input type="text" size=3 maxlength=3 name="userguess" >
<input type="hidden" name="player" value="$player">
<input type="hidden" name="VIEWSTATE" value="$nc">
<input type="hidden" name="oldguess">
<input TYPE="SUBMIT"  VALUE="GUESS">
<input TYPE="RESET"  VALUE="Clear">
</form>
<p>
<hr size="3" color="#FF00FF">
<p>
Guessnum is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the 
<a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project"> OWASP project page.</A>
<p><p>
<a href="http://www.linkedin.com/in/mkraushar">Mordecai Kraushar</a>
<br>
<br>
<a href="http://www.ciphertechs.com" >
<img SRC="../images/CipherTechs.jpg" align="LEFT"></a> 
<body>
mk
