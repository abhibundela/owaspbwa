<?php
/**
* XCloner
* Oficial website: http://www.joomlaplug.com/
* -------------------------------------------
* Creator: Liuta Romulus Ovidiu
* License: GNU/GPL
* Email: admin@joomlaplug.com
* Revision: 1.0
* Date: July 2007
**/


/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' ); 

if($_COOKIE['auth_clone'] != 1)
 setcookie('auth_clone', '1');

class mosTabs{
	
	function mosTabs($int){
		
		echo "<div class=\"tabber\">";
		
	}
	
	function startTab($name, $class){
		
		echo "<div class=\"tabbertab\" title=\"$name\">";
		
		
	}
	
	function endTab(){
		
		echo "</div>";
		
	}
	
	function endPane(){
		
		echo "</div>";
	}
	
}

/**
* @package Joomla
* @subpackage JoomlaCloner
*/
class HTML_cloner {

function header(){

 global $mosConfig_live_site, $task;

?>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>XCloner Backup and Restore</title>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

<link rel="stylesheet" href="css/tabber.css" TYPE="text/css" MEDIA="screen">
<link rel="styleSheet" href="css/dtree.css" type="text/css" />
<link rel="styleSheet" href="css/main.css" type="text/css" />

<script type="text/javascript" src="javascript/tabber.js"></script>
<script type="text/javascript" src="javascript/dtree.js"></script>
<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>

</head>
<body>

<table width='100%' style="padding-left: 3px; padding-right: 4px;align:center;" bgcolor='#ffffff'>
<tr><td align='right'>

</td></tr>

<tr><td align='center'>

<table  width='100%' border='1' bgcolor='white'>
<tr>
<td width='100%'>
<table><tr><td>
<img src="images/backup.png" align="middle">&nbsp;
</td><td>
<h2><?php echo LM_COM_TITLE.$_SERVER['HTTP_HOST']; ?></h2>
<h1>Backup and Restore</h1>
</td></tr></table>
<td>
<?php
# Generating the buttons...
require_once( "toolbar.cloner.php" );
?>

</tr>
</table>
<br />
<table width="100%" cellspacing='3' cellpadding="4" >
<tr><td valign='top' width="160" >
<table width='100%' cellpadding='5' height='100%' class='menu_table'><tr><td>


<div class="dtree">

<a href="javascript: d.openAll();"><?php echo LM_MENU_OPEN_ALL?></a> | <a href="javascript: d.closeAll();"><?php echo LM_MENU_CLOSE_ALL?>l</a><br />
<br />
<script type="text/javascript">
<!--

d = new dTree('d');

d.add(0,-1,'&nbsp;<?php echo LM_MENU_CLONER;?>','index2.php?option=com_cloner','','','images/logo.gif');

d.add(800,0,'&nbsp;<?php echo LM_MENU_ADMINISTRATION;?>','','','','images/actions.gif','images/actions.gif');

d.add(801,800,'&nbsp;<?php echo LM_MENU_CONFIGURATION;?>','index2.php?option=com_cloner&task=config','','','images/gen_settings.png');
d.add(802,800,'&nbsp;<?php echo LM_MENU_CRON;?>','index2.php?option=com_cloner&task=cron','','','images/templatessm.png');
d.add(803,800,'&nbsp;<?php echo LM_MENU_LANG;?>','index2.php?option=com_cloner&task=lang','','','images/lang.png');


d.add(840,0,'&nbsp;<?php echo LM_MENU_ACTIONS;?>','','','','images/actions.gif','images/actions.gif');
d.add(841,840,'&nbsp;<?php echo LM_MENU_View_backups;?>','index2.php?option=com_cloner&task=view','','','images/editionssm.png');
d.add(842,840,'&nbsp;<?php echo LM_MENU_Generate_backup;?>','index2.php?option=com_cloner&task=confirm','','','images/wizardsm.png');
d.add(843,840,'&nbsp;<?php echo LM_MENU_Restore_backup;?>','index2.php?option=com_cloner&task=restore','','','images/wizardsm_restore.gif');

d.add(830,0,'&nbsp;<?php echo LM_MENU_SUPPORT;?>','','','','images/support.png','images/support.png');
d.add(831,830,'&nbsp;<?php echo LM_MENU_FORUM;?>','http://www.xcloner.com/support/forums/','','_blank','images/forum.png','images/forum.png');
d.add(832,830,'&nbsp;<?php echo LM_MENU_WEBSITE;?>','http://www.xcloner.com','','_blank','images/website.png','images/website.png');



d.add(820,0,'&nbsp;<?php echo LM_MENU_Documentation;?>','','','','images/help.png','images/help.png');
d.add(821,820,'&nbsp;<?php echo LM_MENU_ABOUT;?>','index2.php?option=com_cloner&task=about','','','images/about.png','images/about.png');

document.write(d);

//-->
</script></div> </td></tr></table>

<!--XCloner Ads -->
<br />
<table width='100%' cellpadding='5' height='100%' class='menu_table'><tr><td>
<center>

<script type='text/javascript'><!--//<![CDATA[
   var m3_u = (location.protocol=='https:'?'https://www.xcloner.com/ads/www/delivery/ajs.php':'http://www.xcloner.com/ads/www/delivery/ajs.php');
   var m3_r = Math.floor(Math.random()*99999999999);
   if (!document.MAX_used) document.MAX_used = ',';
   document.write ("<scr"+"ipt type='text/javascript' src='"+m3_u);
   document.write ("?campaignid=3");
   document.write ('&amp;cb=' + m3_r);
   if (document.MAX_used != ',') document.write ("&amp;exclude=" + document.MAX_used);
   document.write (document.charset ? '&amp;charset='+document.charset : (document.characterSet ? '&amp;charset='+document.characterSet : ''));
   document.write ("&amp;loc=" + escape(window.location));
   if (document.referrer) document.write ("&amp;referer=" + escape(document.referrer));
   if (document.context) document.write ("&context=" + escape(document.context));
   if (document.mmm_fo) document.write ("&amp;mmm_fo=1");
   document.write ("'><\/scr"+"ipt>");
//]]>--></script><noscript><a href='http://www.xcloner.com/ads/www/delivery/ck.php?n=a6255e1e&amp;cb=1675675575' target='_blank'><img src='http://www.xcloner.com/ads/www/delivery/avw.php?campaignid=3&amp;cb=1675675575&amp;n=a6255e1e' border='0' alt='' /></a></noscript>

</center>
</td></td></table>
<!-- END Ads -->

</td><td valign='top' align='left' style="padding-left: 20px;">

    
<?php
if($_REQUEST['mosmsg']!="")
 
 echo "<center><h2>".$_REQUEST['mosmsg']."</h2></center>";

}

function footer(){

?>
</td></tr></table>
<hr><br /><br />
<center>
<p>Powered by <a href='http://www.xcloner.com' target='_blank'>XCloner</a>. All rights reserved!</p></center>

</td></tr></table>

<?php

}

function  _FDefault(){
?>

<form action="index2.php" method="post" name="adminForm">

<table class="adminform">
<tr><th valign='top'  >
<?php echo LM_JOOMLAPLUG_CP?>
</th>
</table>

<table class="adminform"  >
<tr><td >
<div id="cpanel">

        <div style="float:left;">
			<div class="icon">

				<a href="index2.php?option=com_cloner&amp;task=config">
					<img src="images/settings.png"
                    alt="Settings" align="middle" name="" border="0" />
                    <span><?php echo LM_MAIN_Settings?></span>
				</a>
			</div>
		</div>

        <div style="float:left;">
			<div class="icon">

				<a href="index2.php?option=com_cloner&amp;task=view">
					<img src="images/editions.png"
                    alt="View Backups" align="middle" name="" border="0" />
                    <span><?php echo LM_MAIN_View_Backups?></span>
				</a>
			</div>
		</div>

        <div style="float:left;">
			<div class="icon">

				<a href="index2.php?option=com_cloner&amp;task=confirm">
					<img src="images/wizard.png"
                    alt="MagaGenerate Backup" align="middle" name="" border="0" />
                    <span><?php echo LM_MAIN_Generate_Backup?></span>
				</a>
			</div>
		</div>
  
       <div style="float:left;">
			<div class="icon">

				<a href="index2.php?option=com_cloner&amp;task=about">
					<img src="images/lhelp.png"
                    alt="MagaGenerate Backup" align="middle" name="" border="0" />
                    <span><?php echo LM_MAIN_Help?></span>
				</a>
			</div>
		</div>




</div>
</td></tr>
</table>
<input type="hidden" name="option" value="com_cloner" />
<input type="hidden" name="task" value="lang" />
</form>

<?php
}

/*The basic authentification form*/
function Login(){
	
	?>
	<center><br />
	<form action="index2.php" method="post" name="adminForm">
	<table border='1' ><tr><td align='center'>
	<table align='center' cellpadding='10' cellspacing='20'>
	<tr ><td colspan='2' align='center'><b>Authentification Area:</b></td></tr>
	<tr><td>Username:</td><td><input type='text' size='30' name='username'></td></tr>
	<tr><td>Password:</td><td><input type='password' size='30' name='password'></td></tr>	
	
	<tr><td colspan='2'><?php echo LM_LOGIN_TEXT;?></td></tr>
	
	</table>
	</td></tr>
	
	
	
	</table>
	
	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    
	</form>
	</center>
<?php
	
}

function Cron(){
    global $_CONFIG;
?>
<table class='adminform'>
<tr><th>
<?php echo LM_CRON_TOP?>
</th></tr>
<tr><td>
<pre>
<?php echo LM_CRON_SUB?>
<br /><b>For Joomla:</b>
<span style='background: #eeeeee'>
/usr/bin/php  <?php echo dirname(__FILE__);?>/cloner.cron.php
<br />
or 
<br />
links http://link_to_xcloner_dir/cloner.cron.php
<br />
or
<br />
lynx -source http://link_to_xcloner_dir/cloner.cron.php
</span>

For <b>Running Multiple Crons</b>, you need to first create a custom configuration file in the XCloner Configuration -> Cron tab
and then replace "cloner.cron.php" with "cloner.cron.php?config=myconfig.php", only use 'links' or 'lynx' options to run the cronjob

If you would like to use the <b>php SSH command</b> for running Multiple Crons, you will need to replace 
the  "cloner.cron.php" with <b>"cloner.cron.php myconfig.php"</b> in the command line.

<?php echo LM_CRON_HELP?>
</pre>
</td></tr>
</table>

<?php
}


function Translator_Edit_DEFAULT($option, $content, $file, $lang){
	global $_CONFIG;
?>	
	<form action="index2.php" method="post" name="adminForm">
    <table class="adminlist">
    <tr>
      <th align="left"><?php echo LM_LANG_EDIT_FILE?> <?php echo $file?></th>
    </tr>

    <tr>
	 
	  <td><textarea name='def_content' cols='100' rows='30'><?php echo $content;?></textarea></td>
	
	</tr>  

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>	

<?php

}

function Translator_Add($option){
	global $_CONFIG;
?>	
	<form action="index2.php" method="post" name="adminForm">
    <table class="adminlist">
    <tr>
      <th align="left"><?php echo LM_LANG_NEW?></th>
    </tr>

    <tr>
	 
	  <td><input size='40' type=text name='lname' value=''></td>
	
	</tr>  

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="add_lang_new" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>	
<?php	
}

function Translator_Edit($option, $data, $def_data, $file, $lang){
    global $_CONFIG;
?>	
	
	<form action="index2.php" method="post" name="adminForm">
    <table class="adminlist">
    <tr>
      <th align="left"><?php echo LM_LANG_EDIT_FILE?> <input type=text name='lfile' size=100 value='<?php echo $file?>'><br />
	  <font color='red'><?php echo LM_LANG_EDIT_FILE_SUB?></font>
	  
	  <script language="javascript" type="text/javascript">
			function submitbutton(pressbutton) {
				var form = document.adminForm;

                if (pressbutton == 'save_lang_apply') {
					if(confirm('Before you continue please make sure you are still logged in, else press Cancel and then try again!')){
					submitform( pressbutton );
					}
     				return;
				}
                else
				if (pressbutton == 'save_lang') {
					if(confirm('Before you continue please make sure you are still logged in, else press Cancel and then try again!')){
					submitform( pressbutton );
					}
					return;
				}
                else{
                    submitform( pressbutton );
                    }
           }
      </script>

	  </th>
    </tr>
    </table>
    <?php
	foreach($data as $key=>$value)
	if($def_data[$key]!="")	{
	if($i++ %2 == 0)
	 $bgcolor = '#eeeeee';
	else
	 $bgcolor = '#dddddd'; 
	?>
	<table class="adminlist">
    <tr>
      <th width='50%' align="left">Default Variable <?php echo $key?></th>
      <th width='50%' align="left">Translation <?php echo $key?></th>
    </tr>
	<tr bgcolor="<?php echo $bgcolor?>">
	  <td><textarea cols=65 rows=3 ><?php echo stripslashes($def_data[$key])?></textarea></td>
	 
	  <td bgcolor='<?php if( trim(str_replace(array("\n","\r"," "),array("","",""),$def_data[$key])) != 
	                      trim(str_replace(array("\n","\r"," "),array("","",""),$value))) 
						  echo 'green'; 
						 else 
						  echo 'red';?>'>
	  <textarea cols=65 rows=3 name=lang[<?php echo $key?>]><?php echo stripslashes($value)?></textarea></td>
	</tr>
	
	<?php	
	}
	?> 

