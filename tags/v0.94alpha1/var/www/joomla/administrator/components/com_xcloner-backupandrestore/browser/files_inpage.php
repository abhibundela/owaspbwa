<?php
@ini_set("error_reporting", "2");

$thisApp=$_SERVER['PHP_SELF'] . "?browse=true";

if(isset($_GET['browse'])){
      $dir = isset($_GET['dir']) ? $_GET['dir'] : '/files';
      if(strpos($dir, "../")===true){
            exit;
            }

}else{

echo "Please wait... we are loading the folder structure";

}

?>