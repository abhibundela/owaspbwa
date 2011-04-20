<?php
if (!defined('G2_SUPPORT')) return;

function getCaches() {
    $dirs = array(
	'cached pages' => array(true, 'clearPageCache', array(), 'Cached HTML pages'),
	'entity' => array(true, 'clearG2dataDir', array('cache/entity'), 'Albums and photo data'),
	'module' => array(true, 'clearG2dataDir', array('cache/module'), 'Module settings'),
	'theme' => array(true, 'clearG2dataDir', array('cache/theme'), 'Theme settings'),
	'template' => array(true, 'clearG2dataDir', array('smarty/templates_c'), 'Smarty templates'),
	'tmp' => array(true, 'clearG2dataDir', array('tmp'), 'Temporary directory'),
	'derivative' => array(
	    false, 'clearG2dataDir', array('cache/derivative'),
	    'Thumbnails and resizes <span class="subtext">(expensive to rebuild)</span>')
	);
    return $dirs;
}

function recursiveRmdir($dirname, &$status) {
    $count = 0;
    if (!file_exists($dirname)) {
	return $count;
    }

    if (!($fd = opendir($dirname))) {
	return $count;
    }

    while (($filename = readdir($fd)) !== false) {
	if (!strcmp($filename, '.') || !strcmp($filename, '..')) {
	    continue;
	}
	$path = "$dirname/$filename";

	if (is_dir($path)) {
	    $count += recursiveRmdir($path, $status);
	} else {
	    if (!@unlink($path)) {
		if (!@is_writeable($path)) {
		    $status[] = array("error", "Permission denied removing file $path");
		} else {
		    $status[] = array("error", "Unable to remove file $path");
		}
	    } else {
		$count++;
	    }
	}
    }
    closedir($fd);

    if (!@rmdir($dirname)) {
	$status[] = array("error", "Unable to remove directory $dirname");
    } else {
	$count++;
    }

    return $count;
}

function clearPageCache() {
    require_once(dirname(__FILE__) . '/../../embed.php');
    GalleryEmbed::init();

    $ret1 = GalleryCoreApi::removeAllMapEntries('GalleryCacheMap');
    $ret2 = GalleryEmbed::done();
    if ($ret1 || $ret2) {
	$status[] = array('error', 'Error deleting page cache!');
    } else {
	$status[] = array('info', 'Successfully deleted page cache');
    }

    return $status;
}

function clearG2DataDir($dir) {
    global $gallery;
    $path = $gallery->getConfig('data.gallery.base') . $dir;
    $status[] = array('info', "Deleting dir: $path");
    $count = recursiveRmdir($path, $status);

    /* Commented this out because it's a little noisy */
    /*
     * $status[] = array('info', "Removed $count files and directories");
     */

    if (mkdir($path)) {
	$status[] = array('info', "Recreating dir: $path");
    } else {
	$status[] = array('error', "Unable to recreate dir: $path");
    }
    return $status;
}

$status = array();
if (isset($_REQUEST['clear'])) {
    if (isset($_REQUEST['target'])) {
	$caches = getCaches();
	foreach ($_REQUEST['target'] as $key => $ignored) {
	    /* Make sure the dir is legit */
	    if (!array_key_exists($key, $caches)) {
		$status[] = array("error", "Ignoring illegal cache: $key");
		continue;
	    }

	    $func = $caches[$key][1];
	    $args = $caches[$key][2];
	    $status = array_merge($status, call_user_func_array($func, $args));
	}
    }
}
?>
<html>
  <head>
    <title>Cache Maintenance</title>
    <link rel="stylesheet" type="text/css" href="<?php print $baseUrl ?>support.css"/>
  </head>

  <body>
      <H1> Cache Maintenance </H1>
      <a href="index.php"> Back to Support Page </a>
      <h2>
	Gallery caches data on disk to increase performance.
	Sometimes these caches get out of date and need to be deleted.
	Anything in the cache can be deleted safely, because Gallery
	will recreate anything that it needs.  However, some things
	are more expensive to recreate than others so you might not
	want to delete everything.  If you're in doubt, accept the
	defaults below.
      </h2>

      <?php if (!empty($status)): ?>
      <div class="status">
	<?php foreach ($status as $line): ?>
	<span class="line_<?php print $line[0]?>">
	  <?php print $line[1] ?>
	</span>
	<?php endforeach; ?>
      </div>
      <?php endif; ?>

      <form method="POST">
	<?php $caches = getCaches(); ?>
	<div class="box">
	  <?php foreach ($caches as $key => $info): ?>
	  <div>
	    <input type="checkbox" name="target[<?php print $key ?>]"
		   <?php if ($info[0]): ?> checked="checked" <?php endif; ?> />
	    <?php print $info[3] ?>
	  </div>
	  <?php endforeach; ?>
	  <div>
	    <input type="submit" name="clear" value="Clear Cache"/>
	  </div>
        </div>
      </form>
  </body>
</html>