	<input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="language" value="<?php echo $lang?>" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>	
	
<?php	
}

function Translator($option, $lang_arr){
    global $_CONFIG;  

?>	
	
	 <form action="index2.php" method="post" name="adminForm">
    <table class="adminlist">
    <tr>
      <th width="5" align="left">#</th>
      <th width="5" align="left">
      <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $lang_arr ); ?>);" />
      </th>
      <th align="left">
      <?php echo LM_LANG_NAME ?>
      </th>
    </tr>
    <?php
	
     for($i=0; $i<sizeof($lang_arr); $i++){
		
		?> 
		
     		<tr>
		      <td width="5" align="left"><?php echo ($i+1);?></td>
			  <td width="5" align="left">
			  <input type="checkbox" id="cb<?php echo $i ?>" name="cid[<?php echo $i?>]" value="<?php echo $i ?>" onclick="isChecked(this.checked);" />
              <input type="hidden"  name="files[<?php echo $i?>]" value="<?php echo $lang_arr[$i] ?>" onclick="isChecked(this.checked);" />
			  </td>
		      <td align="left" >
			  <a href="index2.php?option=<?php echo $option;?>&task=edit_lang&langx=<?php echo $lang_arr[$i];?>"><?php echo ucfirst($lang_arr[$i])?>
			  </td>
			</tr>
	   <?php		  
		
	}
	?> 

    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="lang" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>	
