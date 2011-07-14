<?php
/**
* XCloner
* Oficial website: http://www.joomlaplug.com/
* -------------------------------------------
* Creator: Liuta Romulus Ovidiu
* License: All Rights Reserved
* Email: admin@joomlaplug.com
* Revision: 1.0
* Date: July 2007
**/
if ((!extension_loaded('zlib')) &&(function_exists('ob_start'))) {
                ob_end_clean();
                ob_start('ob_gzhandler');
        } 

####################################

$_CONFIG['multiple_config_dir'] = "configs";
//$strlen = strlen($_CONFIG['backup_path']);
//if((substr($_CONFIG['backup_path'], $strlen-1, $strlen) != '/') && (substr($_CONFIG['backup_path'], $strlen-1, $strlen) != '\\'))
//	$_CONFIG['backup_path'] .= "/";

$_CONFIG['backup_path'] = realpath($_CONFIG['backup_path'])."/";
$_CONFIG['backups_dir'] = realpath($_CONFIG['backup_path'])."/administrator/backups";

$_CONFIG['backup_path'] = str_replace("\\","/", $_CONFIG['backup_path']);
$_CONFIG['backups_dir'] = str_replace("\\","/", $_CONFIG['backups_dir']);


$_CONFIG['exfile'] = $_CONFIG['backups_dir']."/.excl";
$_CONFIG['exfile_tar'] = $_CONFIG['backups_dir']."/.excl_tar";
$_CONFIG['script_path'] = str_replace("\\","/",dirname(__FILE__));

$lang_dir =  "language";

$task = $_REQUEST['task'];
####################################


if($_CONFIG['enable_db_backup']){

### Connecting to the mysql server
@mysql_connect($_CONFIG['mysql_host'], $_CONFIG['mysql_user'], $_CONFIG['mysql_pass']) or
    E_print("Could not connect: " . mysql_error());
@mysql_select_db($_CONFIG['mysql_database']) or E_print("Unable to select database ".$_CONFIG['mysql_database']);
@mysql_query("SET NAMES 'utf8'");
}


### loading language
if($_CONFIG['select_lang']!="")

    $mosConfig_lang = $_CONFIG['select_lang'];

if (file_exists( "language/".$mosConfig_lang.".php" )) {

    include_once( "language/".$mosConfig_lang.".php" );

    @include_once( "language/english.php" );

} 

else{

	include_once( "language/english.php" );

}

?>
