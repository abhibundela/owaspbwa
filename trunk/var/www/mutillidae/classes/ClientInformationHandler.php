<?php
	/*@Name: Client Information Handler
	 *@Author: Jeremy Druin
	 */

class ClientInformationHandler {

	function __construct() {
		
	}// end constructor function
	
    public function getOperatingSystem(){
    	if (isset($_SERVER['HTTP_USER_AGENT'])){
		    $browserarray=explode("; ",$_SERVER['HTTP_USER_AGENT']);
		    $os= $browserarray[2]; 
	    	return $os;
    	}// end if
    }// end function
    
    public function getBrowser(){
    	if (isset($_SERVER['HTTP_USER_AGENT'])){
		    $browserarray=explode("; ",$_SERVER['HTTP_USER_AGENT']);
		    if ($browserarray[1]=="U"){
			    $browser = $browserarray[4];
		    }else{
			    $browser = $browserarray[1];
		    }// end if
	
	    	return $browser;
    	}// end if
    }// end function
    
    public function getClientIP(){
    	if (isset($_SERVER['REMOTE_ADDR'])){
    		return ($_SERVER['REMOTE_ADDR']);
    	}// end if
    }// end function
    
    public function getClientUserAgentString(){
    	if (isset($_SERVER['HTTP_USER_AGENT'])){
    		return ($_SERVER['HTTP_USER_AGENT']);
    	}// end if
    }// end function
    
    public function getClientReferrer(){
    	if (isset($_SERVER['HTTP_REFERER'])){
    		return ($_SERVER['HTTP_REFERER']);
    	}// end if
    }// end function
    
    public function getClientPort(){
    	if (isset($_SERVER['REMOTE_PORT'])){
    		return ($_SERVER['REMOTE_PORT']);
    	}// end if
    }// end function
    
    public function getClientHostname(){
    	if (isset($_SERVER['REMOTE_ADDR'])){
    		/* gethostbyaddr() is causing issues because it doesnt
    		 * have a timeout attribute. It will be brought back if
    		 * a timeout is added.
    		 */
    		//return gethostbyaddr($_SERVER['REMOTE_ADDR']);
    		return $_SERVER['REMOTE_ADDR'];
       	}// end if
    }// end function
    
    public function whoIsClient(){
       	if (isset($_SERVER['REMOTE_ADDR'])){
	    	return $this->doWhoIs($_SERVER['REMOTE_ADDR']);
       	}// end if
    }// end function
	
    public function whoIs($domain){
    	return $this->doWhoIs($domain);
    }// end function
    
	private function doWhoIs($domain) {
 		// credit: http://www.jonasjohn.de/snippets/php/whois-query.htm
 		// Modified by Jeremy Druin
	 		
	    // fix the domain name:
	    $domain = strtolower(trim($domain));
	    $domain = preg_replace('/^http:\/\//i', '', $domain);
	    $domain = preg_replace('/^www\./i', '', $domain);
	    $domain = explode('/', $domain);
	    $domain = trim($domain[0]);
	 
	    // split the TLD from domain name
	    $_domain = explode('.', $domain);
	    $lst = count($_domain)-1;
	    $ext = $_domain[$lst];
	 
	    // You find resources and lists 
	    // like these on wikipedia: 
	    //
	    // http://de.wikipedia.org/wiki/Whois
	    //
	    $servers = array(
	        "biz" => "whois.neulevel.biz",
	        "com" => "whois.internic.net",
	        "us" => "whois.nic.us",
	        "coop" => "whois.nic.coop",
	        "info" => "whois.nic.info",
	        "name" => "whois.nic.name",
	        "net" => "whois.internic.net",
	        "gov" => "whois.nic.gov",
	        "edu" => "whois.internic.net",
	        "mil" => "rs.internic.net",
	        "int" => "whois.iana.org",
	        "ac" => "whois.nic.ac",
	        "ae" => "whois.uaenic.ae",
	        "at" => "whois.ripe.net",
	        "au" => "whois.aunic.net",
	        "be" => "whois.dns.be",
	        "bg" => "whois.ripe.net",
	        "br" => "whois.registro.br",
	        "bz" => "whois.belizenic.bz",
	        "ca" => "whois.cira.ca",
	        "cc" => "whois.nic.cc",
	        "ch" => "whois.nic.ch",
	        "cl" => "whois.nic.cl",
	        "cn" => "whois.cnnic.net.cn",
	        "cz" => "whois.nic.cz",
	        "de" => "whois.nic.de",
	        "fr" => "whois.nic.fr",
	        "hu" => "whois.nic.hu",
	        "ie" => "whois.domainregistry.ie",
	        "il" => "whois.isoc.org.il",
	        "in" => "whois.ncst.ernet.in",
	        "ir" => "whois.nic.ir",
	        "mc" => "whois.ripe.net",
	        "to" => "whois.tonic.to",
	        "tv" => "whois.tv",
	        "ru" => "whois.ripn.net",
	        "org" => "whois.pir.org",
	        "aero" => "whois.information.aero",
	        "nl" => "whois.domain-registry.nl"
	    );
	 
	    if (isset($servers[$ext])){
	    	$nic_server = $servers[$ext];
	    }else{
	    	$nic_server = "whois.arin.net";
	    }// end if
	 
	    $output = '';
	 
	    // connect to whois server:
	    try{
	    	$lWhoisConnection = fsockopen ($nic_server, 43);	
		} catch (Exception $e) {
	    	$lWhoisConnection = "";
		}// end catch
	    
	    if ($lWhoisConnection) {
	        fputs($lWhoisConnection, $domain."\r\n");
	        while(!feof($lWhoisConnection)){
	            $output .= fgets($lWhoisConnection,128);
	        }// end while
	        fclose($lWhoisConnection);
	    }else{ 
	    	$output = "Could not connect to whois server " . $nic_server; 
	    }// end if
	 
	    return $output;
	}// end function
		
}// end class

?>