<?php	
}

function showBackups( &$files, &$sizes, $path, $option ) {
    // ----------------------------------------------------------
    // Presentation of the backup set list screen
    // ----------------------------------------------------------
    global $baDownloadPath, $_CONFIG;

    ?>
    <form action="index2.php" method="post" name="adminForm">
    <table class="adminlist">
    <tr>
      <th width="5">#</th>
      <th width="5">
      <input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count( $files ); ?>);" />
      </th>
      <th width="33%" class="title">
      <?php echo LM_COL_FILENAME ?>
      </th>
      <th align="left" width="10%">
      <?php echo LM_COL_DOWNLOAD ?>
      </th>
      <th align="left" width="10%">
      <?php echo LM_COL_SIZE ?>
      </th>
      <th align="left" width="43%">
      <?php echo LM_COL_DATE ?>
      </th>
      </tr>
    <?php
    $k = 0;
    for ($i=0; $i <= (count( $files )-1); $i++) {
      $date = date ("D jS M Y H:i:s (\G\M\T O)", filemtime($path.'/'.$files[$i]));
      ?>
      <tr class="<?php echo "row$k"; ?>">
        <td>
        <?php echo $i+1; ?>
        </td>
        <td align="center">
        <input type="checkbox" id="cb<?php echo $i ?>" name="cid[<?php echo $i?>]" value="<?php echo $i ?>" onclick="isChecked(this.checked);" />
         <input type="hidden"  name="files[<?php echo $i?>]" value="<?php echo $files[$i] ?>" onclick="isChecked(this.checked);" />
        </td>
        <td >
        <a target='_blank' href="<?php echo "index2.php?option=com_cloner&task=download&file=".'/'.urlencode($files[$i]); ?>"><?php echo $files[$i]; ?></a><input type="hidden" id="f<?php echo $i ?>" name="f<?php echo $i ?>" value="<?php echo $files[$i]; ?>" >
        </td>
        <td align="left">
        <a target='_blank' href="<?php echo "index2.php?option=com_cloner&task=download&file=".'/'.urlencode($files[$i]); ?>"><img src="images/filesave.png" border="0" alt="<?php echo LM_DOWNLOAD_TITLE ?>" title="<?php echo LM_DOWNLOAD_TITLE ?>"></a></td >
        <!--<td  align="left">
         <?php

          $userfile = $_CONFIG['baDownloadPath']."/".$files[$i];
          $localfile = $_CONFIG['clonerPath']."/".$files[$i];
          if(@file_exists($userfile))
           echo "<a href=\"index2.php?option=com_cloner&task=action&action=delete&file=$files[$i]\">
                 <img border='0' src='images/tick.png'></a>";
          else
           echo "<a href=\"index2.php?option=com_cloner&task=action&action=copy&file=$files[$i]\">
                 <img border='0' src='images/publish_x.png'></a>";
         ?>
        </td>-->
        <td align="left">
        <?php echo $sizes[$i]; ?>
        </td >
        <td align="left">
        <?php echo $date; ?>
        </td>
      </tr>
      <?php
      $k = 1 - $k;
    }
    ?>
    </table>

    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
    <br/>&nbsp;
    <?php
  }

  function Config($option){
            global $config_file,$_CONFIG, $lang_array, $database, $mosConfig_db;
  ?>
    <form name='adminForm' action='index2.php' method='POST'>
    <table class='adminform'>
    <tr><th colspan='2'>
    <?php echo LM_CONFIG_EDIT?> <?php echo $config_file?>
    </th></tr>
    </table>
    <?php
    $tabs = new mosTabs(1);
    $tabs->startTab(LM_TAB_GENERAL,"config-general-tab");
    ?>
    <table class='adminform'>
    
	
	<tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_BSETTINGS?>
     </th>
    </tr>

    <tr>
     <td>
      <?php echo LM_CONFIG_UBPATH?>
     </td>
     <td>
      <input type=text size=50 name='backup_path' value='<?php echo $_CONFIG[backup_path]?>'>
      <br /><?php echo LM_CONFIG_UBPATH_SUB?>
     </td>
    </tr>

    <tr>
     <td  width='250'>
      <?php echo LM_CONFIG_BPATH?>
     </td>
     <td>
      <input type=text size=50 name='clonerPath' value='<?php echo $_CONFIG[clonerPath]?>'>
      <br /><?php echo LM_CONFIG_BPATH_SUB?>
     </td>
    </tr>


    <tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_BSETTINGS_OPTIONS?>
     </th>
    </tr>

    <tr>
     <td>
      <?php echo LM_CONFIG_MANUAL_BACKUP;?>
     </td>
     <td>
      <?php echo LM_YES?> <input type=radio size=50 value=1 name='backup_refresh' <?php if($_CONFIG[backup_refresh]==1) echo 'checked';?>>
      <?php echo LM_NO?> <input type=radio size=50 value=0 name='backup_refresh' <?php if($_CONFIG[backup_refresh]==0) echo 'checked';?>>
      <br><small><?php echo LM_CONFIG_MANUAL_BACKUP_SUB?></small>
     </td>
    </tr>

	<tr>
     <td>
      <?php echo LM_CRON_COMPRESS?>
     </td>
     <td>
      <?php echo LM_YES?> <input type=radio size=50 value=1 name='backup_compress' <?php if($_CONFIG[backup_compress]==1) echo 'checked';?>>
      <?php echo LM_NO?> <input type=radio size=50 value=0 name='backup_compress' <?php if($_CONFIG[backup_compress]==0) echo 'checked';?>>
     <br /> <small>set it to Yes in order to compress the files into smaller backups</small>
     </td>
    </tr>

    <tr>
     <td>
      <?php echo LM_CRON_DB_BACKUP?>
     </td>
     <td>
      Yes <input type=radio size=50 value=1 name='enable_db_backup' <?php if($_CONFIG[enable_db_backup]==1) echo 'checked';?>>
      No <input type=radio size=50 value=0 name='enable_db_backup' <?php if($_CONFIG[enable_db_backup]==0) echo 'checked';?>>
      <br />
      <?php echo LM_CRON_DB_BACKUP_SUB?>
     </td>
    </tr>
	
	<tr>
     <td>
      <?php echo LM_CONFIG_SYSTEM_MBACKUP?>
     </td>
     <td>
      Yes <input type=radio size=50 value=1 name='add_backups_dir' <?php if($_CONFIG[add_backups_dir]==1) echo 'checked';?>>
      No <input type=radio size=50 value=0 name='add_backups_dir' <?php if($_CONFIG[add_backups_dir]==0) echo 'checked';?>>
      <br />
      <?php echo LM_CONFIG_SYSTEM_MBACKUP_SUB?>
     </td>
    </tr>	
    
    <tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_BSETTINGS_SERVER?>
     </th>
    </tr>
    
    
    <tr >
     <td>
      <?php echo LM_CONFIG_MEM?>
     </td>
     <td align='left'>
     <table width='85%' cellpadding='0' cellspacing='2' border='1'>
     <tr bgcolor='#efefef'><td valign='middle'>
     <?php echo LM_ACTIVE;?> <input type=checkbox value=1 name='mem' <?php if($_CONFIG[mem]==1) echo 'checked';?>>
     </td><td align='left'>

     <table  width='100%' cellpadding='0' cellspacing='0'>
     <tr><td>
     <?php echo LM_TAR_PATH;?>  <br /><input size='50' type=text name=tarpath value='<?php echo $_CONFIG[tarpath]?>'><br />
     <?php echo LM_TAR_PATH_SUB;?>
     </td></tr>
     
     </table>

     </td></tr>

     <tr bgcolor='#dedede'><td>
     <?php echo LM_ACTIVE?> <input type=checkbox value=1 name='sql_mem' <?php if($_CONFIG[sql_mem]==1) echo 'checked';?>>
     </td><td align='left'>
     <?php echo LM_MYSQLDUMP_PATH;?> <br /><input type=text size='50' name='sqldump' value='<?php echo $_CONFIG[sqldump]?>'>
	 
	 </td></tr>
     </table>

     <?php echo LM_CONFIG_MEM_SUB?>

     </td>
    </tr>

    <tr>
     <th colspan='2'>
     <?php echo "License Management"?>
     </th>
    </tr>
    <tr>
     <td>
      <?php echo "License Code - optional*
                  <br />*only for support purposes"?>
     </td>
     <td>
      <textarea cols=40 rows='7' name='license_code' ><?php echo $_CONFIG[license_code]?></textarea>
      <br />Copy/Paste the license code from <a target='_blank' href='http://www.xcloner.com/'>XCloner.com Members area</a>
     </td>
    </tr>


    </table>
    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_MYSQL,"config-mysql-tab");
    ?>
    <table class='adminform'>
    
	
	<tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_MYSQL?>
     </th>
    </tr>

    <tr>
     <td>
      <?php echo LM_CONFIG_MYSQLH?>
     </td>
     <td>
      <input type=text size=50 name='mysql_host' value='<?php echo $_CONFIG[mysql_host]?>'>
      <br /><?php echo LM_CONFIG_MYSQLH_SUB?>
     </td>
    </tr>
    
    <tr>
     <td>
      <?php echo LM_CONFIG_MYSQLU?>
     </td>
     <td>
      <input type=text size=50 name='mysql_user' value='<?php echo $_CONFIG[mysql_user]?>'>
      <br /><?php echo LM_CONFIG_MYSQLU_SUB?>
     </td>
    </tr>
    
    <tr>
     <td>
      <?php echo LM_CONFIG_MYSQLP?>
     </td>
     <td>
      <input type=text size=50 name='mysql_pass' value='<?php echo $_CONFIG[mysql_pass]?>'>
      <br /><?php echo LM_CONFIG_MYSQLP_SUB?>
     </td>
    </tr>
    
    <tr>
     <td>
      <?php echo LM_CONFIG_MYSQLD?>
     </td>
     <td>
      <input type=text size=50 name='mysql_database' value='<?php echo $_CONFIG[mysql_database]?>'>
      <br /><?php echo LM_CONFIG_MYSQLD_SUB?>
     </td>
    </tr>

    <tr>
     <td  width='200'>
      <?php echo LM_CONFIG_SYSTEM_MDATABASES?>
     </td>
     <td>
      <?php echo LM_YES?> <input type=radio name='system_mdatabases' value='0' <?php if(abs($_CONFIG[system_mdatabases])==0) echo "checked";?>>
      <?php echo LM_NO?> <input type=radio name='system_mdatabases' value='1' <?php if(abs($_CONFIG[system_mdatabases])==1) echo "checked";?>>
      <br>

      <br /><?php echo LM_CONFIG_SYSTEM_MDATABASES_SUB?>
     </td>
    </tr>

    </table>
    <?php
	$tabs->endTab();
	$tabs->startTab(LM_TAB_AUTH,"config-mysql-tab");
    ?>
    <table class='adminform'>
    
	
	<tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_AUTH?>
     </th>
    </tr>

    <tr>
     <td>
      <?php echo LM_CONFIG_AUTH_USER?>
     </td>
     <td>
      <input type=text size=30 name='jcuser' value='<?php echo $_CONFIG[jcuser]?>'>
      <br /><?php echo LM_CONFIG_AUTH_USER_SUB?>
     </td>
    </tr>
    
    <tr>
     <td>
      <?php echo LM_CONFIG_AUTH_PASS?>
     </td>
     <td>
      <input type=text size=30 name='jcpass' value=''> <?php if($_CONFIG['jcpass'] == md5('admin')) echo "<font color=red>please change the default password  'admin'</font>"?>
      <br /><?php echo LM_CONFIG_AUTH_PASS_SUB?>
     </td>
    </tr>
    

    </table>
    <?php
	$tabs->endTab();
	$tabs->startTab(LM_TAB_SYSTEM,"config-system-tab");
    ?>
    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_DISPLAY?>
     </th>
    </tr>
    
	<tr>
     <td  width='200'>
      <?php echo LM_CONFIG_SYSTEM_LANG?>
     </td>
     <td>
      <select name='select_lang'>
	  <option value=''><?php echo LM_CONFIG_SYSTEM_LANG_DEFAULT;?></option>
	  <?php
	  foreach($lang_array as $value)
	   if($_CONFIG['select_lang'] == $value)
   	     echo "<option value='$value' selected>$value</option>\n";
	   else
	     echo "<option value='$value'>$value</option>\n";
	  ?>
	  </select>
	  <br>
      <br /><?php echo LM_CONFIG_SYSTEM_LANG_SUB?>
     </td>
    </tr>
	
	
    <!--<tr>
     <td  width='200'>
      <?php echo LM_CONFIG_SYSTEM_DOWNLOAD?>
     </td>
     <td>
      <?php echo LM_YES?> <input type=radio name='system_dlink' value='1' <?php if(abs($_CONFIG[system_dlink])==1) echo "checked";?>>
      <?php echo LM_NO?> <input type=radio name='system_dlink' value='0' <?php if(abs($_CONFIG[system_dlink])==0) echo "checked";?>>
      <br>

      <br /><?php echo LM_CONFIG_SYSTEM_DOWNLOAD_SUB?>
     </td>
    </tr>-->
    
    
    <tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_SYSTEM?>
     </th>
    </tr>

    <tr>
     <td  width='200'>
      <?php echo LM_CONFIG_SYSTEM_FTP?>
     </td>
     <td>
      Direct <input type=radio name='system_ftptransfer' value='0' <?php if(abs($_CONFIG[system_ftptransfer])==0) echo "checked";?>>
      Passive <input type=radio name='system_ftptransfer' value='1' <?php if(abs($_CONFIG[system_ftptransfer])==1) echo "checked";?>> <br>

      <br /><?php echo LM_CONFIG_SYSTEM_FTP_SUB?>
     </td>
    </tr>
     <tr>
     <td>
      <?php echo LM_FTP_TRANSFER_MORE?>
     </td>
     <td>
      Normal <input type=radio size=50 value=0 name='secure_ftp' <?php if($_CONFIG[secure_ftp]==0) echo 'checked';?>>
      Secure <input type=radio size=50 value=1 name='secure_ftp' <?php if($_CONFIG[secure_ftp]==1) echo 'checked';?>>
     </td>
    </tr>
    
     <th colspan='2'>
     <?php echo LM_CONFIG_MANUAL?>
     </th>
    </tr>
    
     <tr>
     <td>
      <?php echo LM_CONFIG_MANUAL_FILES;?>
     </td>
     <td>
      <input type=text size=20 name='backup_refresh_number' value=<?php echo $_CONFIG[backup_refresh_number];?>>
      
     </td>
    </tr>
    
    <tr>
     <td>
      <?php echo LM_CONFIG_MANUAL_REFRESH;?>
     </td>
     <td>
      <input type=text size=20 name='refresh_time' value=<?php echo $_CONFIG[refresh_time];?>> seconds
      
     </td>
    </tr>
    
    </table>
    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_CRON,"config-cron-tab");
    ?>
    <table class='adminform'>
    <tr>
     <th  colspan='2'>
     <?php echo LM_CRON_SETTINGS_M?> - all configs are saved in configs/
     </th>
    </tr>
    
    <tr>
    <td>
      <?php echo LM_CRON_MCRON?>
     </td>
     <td>
      <input type=text size=30 value="<?php echo $_CONFIG[cron_save_as]?>" name='cron_save_as' >.php <br />
       <?php echo LM_CRON_MCRON_SUB?>
     </td>
    </tr>
    
    <tr>
    <td>
      <?php echo LM_CRON_MCRON_AVAIL?>
     </td>
     <td>
      <?php
      
      if ($handle = @opendir($_CONFIG['multiple_config_dir'])) {

      while (false !== ($file = readdir($handle))) {
         if( ($file!=".") && ($file!="..") &&($file!="") && (strstr($file, '.php'))){
           $fcron = "cloner.cron.php?config=$file";
           
           echo "<b>$fcron</b>";
         
           echo " - <a href='$fcron' target='_blank'>execute cron</a>";
           
           echo " | <a href='index2.php?option=com_cloner&task=cron_delete&fconfig=$file'>delete config</a>"; 
           
           echo "\n<br />";
         }
      }

      closedir($handle);
      }
      ?>
     </td>
    </tr>
    
    <tr>
     <th  colspan='2'>
     <?php echo LM_CRON_SETTINGS?>
     </th>
    </tr>
    
    <tr>
    <td>
      <?php echo LM_CRON_SEMAIL?>
     </td>
     <td>
      <input type=text size=30 value="<?php echo $_CONFIG[cron_logemail]?>" name='cron_logemail' > <br />
       <?php echo LM_CRON_SEMAIL_SUB?>
     </td>
    </tr>
    
    <tr>
     <td width='200'>
     <?php echo LM_CRON_MODE?>
     </td>
     <td>
      <input type=radio size=50 value=0 name='cron_send' <?php if($_CONFIG[cron_send]==0) echo 'checked';?>>
	  <?php echo LM_CONFIG_CRON_LOCAL?><br />
      <input type=radio size=50 value=1 name='cron_send' <?php if($_CONFIG[cron_send]==1) echo 'checked';?>>
	  <?php echo LM_CONFIG_CRON_REMOTE?><br />
      <input type=radio size=50 value=2 name='cron_send' <?php if($_CONFIG[cron_send]==2) echo 'checked';?>>
	  <?php echo LM_CONFIG_CRON_EMAIL?> <br />
     <?php echo LM_CRON_MODE_INFO?>
     </td>
    </tr>


   <tr>
    <td>
      <?php echo LM_CRON_TYPE?>
     </td>
     <td>
      <input type=radio size=50 value=0 name='cron_btype' <?php if($_CONFIG[cron_btype]==0) echo 'checked';?>>
	  <?php echo LM_CONFIG_CRON_FULL?> <br />
       <input type=radio size=50 value=1 name='cron_btype' <?php if($_CONFIG[cron_btype]==1) echo 'checked';?>>
	   <?php echo LM_CONFIG_CRON_FILES?><br />
       <input type=radio size=50 value=2 name='cron_btype' <?php if($_CONFIG[cron_btype]==2) echo 'checked';?>>
	   <?php echo LM_CONFIG_CRON_DATABASE?> <br />
       <?php echo LM_CRON_TYPE_INFO?>
     </td>
    </tr>
    
     <tr>
    <td>
      <?php echo LM_CRON_BNAME?>
     </td>
     <td>
      <input type=text size=50 value="<?php echo $_CONFIG[cron_bname]?>" name='cron_bname' > <br />
       <?php echo LM_CRON_BNAME_SUB?>
     </td>
    </tr>
    
    
     <tr>
    <td>
      <?php echo LM_CRON_IP?>
     </td>
     <td>
      <textarea type=text size=50 name='cron_ip' cols='30' rows='5'><?php echo $_CONFIG[cron_ip]?></textarea> <br />
       <?php echo LM_CRON_IP_SUB?>
     </td>
    </tr>
    
    </table>
    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CRON_FTP_DETAILS?>
     </th>
    </tr>
    </tr>
    <tr>
     <td width='200'>
      <?php echo LM_CRON_FTP_SERVER?>
     </td>
     <td>
      <input type=text size=50 name='cron_ftp_server' value='<?php echo $_CONFIG[cron_ftp_server]?>'>
     </td>
    </tr>
    <tr>
     <td>
      <?php echo LM_CRON_FTP_USER?>
     </td>
     <td>
      <input type=text size=50 name='cron_ftp_user' value='<?php echo $_CONFIG[cron_ftp_user]?>'>
     </td>
    </tr>
    <tr>
     <td>
      <?php echo LM_CRON_FTP_PASS?>
     </td>
     <td>
      <input type=text size=50 name='cron_ftp_pass' value='<?php echo $_CONFIG[cron_ftp_pass]?>'>
     </td>
    </tr>
    <tr>
     <td>
      <?php echo LM_CRON_FTP_PATH?>
     </td>
     <td>
      <input type=text size=50 name='cron_ftp_path' value='<?php echo $_CONFIG[cron_ftp_path]?>'>
     </td>
    </tr>
     <tr>
     <td>
      <?php echo LM_CRON_FTP_DELB?>
     </td>
     <td>
      <input type=checkbox name='cron_ftp_delb' <?php if($_CONFIG[cron_ftp_delb]==1) echo "checked";?> value='1'>
     </td>
    </tr>
    </table>

