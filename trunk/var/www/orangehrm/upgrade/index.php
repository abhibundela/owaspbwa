<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

require_once 'Authorize.php';

session_start();

/* Check whether the upgrading has been done */
if (file_exists('../lib/confs/upgradeConf.php')) {
    header('location:../');
}

/* Check whether the newversion is in correct location */
if (!file_exists('../../lib/confs/Conf.php')) {
    echo "You have put upgrader in wrong location. It should be under /newversion/upgrade/";
    die;
}

/* Check whether /newversion is writable */
if (!is_writable('../')) {
    echo realpath("../")." is not writable. Please set write permission and re-run the upgrader.";
    die;
}

/* Initializing current Conf Object */
$oldConfObj = new Conf();
$oldVersion = $oldConfObj->version;

/* Loading relevant upgrader class */
function __autoload($class_name) {
    require_once $class_name . '.php';
}

/* Initializing upgrader details */
if ($oldVersion == '2.2.2.2') {
	$steps = array('welcome', 'version check', 'database information', 'data import', 'database changes', 'value changes', 'configuration files');
	$screenArr = array('welcome'=>0, 'versionCheck'=>1, 'dbInfo'=>2, 'dataImport'=>3, 'dbChanges'=>4, 'valueChanges'=>5, 'confFiles'=>6);
	$oldTablesSqlPath = 'sql/2222tables.sql';
    $oldConstraintsSqlPath = 'sql/2222constraints.sql';
    $newTablesSqlPath = 'sql/2222to242newTables.sql';
    $newAlterSqlPath = 'sql/2222to242alterations.sql';
    $newDefaultDataSqlPath = 'sql/2222to242defaultData.sql';
    $upgrader = new Upgrade2222To242($oldConfObj);
} elseif ($oldVersion == '2.3') {
	$steps = array('welcome', 'version check', 'database information', 'data import', 'database changes', 'configuration files');
	$screenArr = array('welcome'=>0, 'versionCheck'=>1, 'dbInfo'=>2, 'dataImport'=>3, 'dbChanges'=>4, 'confFiles'=>5);
    $oldTablesSqlPath = 'sql/23tables.sql';
    $oldConstraintsSqlPath = 'sql/23constraints.sql';
    $newTablesSqlPath = 'sql/23to242newTables.sql';
    $newAlterSqlPath = 'sql/23to242alterations.sql';
    $newDefaultDataSqlPath = 'sql/23to242defaultData.sql';
    $upgrader = new Upgrade23To242($oldConfObj);
} elseif ($oldVersion == '2.4' || $oldVersion == '2.4.0.1') {
	$steps = array('welcome', 'version check', 'database information', 'data import', 'configuration files');
	$screenArr = array('welcome'=>0, 'versionCheck'=>1, 'dbInfo'=>2, 'dataImport'=>3, 'confFiles'=>4);
	$oldTablesSqlPath = 'sql/24tables.sql';
    $oldConstraintsSqlPath = 'sql/24constraints.sql';
    $upgrader = new Upgrade24To242($oldConfObj);
} elseif ($oldVersion == '2.4.1') {
	$steps = array('welcome', 'version check', 'database information', 'data import', 'configuration files');
	$screenArr = array('welcome'=>0, 'versionCheck'=>1, 'dbInfo'=>2, 'dataImport'=>3, 'confFiles'=>4);
	$oldTablesSqlPath = 'sql/24tables.sql';
    $oldConstraintsSqlPath = 'sql/24constraints.sql';
    $upgrader = new Upgrade241To242($oldConfObj);
}

/* Variables used for Ajax calls */
$dataImportAjax = 'No';
$dbChangesAjax = 'No';
$confFilesAjax = 'No';

/* Checking whether upgrader support curret version */
$versionSupport = false;
if ($oldVersion == '2.2.2.2' || $oldVersion == '2.3' || $oldVersion == '2.4' || $oldVersion == '2.4.0.1' || $oldVersion == '2.4.1') {
    $versionSupport = true;
}

