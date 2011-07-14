<?php
require_once(dirname(__FILE__) . '/security.inc');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
/* Tell other scripts we passed security.inc ok */
define('G2_SUPPORT', true);
if (!empty($_SERVER['QUERY_STRING'])) {
    foreach (array('phpinfo', 'cache', 'gd', 'chmod') as $script) {
    	/* 
    	 * Don't use isset($_GET[$script]) since we want to allow for GET args could collide 
    	 * with the above mentioned script names
    	 */
	if ($_SERVER['QUERY_STRING'] == $script || 
	        strncmp($_SERVER['QUERY_STRING'], $script . '&', strlen($script)+1) == 0) {
	    include(dirname(__FILE__) . '/' . $script . '.php');
	}
    }
    return;
}
?>
<html>
  <head>
    <title>Gallery Support</title>
    <link rel="stylesheet" type="text/css" href="<?php print $baseUrl ?>support.css"/>
    <style>
      p { padding-left: 10px; margin-top: 2px; }
      h3 { margin-bottom: 2px; }
    </style>
  </head>

  <body>
      <H1> Gallery Support </H1>
      <a href="../../"> Back to Gallery </a>

      <h2>
        Here are some diagnostic scripts that can help you troubleshoot
        problems with your Gallery installation
      </h2>

      <h3> <a href="index.php?phpinfo">PHP Info</a> </h3>
      <p> PHP configuration information </p>
      <h3> <a href="index.php?cache">Cache Maintenance</a> </h3>
      <p> Delete files from the Gallery data cache </p>
      <h3> <a href="index.php?chmod">Filesystem Permissions</a> </h3>
      <p> Change the filesystem permissions of your Gallery and your storage folder. </p>
      <h3> <a href="index.php?gd">GD</a> </h3>
      <p> Information about your GD configuration </p>
  </body>
</html>
