#!/usr/bin/perl 
use CGI ':standard' ;
use MIME::Base64 ;
$player=param('player') ;

my $guess = "" ; 
my $oldguess = "" ; 
my $randnum = 0 ;
open (fhi,"<xotto")  ;
@array = <fhi> ;
close fhi ;
$randnum = int(rand(@array)) ;
$guess = $array[$randnum] ;
$guess =~ tr/A-Za-z/N-ZA-Mn-za-m/;
chomp $guess ; 
print header; 
print "<head><title> Let's Play Jotto </title></head>" ;

print <<mk
<table  CELLPADDING="1" WIDTH="100%"> 
  <tr> 
    <td WIDTH="90%"><h2><u>Jotto</h2></u> 
  </tr> 
</table> 
<hr size="3" color="#FF00FF"> 
<h2>Welcome $player - the computer has chosen a word 
<p>
Enter your first guess and then click on the Guess button  </h2>
<form NAME="F" ACTION="jotto2.pl" METHOD="post">
<input type="text" size=5 maxlength=5 name="userguess" >
<input type="hidden" name="player" value="$player">
<input type="hidden" name="guess" value="$guess">
<input type="hidden" name="oldguess">
<input TYPE="SUBMIT"  VALUE="GUESS">
<input TYPE="RESET"  VALUE="Clear">
</form>
<p>
<hr size="3" color="#FF00FF">
<p>
Jotto is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

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
