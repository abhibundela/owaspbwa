#!/usr/bin/perl 
use CGI ':standard' ;
use MIME::Base64 ;

# can be called from two different places 
# either jotto1.pl the very first time or
# by itself. jotto2.pl guess will store previous guesses and call itself
# After the word has been guessed the player will be invited to save his score 

$player=param('player') ;
$cnt=param('cnt') ;
$guess=param('guess');
$encodedguess=$guess;
$guess =~ tr/A-Za-z/N-ZA-Mn-za-m/;
$userguess=param('userguess');
$oldguess=param('oldguess');
print header ;
print "<html><head><title>Jotto - Guess the computer's word</title>" ;
print "<script language=\"JavaScript\">" ;
print "<!--\n" ;
print ("function sf() {document.F.userguess.focus(); }") ;
print "//-->\n" ; 
print "</script>" ;

print "<script language=\"JavaScript\">" ;
print "function checktag(){ " ;
print "var RE = /[a-zA-Z/; " ;
print " if (RE.test(document.F.userguess.value)) { " ;
print "  alert(\"Only letters are valid in this field\"); " ;
print "	return false; " ;
print " } else { " ;
print "  	return true; " ;
print " } " ;
print "} " ;
print "</script> " ;
print ("<body bgcolor=white text=navy  onLoad=sf()>") ;

print " <table  CELLPADDING=1 WIDTH=100%>  " ;
print "   <tr>  " ;
print "     <td WIDTH=90%><h2><u>Jotto</h2></u>  " ;
print "   </tr>" ;
print " </table>" ;
print "<hr size=\"3\" color=\"#FF00FF\"><p>";
chomp $userguess;
$_ = $userguess ;
if (/^q$/) { print "<h2> give up? - the answer is  $guess \n"; exit ;}
if (!(/^\s*[a-zA-Z]{5}\s*$/))  { print "<h2> Your guess $userguess was not valid. Enter a 5 letter word as a guess.  Hit the back button on the browser and try again! \n" ;
		     		exit ; }
$userguess=lc($_);
print "<h2>","Hi $player - you have chosen the word $userguess  </h2><p>" ; 
$cnt ++ ;
if ($guess =~ $userguess) {

	print "<hr size=\"3\" color=\"#FF00FF\"><p>";
  	print "<h2><b>Congratulations - you guessed $guess in $cnt attempts</h2><p>";
	print "<h3>Click on the CONTINUE button to proceed<br>";
	print "<form NAME=F ACTION=jotto3.pl METHOD=post>" ;
	print "<input type=hidden name=player value=$player>"; 
	print "<input type=hidden name=cnt value=$cnt>"; 
        print "<input type=hidden name=guess value=$encodedguess>";
    print "<input TYPE=SUBMIT  VALUE=CONTINUE>" ;
	print "</form></h3><p>" ;
	
print "<br><hr size=\"3\" color=\"#FF00FF\"><p>";
print "<h4>Jotto is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. <br> For comments please visit the <a href=\"http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project\">OWASP project page.<A>"; 
	exit ; }
$fnd = 0;
$_ = $userguess ;
for ($i=0;$i<=4;$i++) {
$char = substr($_,$i,1) ;
$fnd++ if ($guess =~ /$char/ )  ; }
$s = "s";
$s = "" if  ($tempcnt eq "1" )  ;

print "<table border=5 cellpadding=5 width=80%>" ;
print "<tbody><tr>" ;
print "<td align=right width=22%>Last guess of $userguess</td> " ;
print "<td width=52%>had $fnd letter$s found. </td></tr> " ;
$i = 0 ;
chomp $oldguess ;
while ($i < length($oldguess)) {

$tempstr = substr($oldguess,$i,5) ;
$tempcnt = substr($oldguess,$i+5,1) ;
print "<td align=right width=22%>Earlier guess of $tempstr</td>" ;
$s = "s";
$s = "" if  ($tempcnt eq "1" )  ;
print "<td width=32%>had $tempcnt letter$s found</td></tr> " ;
$i += 6; 				}

$oldguess =   $oldguess . $userguess .$fnd   ;

$guess =~ tr/A-Za-z/N-ZA-Mn-za-m/;
print <<mk
</table>
<hr>
<p>
Enter your next guess and then click on the Guess button  </h2>
<form NAME="F" ONSUBMIT="return checktag()" ACTION="jotto2.pl" METHOD="post">
<input type="text" size=5 maxlength=5 name="userguess" >
<input type="hidden" name="player" value="$player">
<input type="hidden" name="guess" value="$guess">
<input type="hidden" name="cnt" value="$cnt">
<input type="hidden" name="oldguess" value=$oldguess>
<input TYPE="SUBMIT"  VALUE="GUESS">
<input TYPE="RESET"  VALUE="Clear">
</form>
<hr size="3" color="#FF00FF">
<pre>

<h4>Jotto is part of the Vicnum project which was developed for educational purposes to demonstrate common web vulnerabilities. 

For comments please visit the <a href="http://www.owasp.org/index.php/Category:OWASP_Vicnum_Project">OWASP project page.<A>

<br></pre>
</body>
mk
