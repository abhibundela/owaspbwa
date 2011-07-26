<?php
/*
 * $RCSfile$
 *
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2006 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */

if (!defined('G2_SUPPORT')) return;

/* Commands */
define('CMD_CHMOD_MODULES_AND_THEMES_DIR', 'chmodModulesAndThemesDir');
define('CMD_ADVANCED', 'advanced');
define('CMD_CHMOD_PLUGIN_DIR', 'chmodPluginDir');
define('CMD_CHMOD_GALLERY_DIR', 'chmodGalleryDir');
define('CMD_CHMOD_STORAGE_DIR', 'chmodStorageDir');
/* For get/post input sanitation */
require_once(dirname(__FILE__) . '/../../modules/core/classes/GalleryUtilities.class');

$DEFAULT_FOLDER_PERMISSIONS = PermissionBits::fromString('555');
$DEFAULT_FILE_PERMISSIONS = PermissionBits::fromString('444');

$status = array();
$ret = null;

/* The permission bit sets that we accept / handle. */
$permissionBitSets = getPermissionSets();
/* Gather a complete list of plugins in this installation. */
$plugins = getPluginList();

/* Process inputs and set some variables to default values */

$path = getRequestVariable('path');
if (empty($path)) {
    $path = getGalleryStoragePath();
}
/* Some basic sanitation */
$path = str_replace('..', '', $path);
/* 
 * $path is used in a chmod() call and we output the path in the HTML. 
 * Just do some very basic sanitation.
 */

GalleryUtilities::sanitizeInputValues($path);
if (!file_exists($path)) {
    /* TODO: add open_basedir check */
    $status['error'][] = "Folder or file '$path' does not exist!";
}

/* Permissions (format e.g. 755644, split after 3 characters to get 755 and 644)*/
$permissions = (string)getRequestVariable('permissions');
if (empty($permissions)) {
    $permissions = $DEFAULT_FOLDER_PERMISSIONS->getAsString() . 
    		   $DEFAULT_FILE_PERMISSIONS->getAsString();
}
if (strlen($permissions) != 6) {
    $status['error'][] = 
	"Unknown permissions '$permissions'! Aborting action and resetting permissions.";
}
if (empty($status['error'])) {
    $folderPermissions = PermissionBits::fromString(substr($permissions, 0, 3));
    $filePermissions = PermissionBits::fromString(substr($permissions, 3, 3));
    if (!$folderPermissions->isValid()) {
	$status['error'][] = 'Invalid folder permissions! Aborting action and resetting permissions.';
	$folderPermissions = $DEFAULT_FOLDER_PERMISSIONS;
    }
    if (!$filePermissions->isValid()) {
	$status['error'][] = 'Invalid file permissions! Aborting action and resetting permissions.';
	$filePermissions = $DEFAULT_FILE_PERMISSIONS;
    }
} else {
    $folderPermissions = $DEFAULT_FOLDER_PERMISSIONS;
    $filePermissions = $DEFAULT_FILE_PERMISSIONS;
}

/************************************************************
 * Main program section
 ************************************************************/

printPageWithoutFooter($plugins, $path, $filePermissions, $folderPermissions, $permissionBitSets);

