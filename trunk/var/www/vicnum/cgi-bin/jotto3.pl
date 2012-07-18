#!/usr/bin/perl
use CGI ':standard' ;
use MIME::Base64 ;

# called when guess has completed
# redirects to php code that writes 

$player=param('player') ;
$cnt=param('cnt');
$guess=param('guess');


$player1 = "0012AA9B12good" . $player ;
$cnt1 = "0029A9B91crisp" . $cnt  ;
$guess1 = "92BEF345Apecan" . $guess  ;

print "Set-Cookie:oreos=$player\;path=/\n" ;
print "Set-Cookie:chipsahoy=$cnt\;path=/\n" ;
print "Set-Cookie:mallomar=$guess\;path=/\n" ;
print "Location: ../jotto4.php\n\n";