if (!isset($_REQUEST['hdnState'])) {
	$currScreen = $screenArr['welcome']; $currPage = 'templates/welcome.inc'; require_once 'templates/mainUi.php';
} else {

	$state = $_REQUEST['hdnState'];

	switch ($state) {

		case 'authAdmin':
			if (Authorize::authAdmin(trim($_POST['txtUsername']), trim($_POST['txtPassword']))) {
			   	if ($versionSupport) {
					$currScreen = $screenArr['versionCheck']; $currPage = 'templates/upgradeStart.inc'; require_once 'templates/mainUi.php';
			   	} else {
			   		$currScreen = $screenArr['versionCheck']; $currPage = 'templates/error-version.inc'; require_once 'templates/mainUi.php';
			   	}
			} else {
			    $currScreen = $screenArr['welcome']; $currPage = 'templates/error-login.inc'; require_once 'templates/mainUi.php';
			}
			break;

		case 'upgradeStart':
			if ($upgrader->isDataCompatible()) {
				$currScreen = $screenArr['dbInfo']; $currPage = 'templates/dbInfo.inc'; require_once 'templates/mainUi.php';
			} else {
				$currScreen = $screenArr['dbInfo']; $currPage = 'templates/error-data.inc'; require_once 'templates/mainUi.php';
			}
			break;

		case 'dbInfo':
			$dbName = mysql_real_escape_string(trim($_POST['newDbName']));
			if ($upgrader->isDatabaseAccessible($dbName)) {
				if ($upgrader->executeSql($oldTablesSqlPath, $dbName)) {
					$_SESSION['newDb'] = $dbName;
					$dataImportAjax = 'Yes';
					$tablesArray = $upgrader->getAllTables($dbName);
					$currScreen = $screenArr['dataImport']; $currPage = 'templates/dataImport.inc'; require_once 'templates/mainUi.php';
				} else {
					$currScreen = $screenArr['dbInfo']; $currPage = 'templates/error-tableCreation.inc'; require_once 'templates/mainUi.php';
				}
			} else {
				$currScreen = $screenArr['dbInfo']; $currPage = 'templates/error-db.inc'; require_once 'templates/mainUi.php';
			}
			break;

		case 'dataImport':
			$tableName = $_REQUEST['table'];
			$logHandle = fopen('upgraderLog.log', 'a');
			if ($upgrader->importDataFromTable($tableName, $oldConfObj->dbname, $_SESSION['newDb'])) {
			    echo 'Yes-'.$tableName;
			    $logMessage = "Importing from ".$tableName." succeeded \n\n";
			    fwrite($logHandle, $logMessage);
			} else {
				echo 'No-'.$tableName;
			    $logMessage = "Importing from ".$tableName." failed \n Reason: ".mysql_error()."\n\n";
			    fwrite($logHandle, $logMessage);
			}
			fclose($logHandle);
			break;

		case 'oldConstraints':
			$dbName = $_SESSION['newDb'];
			if ($upgrader->applyConstraints($oldConstraintsSqlPath, $dbName)) {
				$dbChangesAjax = 'Yes';
				if ($oldVersion == '2.4' || $oldVersion == '2.4.0.1' || $oldVersion == '2.4.1') {
					$confFilesAjax = 'Yes';
				    $currScreen = $screenArr['confFiles']; $currPage = 'templates/copyConfFiles.inc';
				} else {
				    $currScreen = $screenArr['dbChanges']; $currPage = 'templates/newDbChanges.inc';
				}
				require_once 'templates/mainUi.php';
			} else {
				$currScreen = $screenArr['dbChanges']; $currPage = 'templates/error-constraints.inc'; require_once 'templates/mainUi.php';
			}
			break;

		case 'newDbChanges':

			$action = $_REQUEST['action'];
			$dbName = $_SESSION['newDb'];

			switch ($action) {

			    case 'tables':
			    	if ($upgrader->createNewTables($newTablesSqlPath, $dbName)) {
						echo 'tablesYes';
			    	} else {
						echo 'tablesNo';
			    	}
			    	break;

			    case 'alter':
			    	if ($upgrader->applyDbAlterations($newAlterSqlPath, $dbName)) {
						echo 'alterYes';
			    	} else {
						echo 'alterNo';
			    	}
			    	break;

			    case 'store':
			    	if ($upgrader->storeDefaultData($newDefaultDataSqlPath, $dbName)) {
						echo 'storeYes';
			    	} else {
						echo 'storeNo';
			    	}
			    	break;

			}

			break;

		case 'dbValueChangeOption':
	                if($oldVersion == '2.3'){
	                	$confFilesAjax = 'Yes';
	                	$currScreen = $screenArr['confFiles']; $currPage = 'templates/copyConfFiles.inc';
	                }elseif($oldVersion == '2.2.2.2'){
	                	$currScreen = $screenArr['valueChanges']; $currPage = 'templates/dbValueChanges.inc';
	                }
	                require_once 'templates/mainUi.php';
			break;

		case 'dbValueChanges':

			$choiceArr = array();

			if (isset($_POST['chkEncryption']) && $_POST['chkEncryption'] == 'Enable') {
				$choiceArr[] = "encryption";
			}

			if ($upgrader->changeExistingData($_SESSION['newDb'], $choiceArr)) {
				$confFilesAjax = 'Yes';
	        	$currScreen = $screenArr['confFiles']; $currPage = 'templates/copyConfFiles.inc'; require_once 'templates/mainUi.php';
			} else {
			    $currScreen = $screenArr['valueChanges']; $currPage = 'templates/error-dbValues.inc'; require_once 'templates/mainUi.php';
			}

			break;

		case 'locateConfFiles':

			$action = trim($_REQUEST['action']);
			$dbName = trim($_SESSION['newDb']);

			switch ($action) {

			    case 'conf':
			    	if ($upgrader->createConfFile($dbName)) {
						echo 'confYes';
			    	} else {
						echo 'confNo';
			    	}
			    	break;

			    case 'mail':
			    	$filePath = '../../lib/confs/mailConf.php';
			    	if (file_exists($filePath)) {
				    	$newFilePath = '../lib/confs/mailConf.php';
				    	if ($upgrader->copyFile($filePath, $newFilePath)) {
							echo 'mailYes';
				    	} else {
							echo 'mailNo';
				    	}
			    	} else {
						echo 'mailNoFile';
			    	}
			    	break;

	            case 'enckey':
			    	$filePath = '../../lib/confs/cryptokeys/key.ohrm';
			    	if (file_exists($filePath)) {
				    	$newFilePath = '../lib/confs/cryptokeys/key.ohrm';
				    	if ($upgrader->copyFile($filePath, $newFilePath)) {
							echo 'enckeyYes';
				    	} else {
							echo 'enckeyNo';
				    	}
			    	} else {
						echo 'enckeyNoFile';
			    	}
			    	break;

			    case 'upgrade':
			    	if ($upgrader->createUpgradeConfFile()) {
						echo 'upgradeYes';
			    	} else {
						echo 'upgradeNo';
			    	}
			    	break;

			}

			break;

		case 'invalidLogin':
			header('location:./');
			break;

		case 'versionError':
			header('location:../../');
			break;

		case 'dataError':
			header('location:../../');
			break;

		case 'upgradeFinish':
			session_destroy();
			header('location:../');
			break;

		case 'confError':
			$conf = '../lib/confs/Conf.php';
			$upgradeConf = '../lib/confs/upgradeConf.php';
			$mailConf ='../lib/confs/mailConf.php';
			if (file_exists($conf)) {
			    unlink($conf);
			}
			if (file_exists($upgradeConf)) {
			    unlink($upgradeConf);
			}
			if (file_exists($mailConf)) {
			    unlink($mailConf);
			}
			header('location:./');
			break;

	}

}

?>
