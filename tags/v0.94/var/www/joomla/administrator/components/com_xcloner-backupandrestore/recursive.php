<?php
$startdir = "D:/server/wamp/www/";
$dir = $startdir;
$last_file = "..";
$recursive_limit = 400;


$f_arr = array();
$d_arr = array() ;
#$dir = "E:/server/www/Joomla/xcloner/images";

while($i++ < 100){

$count = 0;
$k = 0;
$return = "";
$last = recursive($dir, $last_file);
echo "<br /><b>Lastdir:</b> $last<br />" ;

if(dirname($last) == dirname($startdir)){
 
 $f_arr = array_unique($f_arr);
 $d_arr = array_unique($d_arr);
 sort($f_arr);
 sort($d_arr);
 echo "<b>Recurse finished! Files: ".sizeof($f_arr)." ; Directories: ".sizeof($d_arr)."</b>";
 #print_r($f_arr);
 break;
 }

#echo $last_return;
if((!is_dir($last))||($last_return ==  1))
 {
  $dir = dirname($last);
  $last_file = basename ($last);
 } 
else{
 $dir = $last; 
 $last_file = '..';
 
 }



}

function recursive( $dir , $last_file = '..')
{       global $count, $return, $k, $startdir, $last_return, $recursive_limit, $f_arr, $d_arr;
        
        #echo "<br /><b>Opening $dir with last file $last_file</b><br />";
        $last_return = 0;
		
		if (is_dir($dir)) {
           if ($dh = opendir($dir)) {
               while (($file = readdir($dh)) !== false ) {       
			    
					   #echo $file."--".$last_file."<br />";
					   
					   if (($file != $last_file)&&($k == 0))
			             continue;
                       elseif(($file == $last_file)&&($file != '..')){
					     $k = 1; 
					     continue;
					   }else{
						
						 $k =1 ;
					   }
					   #echo "<b>".$file."</b>"; 
		 
						if( $file != "." && $file != ".." )
                        {
                        		$cfile = "$dir/$file";
																					
								if($count == $recursive_limit){
								
								   if($return == "")
									  $return = $cfile;
									  
									$last_return = 2;
									  
									if(is_dir($cfile))
									   $d_arr[] = $cfile;
									else
									   $f_arr[] = $cfile; 
								  
									  echo "return here with $cfile";return $return ;
								}
						  
								++$count;
                         
                                if( is_dir( $dir ."/". $file ) )
                                {
                                        echo "<b>Entering Directory: $cfile</b><br/>";
                                        $d_arr[] = $cfile;
                                        recursive( $cfile );
                                        
                                }
                                else
                                {
                                        
								        echo "file: $cfile<br/>";
								        
								        $f_arr[] = $cfile;
                                
								}
                        }
               }
               closedir($dh);
           }
        }

$last_return = 1;
return $dir;

}


?>