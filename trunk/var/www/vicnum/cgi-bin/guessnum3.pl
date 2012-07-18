#!/usr/bin/perl
use CGI ':standard' ;
use MIME::Base64 ;

# called from guessnum2 when guess has completed
# original code mentioned database , now redirects to php code that writes 
# optionally insert code to write right now

$player=param('player') ;
$cnt=param('cnt');
$guess=param('VIEWSTATE');

$guess = decode_base64($guess); 

$player1 = "0012AA9B12good" . $player ;
$cnt1 = "0029A9B91crisp" . $cnt  ;
$guess1 = "92BEF345Apecan" . $guess  ;

print "Set-Cookie:Milano=$player1\;path=/\n" ;
print "Set-Cookie:Brussels=$cnt1\;path=/\n" ;
print "Set-Cookie:Geneva=$guess1\;path=/\n" ;
print "Location: ../guessnum4.php\n\n";