if (empty($status['error'])) {
    $command = trim(getRequestVariable('command'));
    switch ($command) {
    case CMD_ADVANCED:
        /* Advanced Options, allow chmod of any folder / file */
        $ret = chmodRecursively($path, $folderPermissions->getAsInt(), 
		     $filePermissions->getAsInt(), time() - 60);
	if (!empty($ret)) {
            $status['error'][] = "Failed to change the filesystem permissions " .
			       "of '$path'.";
        } else {
	    $status['message'] = "Successfully changed the filesystem permissions " .
				  "of '$path'.";
        }
        break;
    case CMD_CHMOD_MODULES_AND_THEMES_DIR:
        /* Chmod the modules/ and themes/ dir writeable or read-only (not recursively) */
        $mode = getRequestVariable('mode');
        if (!in_array($mode, array('open', 'secure'))) {
            $status['error'][] = "Unknown mode '$mode'. Please try again.";
        } else {
            $ret = chmodModulesAndThemesDir($mode == 'open');
            if (!empty($ret)) {
                $status['error'][] = 'Failed to change the filesystem permissions ' .
                		     'of the modules/ and themes/ folder.';
            } else {
            	$status['message'] = 'Successfully changed the filesystem permissions ' .
            			     'of the modules/ and the themes/ folder.';
            }
        }
        break;
    case CMD_CHMOD_PLUGIN_DIR:
        /* Chmod a _specific_ plugin (theme or module) writeable or read-only (recursively) */
        $mode = getRequestVariable('mode');
        /* Check the given plugin path against a white list */
        $pluginPath = getRequestVariable('pluginId');
        if (!isset($plugins[$pluginPath])) {
            $status['error'][] = "Unknown plugin path '$pluginPath'.";
        } else if (!in_array($mode, array('open', 'secure'))) {
            $status['error'][] = "Unknown mode '$mode'. Please try again.";
        } else {
            $ret = chmodPluginDir($pluginPath, $mode == 'open');
            if (!empty($ret)) {
                $status['error'][] = "Failed to change the filesystem permissions " .
                		     "of the '$pluginPath' folder.";
            } else {
            	$status['message'] = "Successfully changed the filesystem permissions " .
                		     "of the '$pluginPath' folder.";
            }
        }
        
        break;
    case CMD_CHMOD_GALLERY_DIR:
        /* Chmod the whole gallery2 dir writeable or read-only */
        $mode = getRequestVariable('mode');
        if (!in_array($mode, array('open', 'secure'))) {
            $status['error'][] = "Unknown mode '$mode'. Please try again.";
        } else {
            $ret = chmodGalleryDirRecursively($mode == 'open');
            if (!empty($ret)) {
                $status['error'][] = 'Failed to change the filesystem permissions ' .
                		     'of the Gallery folder.';
            } else {
            	$status['message'] = 'Successfully changed the filesystem permissions ' .
            			     'of the Gallery folder.';
            }
        }
        break;
    case CMD_CHMOD_STORAGE_DIR:
        /* Chmod the entire storage dir writeable */
	$ret = chmodStorageDirRecursively();
	if (!empty($ret)) {
            $status['error'][] = 'Failed to change the filesystem permissions ' .
                		 'of the storage folder.';
        } else {
            $status['message'] = 'Successfully changed the filesystem permissions ' .
            			   'of the storage folder.';
        }
        break;
    default:
       /* Just redisplay the page. */
       break;
    }
}
printStatus($status);

printFooter();

/************************************************************
 * Functions and Classes
 ************************************************************/

/**
 * Changes the filesystem permissions of a file or a folder recursively
 * Also prints out folder names online on success / error and prints out 
 * filenames on error as well.
 * 
 * @param string absolute path to folder/file that should be chmod'ed
 * @param int (octal) new permissions for folders
 * @param int (octal) new permissions for files
 * @param int unix timestamp of last webserver/php timeout counter-measure  
 * @return null on success, int <> 0 on error
 */
function chmodRecursively($filename, $folderPermissions, $filePermissions, $start) {
    $error = 0;
    /* Try to prevent timeouts */
    if (time() - $start > 55) {
	if (function_exists('apache_reset_timeout')) {
    	    @apache_reset_timeout();
    	}
    	@set_time_limit(600);
	$start = time();
    }
    /*
     * Have to chmod first before the is_dir check because is_dir does a stat on the 
     * file / dir which fails if the permissions are too tight.
     * Chmod to filepermissions since the majority of the chmod() calls will be for 
     * files anyway and then change the permissions for folders with a second call.
     */
    if (!@chmod($filename, $filePermissions)) {
    	error("Can't fix", $filename);
    	$error = 1;
    }
    if (is_dir($filename)) {
    	/* For folders, we change the permissions to the right ones with a second chmod call. */
    	if (!$error && !@chmod($filename, $folderPermissions)) {
    	    error("Can't fix folder", $filename);
    	    $error = 1;
	} else {
	    status("[OK] Directory", $filename);	
    	}
    	/* 
    	 * Recurse into subdirectories: Open all files / sub-dirs and change the 
    	 * permissions recursively.
    	 */
	if ($fd = opendir($filename)) {
	    while (($child = readdir($fd)) !== false) {
		if ($child == '.' || $child == '..') {
		    continue;
		}
		$fullpath = "$filename/$child";
		$ret = chmodRecursively($fullpath, $folderPermissions, 
					$filePermissions, $start);
		$error |= $ret;
	    }
	    closedir($fd);
	} else {
	    error("Cannot open directory", $filename);
	    return 1;
	}
    }
    
    if ($error) {
	return 1;
    }

    return null;
}