<table class='adminform'>
	
	<tr><th colspan=2>
		<?php echo LM_AMAZON_S3?>
	</th></tr>	
	<tr>
	<td width='200'>
     		<?php echo LM_AMAZON_S3_ACTIVATE?>
	</td>
     	<td>
		<input type=checkbox name='cron_amazon_active' <?php if($_CONFIG[cron_amazon_active]==1) echo "checked";?> value='1'>
	</td>
	</tr>	

	<tr>
	<td width='200'>
     		<?php echo LM_AMAZON_S3_AWSACCESSKEY;?>
	</td>
     	<td>
		<input type=text size=50  name='cron_amazon_awsAccessKey' value="<?php echo $_CONFIG['cron_amazon_awsAccessKey'];?>">
	</td>
	</tr>

	<tr>
	<td width='200'>
     		<?php echo LM_AMAZON_S3_AWSSECRETKEY;?>
	</td>
     	<td>
		<input type=text size=50  name='cron_amazon_awsSecretKey' value="<?php echo $_CONFIG['cron_amazon_awsSecretKey'];?>">
	</td>
	</tr>

	<tr>
	<td width='200'>
     		<?php echo LM_AMAZON_S3_BUCKET;?>
	</td>
     	<td>
		<input type=text size=50  name='cron_amazon_bucket' value="<?php echo $_CONFIG['cron_amazon_bucket'];?>">
	</td>
	</tr>

	<tr>
	<td width='200'>
     		<?php echo LM_AMAZON_S3_DIRNAME;?>
	</td>
     	<td>
		<input type=text size=50  name='cron_amazon_dirname' value="<?php echo $_CONFIG['cron_amazon_dirname'];?>">
	</td>
	</tr>

     <td>
      
     </td>
    </tr>

