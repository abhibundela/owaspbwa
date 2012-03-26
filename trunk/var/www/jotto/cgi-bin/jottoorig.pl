#!/usr/bin/perl 
#  jotto game program to guess a five letter word in as few as possible attempts
# eventually will be a network game 
sub logit { 
	    open(pros,"</var/run/jottopros") ;
	    @array = <pros> ;
	    print "Enter your name for logging: \n" ;
	    $name = <> ;
	    push (@array,"$_[0] guesses by $name" ) ;
	    @array = sort @array ;
	    close(pros) ;
		open (pros,">/var/run/jottopros") ;
	    print @array ;
	    print pros @array ;
		}  
system("clear") ;
print "*" x 73 ;
print "*\n* Welcome to jotto - the computer will think of a valid five letter word.*\n";
print "* Try to guess the word - the computer will tell how many letters match. *\n";
print "* The computer word will use unique letters, but you don't have to !     * \n" ;
print "*" x 73 ;

my $guess = "" ; 
my $randnum = 0 ;
open (fhi,"<jotto")  ;
@array = <fhi> ;
close fhi ;
$randnum = int(rand(@array)) ;
$guess = $array[$randnum] ;
chomp $guess ; 
print "\nWe have the word! Try to guess it \n" ;
 print "*" x 73,"\n" ;
my $i ; my $char = "" ; my $cnt = 0 ; my $userguess = "" ; my $fnd = 0 ;
$startsec = time ;
while (<>) {
chomp ;
if (/^q$/) { print " give up? - the answer is  $guess \n"; exit ;}
if (!(/^\s*[a-zA-Z]{5}\s*$/))  { print "Entry not valid. Enter a 5 letter word as a guess\n" ;
		     		next ; }
# clean the data strip out white space and convert to lower case 
(/\s*(\w+)\s*/)  ;
$userguess = $1 ;
$_ =  lc($userguess)  ;
$cnt ++ ;
$fnd = 0 ;

if ($_ eq  $guess) {  print " Congratulations  you guessed $guess in $cnt attempts\n";
$endsec = time ;
print "It took " ;
$totalsec = $endsec - $startsec ;
$minutes = int($totalsec / 60)  ;
if ( $minutes > 1 ) {$s='s';} else {$s=''} ; 
print "$minutes minute$s: "  if ($minutes > 0) ;

$s='s' ; 
$seconds = $totalsec % 60 ;
print "$seconds second$s.\n" ;


print "Would you like to record your score? Enter y or n \n" ;
	while(1) 		{

				$_ = uc(<>) ;
				exit if (/N\n/) ;
				if (/Y\n/) { logit($cnt) ;
						exit; }
				print "Please enter y or n to record your score\n" ;
				 }
		  }
for ($i=0;$i<=4;$i++) { 
$char = substr($_,$i,1) ;
next unless ($i == index($_,$char) ) ; 
$fnd++ if ($guess =~ /$char/ )  ; }
system("clear") ;
push(@array2,"Guess $cnt of $userguess had $fnd letter(s) \n") ;
print @array2 ;
}