/**
 * Returns the predefined / acceptable permission bit sets for folders and files
 * as strings. Use as-is for HTML output, convert to integer (octdec) for chmod().
 * 
 * @return array(array(string folder permission) )
 */
function getPermissionSets() {
    $permissionSets = array();
    
    $permissionSets[] = array(PermissionBits::fromString("777"),
    			      PermissionBits::fromString("666"));
    $permissionSets[] = array(PermissionBits::fromString("555"),
    			      PermissionBits::fromString("444"));
    $permissionSets[] = array(PermissionBits::fromString("755"),
    			      PermissionBits::fromString("644"));
    return $permissionSets;
}

function getGalleryBasePath() {
    /* For multisites, return the multisite home and not the codebase */
    return GALLERY_CONFIG_DIR;
}

function getGalleryStoragePath() {
    global $gallery;
    return $gallery->getConfig('data.gallery.base');	
}

/**
 * Class to represent a set of filesystem permission bits, e.g. 0755 with a few convenience methods.
 * We need to 
 */
class PermissionBits {
    /**
     * Bits in octal integer representation, e.g. 0755
     */
    var $_bits;	
    
    /**
     * Constructor
     * @param integer permission bits in decimal integer representation
     *        e.g. octdec(0755)
     */
    function PermissionBits($bits) {
    	$this->_bits = decoct($bits);
    }
    
    /**
     * Returns a new PermissionBits object
     * @param string permission set in string representation, e.g. "755"
     * @return PermissionBits object
     * @static
     */
    function fromString($bitsAsString) {
    	$bitsAsString = (string)$bitsAsString;
    	if (strlen($bitsAsString) && $bitsAsString{0} != '0') {
    	    $bitsAsString = '0' . $bitsAsString;
    	}
    	return new PermissionBits(octdec($bitsAsString));
    }
    
    function getAsString() {
    	return (string)$this->_bits;
    }
    
    /**
     * For use with chmod()
     * @return int the permission set as decimal integer
     */
    function getAsInt() {
    	return octdec($this->_bits);
    }
    
    /**
     * Returns a concise description of this permission set
     * @XXX rethink the whole concept, maybe just show a ower/group/world vs. r+w+x matrix
     */
    function getDescription() {
    	switch (intval($this->_bits, 8)) {
    	    case 0777: 
		return 'Read + Write + Execute for Everyone';
	    case 0555: 
		return 'Read + Execute for Everyone';
	    case 0666: 
		return 'Read + Write for Everyone';
	    case 0444: 
		return 'Read for Everyone';
	    case 0755: 
		return 'Read + Execute for Everyone, Plus Write for Owner';
	    case 0644: 
		return 'Read for Everyone, Plus Write for Owner';
	    default:
	        /* No description available */
	        return null;
    	}
    }
    
    function getAsDescriptiveString() {
    	return $this->getAsString() . ' (' . $this->getDescription() . ' )';
    }
    
    function equals($permissionBits) {
        return $this->getAsInt() == $permissionBits->getAsInt();
    }
    
    function isValid() {
    	$description = $this->getDescription();
    	return !empty($description);
    }
}

/* Functions which control the HTML output of the page. */
function status($msg, $obj) {
    print "$msg: <b>$obj</b><br>";
}

function error($msg, $obj) {
    print "<font color=red>$msg: <b>$obj</b></font><br>";
}

function isModulesOrThemesDirWriteable() {
    return is_writable(GALLERY_CONFIG_DIR . '/modules/') && 
    		is_writable(GALLERY_CONFIG_DIR . '/themes/');
}

/**
 * Make the themes/ and modules/ dir writeable or read-only
 * @param boolean true to make the dirs writeable, false to make them read-only
 * @return null on success, non 0 integer on error 
 */
