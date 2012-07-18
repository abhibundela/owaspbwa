#!/usr/bin/perl 
use CGI ':standard' ;
use MIME::Base64 ;

# can be called from two different places 
# After the number has been guessed the player will be invited to save his score 

$player=param('player') ;
$userguess=param('userguess');
$nc  = param('VIEWSTATE'); 
# code to unobfuscate follows , must sync with guessnum1 and guessnum3
$guess = decode_base64($nc); 
# $guess = $guess -9 ;
$oldguess = param('oldguess')  || "" ; 
print header ;
print "<html><head><title>Guessnum - Guess the Computer's number</title>" ;
print "<script language=\"JavaScript\">" ;
print "<!--\n" ;
print ("function sf() {document.F.userguess.focus(); }") ;
print "//-->\n" ; 
print "</script>" ;

print "<script language=\"JavaScript\">" ;
print "function checktag(){ " ;
print "var RE = /\\D/; " ;
print " if (RE.test(document.F.userguess.value)) { " ;
print "  alert(\"Only numbers are valid in this field\"); " ;
print "	return false; " ;
print " } else { " ;
print "  	return true; " ;
print " } " ;
print "} " ;
print "</script> " ;
print "<body background=\"../images/ctech.png\" text=navy  onLoad=sf()>" ;

print " <table  CELLPADDING=1 WIDTH=100%>  " ;
print "   <tr>  " ;
print "     <td WIDTH=70%><img src=\"../images/guessnum.png\"> ";
print "   </tr>" ;
print " </table>" ;
print "<hr size=\"3\" color=\"#FF00FF\"><p>";
# diagnostic code follows 
# foreach my $name ( param())  {
#  $value = param($name) ;
#  print "<p><b>Name is $name : Value is $value </b> " ; }

$_ = $userguess ;
if (/^0$/) { print "<h2> give up? - the answer is  $guess \n"; exit ;}
if (!(/^\s*[0-9]{3}\s*$/))  { print "<h2> Your guess was not valid. Enter a 3 digit number as a guess.  Hit the back button on the browser and try again! \n" ;
		     		exit ; }
print "<h2>","Hi $player - you have chosen the number $userguess  </h2><p>" ; 

$cnt = (length($oldguess)/5) + 1 ;
if ($guess =~ /$_/) {

	print "<hr size=\"3\" color=\"#FF00FF\"><p>";
  	print "<h2><b>Congratulations - you guessed $guess in $cnt attempts</h2><p>";
# Consider encoding the guess here as well		
	print "<h3>Click on the CONTINUE button to proceed<br>";
	print "<form NAME=F ACTION=guessnum3.pl METHOD=post>" ;
	print "<input type=hidden name=player value=$player>"; 
	print "<input type=hidden name=cnt value=$cnt>"; 
        print "<input type=hidden name=VIEWSTATE value=$nc>";
    print "<input TYPE=SUBMIT  VALUE=CONTINUE>" ;
	print "</form></h3><p>" ;
	
print "<br><hr size=\"3\" color=\"#FF00FF\"><p>";
print "<h4>Guessnum is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. <br> For comments please visit the <a href=\"http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project\">OWASP project page.<A>"; 
	exit ; }

$charfound = 0;
for ($i=0;$i<3;$i++) {

	$char =substr($_,$i,1) ;
	$charfound++ if ($guess =~ /$char/) ;  }
	
	$charright = 0 ;
	for ($i=0;$i<3;$i++) {
	$charright++ if (substr($_,$i,1) eq substr($guess,$i,1))  ; }

print "<table border=5 cellpadding=5 width=80%>" ;
print "<tbody><tr>" ;
print "<td align=right width=22%>Last guess of $userguess</td> " ;
print "<td width=52%>had $charfound numbers found. $charright in the right position. </td></tr> " ;
$i = 0 ;
chomp $oldguess ;
while ($i < length($oldguess)) {

$tempstr = substr($oldguess,$i,3) ;
$tempcnt = substr($oldguess,$i+3,1) ;
$tempright = substr($oldguess,$i+4,1) ;
print "<td align=right width=22%>Earlier guess of $tempstr</td>" ;
$s = "s";
$s = "" if  ($tempcnt eq "1" )  ;
print "<td width=52%>had $tempcnt digit$s. $tempright in the correct position</td></tr> " ;
$i += 5; 				}

$oldguess =  $_  . $charfound . $charright . $oldguess  ;

print <<mk
</table>
<hr>
<p>
Enter your next guess and then click on the Guess button  </h2>
<form NAME="F" ONSUBMIT="return checktag()" ACTION="guessnum2.pl" METHOD="post">
<input type="text" size=5 maxlength=3 name="userguess" >
<input type="hidden" name="player" value="$player">
<input type="hidden" name="mcode" value="$mcode">
<input type="hidden" name="VIEWSTATE" value="$nc">
<input type="hidden" name="oldguess" value=$oldguess>
<input TYPE="SUBMIT"  VALUE="GUESS">
<input TYPE="RESET"  VALUE="Clear">
</form>
<hr size="3" color="#FF00FF">
<pre>

<h4>Guessnum is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
mk
