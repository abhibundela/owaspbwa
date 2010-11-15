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

require_once 'Upgrader.php';

class Upgrade24To242 extends Upgrader {

	public function isDataCompatible() {
	    return true;
	}

	public function applyConstraints($sqlPath, $dbName) {
	    return true;
	}

	public function createNewTables($sqlPath, $dbName) {
	    return true;
	}

	public function applyDbAlterations($sqlPath, $dbName) {
	    return true;
	}

	public function storeDefaultData($sqlPath, $dbName) {
	    return true;
	}

	public function changeExistingData($dbName, $choiceArr) {
	    return true;
	}

	public function createConfFile($newDbName) {

		/* Checking lib/confs directory */
		 if (!is_writable("../lib/confs")) {
		    $errorArray[] = "/lib/confs is not writable";
		    $this->errorArray = $errorArray;
		    return false;
		}
		$dbHost = $this->dbHost;
		$dbHostPort = $this->dbPassword;
		$dbName = $newDbName;
		$dbUser = $this->dbUsername;
		$dbPassword = $this->dbPassword;

    $confContent = <<< CONFCONT
<?php
class Conf {

	var \$smtphost;
	var \$dbhost;
	var \$dbport;
	var \$dbname;
	var \$dbuser;
	var \$version;

	function Conf() {

		\$this->dbhost	= '$dbHost';
		\$this->dbport 	= '$dbHostPort';
		\$this->dbname	= '$dbName';
		\$this->dbuser	= '$dbUser';
		\$this->dbpass	= '$dbPassword';
		\$this->version = '2.4.2';

		\$this->emailConfiguration = dirname(__FILE__).'/mailConf.php';
		\$this->errorLog =  realpath(dirname(__FILE__).'/../logs/').'/';
	}
}
?>
CONFCONT;

		$filename = '../lib/confs/Conf.php';
		$handle = fopen($filename, 'w');
		if (!fwrite($handle, $confContent)) {
			$errorArray[] = "Conf.php could not be written to /lib/confs";
		    $this->errorArray = $errorArray;
		    return false;
		}
	    fclose($handle);
	    return true;

	}

	public function createUpgradeConfFile() {

		/* Checking lib/confs directory */
		if (!is_writable("../lib/confs")) {
		    $errorArray[] = "/lib/confs is not writable";
		    $this->errorArray = $errorArray;
		    return false;
		}
    $confContent = <<< CONFCONT
<?php
/* Upgraded from version 2.4 to 2.4.2 */
?>
CONFCONT;

		$filename = '../lib/confs/upgradeConf.php';
		$handle = fopen($filename, 'w');
		if (!fwrite($handle, $confContent)) {
			$errorArray[] = "upgradeConf.php could not be written to /lib/confs";
		    $this->errorArray = $errorArray;
		    return false;
		}
	    fclose($handle);
	    return true;

	}



}

?>