function chmodModulesAndThemesDir($makeItWriteable) {
    $mode = $makeItWriteable ? 0777 : 0555;
    $ret = null;
    foreach (array('/modules/', '/themes/', '/plugins/modules/', '/plugins/themes/') as $dir) {
    	if (file_exists(GALLERY_CONFIG_DIR . $dir)) {
    		/* Try to chmod all dirs, even if one fails */
	    if (!chmod(GALLERY_CONFIG_DIR . $dir, $mode)) {
	        $ret = 1;	
	    }
    	}
    }
    return $ret;
}

function isGalleryDirWriteable() {
    return is_writable(GALLERY_CONFIG_DIR);
}

/**
 * Chmod the whole gallery dir recursively either read-only or writeable
 * @param boolean true to make the dirs writeable, false to make them read-only
 * @return null on success, non 0 integer on error 
 */
function chmodGalleryDirRecursively($makeItWriteable) {
    /* This is just a wrapper function for the general chmod recursively function */
    $folderMode = $makeItWriteable ? 0777 : 0555;
    $fileMode = $makeItWriteable ? 0666 : 0444;
    return chmodRecursively(GALLERY_CONFIG_DIR, $folderMode, $fileMode, time() - 60);
}

/* Chmod a specific plugin dir recursively */
function chmodPluginDir($pluginPath, $makeItWriteable) {
    /* This is just a wrapper function for the general chmod recursively function */
    $folderMode = $makeItWriteable ? 0777 : 0555;
    $fileMode = $makeItWriteable ? 0666 : 0444;
    return chmodRecursively(GALLERY_CONFIG_DIR . $pluginPath, $folderMode, $fileMode, time() - 60);
}

function chmodStorageDirRecursively() {
    /* This is just a wrapper function for the general chmod recursively function */
    return chmodRecursively(getGalleryStoragePath(), 0777, 0666, time() - 60);
}

/**
 * @return array (pluginId => boolean writeable, .. )
 */
function getPluginList() {
    /* 
     * We don't want to depend on the G2 API here, so just list the folders in 
     * modules/, themes/ and in plugins/modules/, plugins/themes/.
     * We prefer being indepdent of the state of G2 over flexibility (e.g. if the 
     * user hacked init.inc to set a different plugins dir name).
     */
    $plugins = array();
    foreach (array('/modules/', '/themes/', '/plugins/modules/', '/plugins/themes/') 
    	         as $base) {
	if (!file_exists(GALLERY_CONFIG_DIR . $base)) {
	    continue;
	}
	$fh = opendir(GALLERY_CONFIG_DIR . $base);
	if (empty($fh)) continue;
	
	/* For each folder in the plugin dir, check if it's writeable */
	while (($folderName = readdir($fh)) !== false) {
	    if ($folderName == '.' || $folderName == '..') {
		continue;
	    }
	    $pluginId = $base . trim($folderName);
	    $plugins[$pluginId] = (int)is_writable(GALLERY_CONFIG_DIR . $base . $folderName);
    	}
	closedir($fh);
    }
    return $plugins;
}

function getRequestVariable($varName) {
    foreach (array($_POST, $_GET) as $requestVars) {
	if (isset($requestVars[$varName])) {
	    return $requestVars[$varName];
	}
    }
    
    return null; 	
}

/* 
 * Uses JavaScript to print the status / error message at the top of the page 
 * even if the page has already been printed. 
 */
function printStatus($status) {
    if (!empty($status['error'])) {
	printf('<script type="text/javascript">printErrorMessage(\'%s\');</script>',
	       str_replace(array("\\", "'"), array("\\\\", "\\'"), 
	       		   implode('<br/>', $status['error'])));
    }
    if (!empty($status['message'])) {
	printf('<script type="text/javascript">printStatusMessage(\'%s\');</script>', 
	       str_replace(array("\\", "'"), array("\\\\", "\\'"), $status['message']));
    }
}

/************************************************************
 * HTML - The Page layout / GUI
 ************************************************************/

/**
 * Prints the whole page including form but without the footer.
 * Call this function, then call chmodRecursively() which will output some HTML,
 * and finally call printFooter();
 */