</table>	


    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CRON_EMAIL_DETAILS?>
     </th>
    </tr>
    </tr>
    <tr>
     <td width='200'>
      <?php echo LM_CRON_EMAIL_ACCOUNT?>
     </td>
     <td>
      <input type=text size=50 name='cron_email_address' value='<?php echo $_CONFIG[cron_email_address]?>'>
     </td>
    </tr>


    </table>
    
    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CRON_MYSQL_DETAILS?>
     </th>
    </tr>
    </tr>
    <tr bgcolor='#ffffff'>
     <td width='200'>
      <?php echo LM_CRON_MYSQL_DROP?>
     </td>
     <td>
      <input type=checkbox  name='cron_sql_drop' value='1' <?php if($_CONFIG[cron_sql_drop]) echo "checked";?> >
     </td>
    </tr>
 
    <?php 
    if((abs($_CONFIG[system_mdatabases])==0) && ($_CONFIG[enable_db_backup]==1)){
    ?>  
    <tr><td valign='top'>
    <?php echo LM_DATABASE_INCLUDE_DATABASES?>
    </td><td>
    <select name='databases_incl[]' MULTIPLE SIZE=5>
    <?php

    $curent_dbs = explode(",", $_CONFIG['databases_incl_list']);

    $query = @mysql_query("SHOW databases");
    while($row = @mysql_fetch_array($query)){
  
	   $table = $row[0];
        
       if($table != $_CONFIG['mysql_database'])
	   
	   if(in_array($table, $curent_dbs)){
       
	     	echo "<option value='".$table."' selected>$table</option>";
       
	   }else{
         	
		    echo "<option value='".$table."'>$table</option>";
        
		}
    }
	?>
    </select><br />
    <?php echo LM_DATABASE_INCLUDE_DATABASES_SUB?>
    </td></tr>
    <?php
    }
    ?>
    
    <tr><th colspan=2>
    <?php echo LM_CRON_DELETE_FILES?>
    </th></tr>
	<tr>
	<td width='200'>
      <?php echo LM_CRON_DELETE_FILES_SUB_ACTIVE?>
     </td>
     <td>
      <input type=checkbox name='cron_file_delete_act' <?php if ($_CONFIG['cron_file_delete_act'] == 1) echo 'checked';?> value='1'>
     </td>
    </tr>
    <tr>
	<td width='200'>
      <?php echo LM_CRON_DELETE_FILES_SUB?>
     </td>
     <td>
      <input size=5 name='cron_file_delete' value='<?php echo $_CONFIG[cron_file_delete]?>'> days:
     </td>
    </tr>
    </table>
    
    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CRON_EXCLUDE?>
     </th>
    </tr>
    </tr>
    <tr>
     <td width='200'>
      <?php echo LM_CRON_EXCLUDE_DIR?>
     </td>
     <td>
      <textarea cols=50 rows=5 name='cron_exclude'><?php echo $_CONFIG[cron_exclude]?></textarea>
     </td>
    </tr>

    </table>
    <?php
    $tabs->endTab();
    $tabs->startTab(LM_TAB_INFO,"config-info-tab");
    ?>
    <table class='adminform'>
    <tr>
     <th colspan='2'>
     <?php echo LM_CONFIG_INFO_PHP?>
     </th>
    </tr>
    
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_SAFEMODE?>
     </td>
     <td>
        <b><?php $val = (ini_get('safe_mode') != "")? ini_get('safe_mode'):"Off";
        echo HTML_cloner::get_color($val, 'On');
        ?></b> 
        <br />
        <?php echo LM_CONFIG_INFO_SAFEMODE?>
   </td>
    </tr>
     
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_MTIME?>
     </td>
     <td>
        <b><?php echo (ini_get('max_execution_time') != "")? ini_get('max_execution_time'):"no value";
        
        ?></b> 
        <br />
        <?php echo LM_CONFIG_INFO_TIME?>
   </td>
    </tr>
    
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_MEML?>
     </td>
     <td>
        <b><?php echo (ini_get('memory_limit') != "")? ini_get('memory_limit'):"no value";?> </b>
        <br />
         <?php echo LM_CONFIG_INFO_MEMORY?>
     </td>
    </tr>
       
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_BDIR?>
     </td>
     <td>
        <b><?php $val = (ini_get('open_basedir') != "")? ini_get('open_basedir'):"no value";
        echo HTML_cloner::get_color($val, '/');
        ?> </b>
        <br />
         <?php echo LM_CONFIG_INFO_BASEDIR?>
     </td>
    </tr>
    
     <tr>
     <td width='200'>
    <?php echo LM_CONFIG_INFO_T_EXEC?>
     </td>
     <td>
        <b><?php 

	$out = "";
	if(function_exists("exec")){

	        $out = @exec("ls -al");
	}

        $val = ($out != "")? "ENABLED":"<font color='red'>DISABLED</font>";
        echo HTML_cloner::get_color($val, 'DISABLED');
        ?> </b>
        <br />
         <?php echo LM_CONFIG_INFO_EXEC?>
     </td>
    </tr>
    
    <tr>
     <th colspan='2'>
      <?php echo LM_CONFIG_INFO_PATHS?>
     </td>
     <td>
    </tr>
 
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_ROOT_BPATH_TMP?>
     </td>
     <td>
        <b><?php $tmp_dir = realpath($_CONFIG['backup_path']."/administrator/backups");  
		echo (@is_writeable( $tmp_dir ))? $tmp_dir . " is <font color=green>writeable</font>":$tmp_dir. " <font color=red>incorrect or unreadable</font>";?></b>
        <br />
        <?php echo LM_CONFIG_INFO_ROOT_PATH_TMP_SUB?>
   </td>
    </tr>
	
	 <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_ROOT_BPATH?>
     </td>
     <td>
        <b><?php echo (@is_readable($_CONFIG['backup_path']) )? $_CONFIG['backup_path'] . " is <font color=green>readable</font>":$_CONFIG['backup_path']. " <font color=red>incorrect or unreadable</font>";?></b>
        <br />
        <?php echo LM_CONFIG_INFO_ROOT_PATH_SUB?>
   </td>
    </tr>
    
	 
	 <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_BPATH?>
     </td>
     <td>
        <b><?php echo (@is_writeable($_CONFIG['clonerPath']) )? $_CONFIG['clonerPath'] . " is <font color=green>writeable</font>":$_CONFIG['clonerPath']. " <font color=red>unwriteable</font>";?></b> 
        <br />
        <?php echo LM_CONFIG_INFO_BPATH?>
   </td>
    </tr>
    
   
    <tr>
     <td width='200'>
        <?php echo LM_CONFIG_INFO_T_TAR?>
     </td>
     <td>
        <b><?php 
	if(function_exists('exec')){
	        $info_tar_path = explode(" ", @exec("whereis tar"));
	}
        echo ($info_tar_path['1'] != "")? $info_tar_path['1']:"unable to determine";
        ?> </b>
        <br />
         <?php echo LM_CONFIG_INFO_TAR?>
     </td>
    </tr>
    
    
    <tr>
     <td width='200'>
      <?php echo LM_CONFIG_INFO_T_MSQL?>
     </td>
     <td>
        <b><?php 
	if(function_exists('exec')){
	        $info_msql_path = explode(" ", @exec("whereis mysqldump"));
	}
        echo ($info_msql_path['1'] != "")? $info_msql_path['1']:"unable to determine";
        ?> </b>
        <br />
         <?php echo LM_CONFIG_INFO_MSQL?>
     </td>
    </tr>
       
       
    </table>
    <?php
    $tabs->endTab();
    $tabs->endPane();
    ?>
     <input type="hidden" name="option" value="com_cloner" />
     <input type="hidden" name="task" value="config" />
     <input type="hidden" name='action' value='save'>
     </form>

  <?php
  }
  
  function get_color($val, $comp){
  
   if(!stristr($val, $comp))
    echo "<span style='color:green'>$val</span>";
   else
    echo "<span style='color:red'>$val</span>"; 
  
  }
  
  function TransferForm($option, $files){
      global $baDownloadPath, $mosConfig_absolute_path, $clonerPath, $task;
      
     ?>
    <form action="index2.php" method="GET" name="adminForm">
    <script language="javascript" type="text/javascript">


		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
        
                submitform( pressbutton );

		}

		function gotocontact( id ) {
			var form = document.adminForm;
			form.contact_id.value = id;
			submitform( 'contact' );
		}
		</script>
    <table class='adminform'>
    <tr><td colspan='2'>
    <b>Transfer <?php echo $file;?> details:</b>
    <br /><b>Attempting to
    <?php echo (($_REQUEST[task]=='move')||($_REQUEST[task2]=='move'))?'Move':'Clone';?> backup(s):</b><br /><?php echo implode("<br />",$files)?>
    
    </td></tr>
    <tr><td colspan='2'><?php echo LM_CLONE_FORM_TOP?></td></tr>
    <?php
    if(($_REQUEST[task]=='move')||($_REQUEST[task2]=='move')){
    }
    else{

    ?>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_URL?></b> </td>
     <td><input type='text' size='30' name='ftp_url' value='<?php echo $_REQUEST[ftp_url]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><?php echo LM_TRANSFER_URL_SUB?></td>
    </tr>
    <?php } ?>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_HOST?></b> </td>
     <td><input type='text' size='30' name='ftp_server'  value='<?php echo $_REQUEST[ftp_server]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_HOST_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_USER?></b> </td>
     <td><input type='text' size='30' name='ftp_user'  value='<?php echo $_REQUEST[ftp_user]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_USER_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_PASS?></b> </td>
     <td><input type='text' size='30' name='ftp_pass'  value='<?php echo $_REQUEST[ftp_pass]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_PASS_SUB?></small></td></tr>
    <tr>
     <td width='110'><b><?php echo LM_TRANSFER_FTP_DIR?></b> </td>
     <td><input type='text' size='30' name='ftp_dir'  value='<?php echo $_REQUEST[ftp_dir]?>'></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_DIR_SUB?></small></td></tr>

    <tr>
     <td width='140'><b><?php echo LM_TRANSFER_FTP_INCT?></b> </td>
     <td><input type='checkbox' name='ftp_inct'  value='1' <?php if($_REQUEST[ftp_inct] ==1 ) echo "checked";?>></td>
    </tr>
    <tr>
     <td colspan='2'><small><?php echo LM_TRANSFER_FTP_INCT_SUB?></small></td></tr>

    </table>
     <input type="hidden" name="option" value="com_cloner" />
     <input type="hidden" name="task" value="" />
     <input type="hidden" name="task2" value="<?php  if($_REQUEST[task2]!="") echo $_REQUEST[task2]; else echo $task;?>" />
     <?php
     foreach($files as $key=>$value)
     {
     ?>
     <input type="hidden" name="files[<?php echo $key;?>]" value="<?php echo $value?>" />
     <input type="hidden" name="cid[<?php echo $key;?>]" value="<?php echo $value?>" />
     <?php
     }
     ?>
     <input type="hidden" name="action" value="connect" />
     <input type="hidden" name="hidemainmenu" value="0" />
     </form>
     <?php
      }
  function confirmBackups( &$folders, &$sizes, $path, $option ) {
    // ----------------------------------------------------------
    // Presentation of the confirmation screen
    // ----------------------------------------------------------
    global $baDownloadPath, $mosConfig_absolute_path, $clonerPath, $_CONFIG, $database, $mosConfig_db;

    ?>
	<form action="index2.php" method="post" name="adminForm">
	<?php
	$tabs = new mosTabs(1);
    #$tabs->startPane("BGeneratePane");
    if($_CONFIG['enable_db_backup']){
	$tabs->startTab(LM_TAB_G_DATABASE,"users-databse-options-tab");
    ?>
    

    <table class="adminform">
    <tr>
     <th colspan=2>
       <b><?php echo LM_DATABASE_ARCHIVE; ?></b>
     </th>
    </tr>
    <tr>
        <td><input type="checkbox" id="dbbackup" name="dbbackup" checked value="1" />&nbsp;<?php echo LM_CONFIRM_DATABASE; ?></td>
    </tr>
    <tr>
        <td><input type="checkbox" id="dbbackup_drop" name="dbbackup_drop"  value="1" />&nbsp;<?php echo "Add DROP SYNTAX"; ?></td>
    </tr>
    <tr>
        <td><?php echo "Mysql Compatibility"; ?> &nbsp; 
           <select name='dbbackup_comp'>
           <option value=''>Default</option>
           <option value='MYSQL40'>MYSQL40</option>
           <option balue='MYSQL323'>MYSQL323</option>
           </select>
           </td>
    </tr>
    <tr><th colspan=2>
    <?php echo LM_DATABASE_EXCLUDE_TABLES?>
    </th></tr>
    <tr><td>
    <?php echo LM_DATABASE_CURRENT?> <b><?php echo $mosConfig_db;?></b><br />
	<select name='excltables[]' MULTIPLE SIZE=15>
    <?php

    $query = mysql_query("SHOW tables");
    while($row = mysql_fetch_array($query)){
         
		 echo "<option value='".$row[0]."'>$row[0]</option>";
        
		}
    ?>
    </select>
    </td></tr>
    
    <?php 
    if(abs($_CONFIG[system_mdatabases])==0){
    ?> 
    
	<tr><th colspan=2>
    <?php echo LM_DATABASE_INCLUDE_DATABASES?>
    </th></tr>
    <tr><td>
    <select name='databases_incl[]' MULTIPLE SIZE=5>
    <?php

    $query = mysql_query("SHOW databases");
    
	while($row = mysql_fetch_array($query)){
    
	     echo "<option value='".$row[0]."'>$row[0]</option>";
    
	    }
    
	?>
    </select><br />
    <?php echo LM_DATABASE_INCLUDE_DATABASES_SUB?>
    </td></tr>
	
	<?php
    }
	?>
	
	</table>
    <?php
    $tabs->endTab();
    }
    $tabs->startTab(LM_TAB_G_FILES,"users-files-options-tab");
    ?>
    <table class="adminform">
    <tr>
     <th>
       <b><?php echo LM_BACKUP_NAME; ?></b>
     </th>
    </tr>
    <tr>
     <td>
       <input type=text name='bname' value='' size=40><br/>
       <?php echo LM_BACKUP_NAME_SUB?>
     </td>
    </tr>

    <tr>
        <td width="50%"><?php echo LM_CONFIRM_INSTRUCTIONS ?></td>
    </tr>
    </table>
    <table class="adminlist" >
    <tr>
      <th width="200" valign='top' colspan='2' align='left'>

      <?php echo LM_COL_FOLDER ?>
      <?php
	  {
      ?>
    <tr><td>
    <link href="browser/filebrowser.css" rel="stylesheet" type="text/css">

	<script type="text/javascript" src="browser/xmlhttp.js"></script>
	

    <div id="browser">
    <?php require_once("browser/files_inpage.php"); ?>
    </div>
    <script>do_browser()</script>
	
    </td></tr>
    <?php
    }
    ?>
    
    </table>
    <?php
    $tabs->endTab();
    $tabs->endPane();
    ?>
    <input type="hidden" name="option" value="com_cloner" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="hidemainmenu" value="0" />
    </form>
    <br/>&nbsp;
    <?php
  }


  function generateBackup( $archiveName, $archiveSize, $originalSize, $d, $f, $databaseResult, $option ) {
    // ----------------------------------------------------------
    // Presentation of the final report screen
    // ----------------------------------------------------------


    ?>
    <table cellpadding="4" cellspacing="0" border="0" width="100%">

    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    </tr>
    <tr>
      <td width="20%"><strong>&nbsp;</strong></td><td>&nbsp;</td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_ARCHIVE_NAME; ?></strong></td><td><?php echo $archiveName; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_NUMBER_FILES; ?></strong></td><td><?php echo $f; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_SIZE_ORIGINAL; ?></strong></td><td><?php echo $originalSize; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_SIZE_ARCHIVE; ?></strong></td><td><?php echo $archiveSize; ?></td>
    </tr>
    <tr>
      <td><strong>&nbsp;<?php echo LM_DATABASE_ARCHIVE; ?></strong></td><td><?php echo $databaseResult; ?></td>
    </tr>


    <tr>
      <td><strong>&nbsp;</strong></td><td>&nbsp;</td>
    </tr>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type=hidden name=files[1] value='<?php echo $archiveName?>'>
    <input type=hidden name=cid[1] value='<?php echo $archiveName?>'>
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }
 
  function generateBackup_text( $archiveName, $archiveSize, $originalSize, $d, $f, $databaseResult, $option ) {
    // ----------------------------------------------------------
    // Presentation of the final report screen in text mode
    // ----------------------------------------------------------

    ob_start();
    ?>
    <?php echo LM_ARCHIVE_NAME; ?>: <?php echo $archiveName."\r\n"; ?><br />
    <?php echo LM_NUMBER_FILES; ?>: <?php echo $f."\r\n"; ?><br />
    <?php echo LM_SIZE_ORIGINAL; ?>: <?php echo $originalSize."\r\n"; ?><br />
    <?php echo LM_SIZE_ARCHIVE; ?>: <?php echo $archiveSize."\r\n"; ?><br />
    <?php echo LM_DATABASE_ARCHIVE; ?>: <?php echo $databaseResult."\r\n"; ?><br />
    ### END REPORT
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    
    return $content;
  }

  function showHelp( $option ) {
    ?>

    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    <tr><th>
    <?php echo LM_CREDIT_TOP?>
    </th></tr>
    <tr>
      <td>
        <?php echo LM_CLONER_ABOUT?>
      </td>
    </tr>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }

  function Restore( $option ) {
    // ----------------------------------------------------------
    // Presentation of the Help Screem
    // ----------------------------------------------------------

    ?>


    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    <tr><th>
    <?php echo LM_RESTORE_TOP?>
    </th></tr>
    <tr>
      <td>
        <?php echo LM_CLONER_RESTORE?>
      </td>
    </tr>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }
  function showCredits( $option ) {
    // ----------------------------------------------------------
    // Presentation of the Help Screem
    // ----------------------------------------------------------

    ?>

    <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    <tr><th>
    <?php echo LM_CREDIT_TOP?>
    </th></tr>
    <tr>
      <td>

      <?echo LM_CLONER_CREDITS?>
      </td>
    </tr>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value=""/>
    </form>
    <?php
  }
  
  
  function Rename($files, $option){
       global $_CONFIG;

    ?>
    <form action="index2.php" method="post" name="adminForm">
   <table border="0" align="center" cellspacing="0" cellpadding="2" width="100%" class="adminform">
    <tr><th colspan='2'>
    <?php echo LM_RENAME_TOP?>
    </th></tr>
    <?php
    
    foreach($files as $key=>$file){
        echo "<tr>
      <td >
        ".LM_RENAME." <input type=hidden name='cfile[]' value='$file' ><b>$file</b>
       </td>
      <td align='left'>
        ".LM_RENAME_TO." <input type=text name='dfile[]' value='$file' size=100>
       </td>
    </tr>";
        }

    ?>
    </table>
    <form action="index2.php" name="adminForm" method="post">
    <input type="hidden" name="option" value="<?php echo $option; ?>"/>
    <input type="hidden" name="task" value="rename_save"/>
    </form>

    <?php
      }

}
?>