function printPageWithoutFooter($plugins, $path, $filePermissions, $folderPermissions, $permissionBitSets) {
    global $baseUrl;
?>
<html>
  <head>
    <title>Gallery Support - Change Filesystem Permissions</title>
    <link rel="stylesheet" type="text/css" href="<?php print $baseUrl ?>support.css"/>
    <style type="text/css">
	.highlightedLink {
	    font-weight: bold;
	    color: blue;
	}

	.disabledLink{
	    font-weight: lighter;
	    color: #888888;
	}
	
	div.success {
            border: solid green 1px;
            margin: 20px;
            padding: 10px;
        }
    </style>
    <script type="text/javascript">
      var plugins = new Array();
      <?php foreach ($plugins as $pluginId => $isOpenForEdit) {
        print "plugins['$pluginId'] = $isOpenForEdit;
        ";
      } ?>

      function setEditOrSecure(pluginId, formObj) {
        if (pluginId == -1) {
	  formObj.open.disabled = true;
	  formObj.secure.disabled = true;    	
        } else if (plugins[pluginId]) {
	  formObj.open.disabled = false;
	  formObj.secure.disabled = true;
        } else {
	  formObj.open.disabled = true;
	  formObj.secure.disabled = false;	
        }
      }
      
      function printStatusMessage(message) {
        var statusElement = document.getElementById('status');
        statusElement.innerHTML = message + "<a href=\"#details\">[details]</a>";
        statusElement.style.display = 'block';
      }
      
      function printErrorMessage(message) {
        var errorElement = document.getElementById('error');
        errorElement.innerHTML = message + 
          "<br/>Note: Please look at the <a href=\"#details\">[details]</a>. " +
          "You might be able to change the filesystem permissions of the failed directories " +
          "successfully yourself with an FTP program."
        errorElement.style.display = 'block';
      }
    </script>
  </head>

  <body>
      <H1>Change Filesystem Permissions</H1>
      <a href="index.php"> Back to Support Page </a>
      <h2>
        This tool lets you change the filesystem permissions of files and folders owned 
        by the webserver.
      </h2>
      <p>
        All files and folders in your Gallery storage folder are owned by the webserver. 
        Also, if you have used the &quot;pre-installer&quot;
        to get Gallery onto the webserver, your whole Gallery folder is owned by the 
        webserver as well. But usually the Gallery folder was created by yourself.
      </p>
      <p>
        See: <b><a href="http://codex.gallery2.org/index.php/Gallery2:Security">
        Gallery Security Guide</a></b>
      </p>

      <!-- Identifyable placeholders such that we can insert our messages during runtime via JS. -->
      <div id="error" class="error" style="display: none;">
        &nbsp;
      </div>

      <div id="status" class="success" style="display: none;">
        &nbsp;
      </div>
      
      <h3>Please choose an action:</h3>
      
      <div class="box">
      <?php if (isModulesOrThemesDirWriteable()): ?>
      <h3><a href="index.php?chmod&amp;command=<?php 
        print CMD_CHMOD_MODULES_AND_THEMES_DIR; ?>&amp;mode=open">Add a new module or theme 
        (make modules/ &amp; themes/ writeable)</a></h3>
      <?php else: ?>
      <h3><a href="index.php?chmod&amp;command=<?php 
        print CMD_CHMOD_MODULES_AND_THEMES_DIR; ?>&amp;mode=secure">Secure the modules/ and 
        themes/ folder (make modules/ &amp; themes/ read-only)</a></h3>
      <?php endif; ?>
      <p>
        When adding a new module or theme you must first make your modules/ or themes/ folder 
        writeable.<br/>
        Only useful if you have installed Gallery with the pre-installer. Usually you can 
        change the filesystem permissions with your FTP program.
      </p>
      </div>

      <div class="box">
      <form name="pluginForm" method="POST" action="index.php?chmod&amp;command=<?php 
      print CMD_CHMOD_PLUGIN_DIR; ?>">
      <h3 id="themeOrModule">
        Edit or remove a specific module / theme (make themes/xxx/ or modules/xxx writeable)
      </h3>
      &nbsp;
      <select name="pluginId" 
      onchange="setEditOrSecure(this.options[this.selectedIndex].value, this.form)">
      <option value="-1">Please select a module or theme:</option>
      <?php foreach ($plugins as $pluginId => $writeable) {
                print '<option value="' . $pluginId . '">' . $pluginId . "</option>\n";
      } ?>
      </select>
      &nbsp;&nbsp;
      <input type="hidden" name="mode" value="open"/>
      <input type="submit" disabled name="open" value="Open for edit (writeable)"/> | 
      <input type="submit" disabled name="secure" value="Make it read-only (secure)"/> 
      <p>
        If you want to edit a page template file of a specific module or theme and your 
        Gallery was originally installed with the pre-installer, you might have to make 
        the corresponding plugin folder writeable first.
      </p>
      </form> 
      </div>

      <div class="box">
      <h3><a href="index.php?chmod&amp;command=<?php print CMD_CHMOD_STORAGE_DIR; 
      ?>">Fix the storage folder 
      (make it writeable)</a></h3>
      <p>
        For some reason, your Gallery storage folder might no longer be writeable by Gallery itself
        and if that happens, Gallery will usually show a ERROR_STORAGE_FAILURE. In that case the 
        problem might be solved by the above action. If the problem persists, you will have to talk
        to your webhost to get the storage folder writeable again.
      </p>
      </div>

      <div class="box">
      <?php if (isGalleryDirWriteable()): ?>
      <h3><a href="index.php?chmod&amp;command=<?php print CMD_CHMOD_GALLERY_DIR; 
      ?>&amp;mode=open">Prepare for Upgrade: Make everything writeable (deleteable)</a></h3>
      <?php else: ?>
      <h3><a href="index.php?chmod&amp;command=<?php print CMD_CHMOD_GALLERY_DIR; 
      ?>&amp;mode=secure">Secure the installation after upgrade</a></h3>
      <?php endif; ?>
      <p>
        If your Gallery has been installed with the pre-installer, you might have to make the 
        whole Gallery directory structure writeable before you can upgrade your installation.<br/>
        Similarely, if you want to remove Gallery altogether from your website and if it was installed
        with the pre-installer, you need to do the same.
      </p>
      </div>
      
      <div class="box">
        <h3>Advanced: Choose the path and the permissions manually</h3>
        <form method="POST" action="index.php?chmod&amp;command=<?php print CMD_ADVANCED; ?>">
	  <h4>Please enter the filesystem path of the folder / file:</h3>
	  <p style="size=-1">
	    (e.g. the path to your Gallery folder is <i><?php print getGalleryBasePath(); ?>
            </i> and the path to your Gallery storage folder is 
            <i><?php print getGalleryStoragePath(); ?></i>.
          </p>
          <input type="text" name="path" size="50" value="<?php print $path; ?>"/>
	  <h4>Please select the new file / folder permissions:</h3>
          <table>
            <?php $first = true; foreach ($permissionBitSets as $permissionBitSet):
             $checked = ($permissionBitSet[1]->equals($filePermissions)) ? 'checked' : '';
             $value = $permissionBitSet[0]->getAsString() . $permissionBitSet[1]->getAsString(); 
             $longDescription = '<font size=-2>[Files: ' . $permissionBitSet[1]->getDescription() .
              ' (' . $permissionBitSet[1]->getAsString() . ') / Folders: ' . 
              $permissionBitSet[0]->getDescription() .
              ' (' . $permissionBitSet[0]->getAsString() . ')]</font>';
             printf('<tr><td><input type="radio" name="permissions" value="%s" %s/></td><td>%s</td><td>&nbsp;</td><td>%s</td></tr>',
                     $value, $checked, $permissionBitSet[1]->getDescription(), $longDescription);
            endforeach; ?>
          </table>
          <p>
            Note: Execute permission on a folder means that you can list the files and sub-folders
            in that folder. Usually, you always want to give execute and read permissions on 
            folders to everyone and read permissions on files to everyone.  
          </p>
	  <input type="submit" value="Change the Permissions now!"/>
	</form>
      </div>
      
      <a name="details"></a>
      <div class="box">
        <h3>Results:</h3>
        
<?php
} // end function printPageWithoutFooter()

function printFooter() {
?>
    </div>
  </body>
</html>
<?php
} // end function printFooter()
?>