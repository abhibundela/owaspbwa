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

class Upgrade23To242 extends Upgrader {

	public function isDataCompatible() {

	/* By 2.4.2, hs_hr_empreport.rep_name and hs_pr_salary_grade.sal_grd_name were made unique */
		$flag = true;
		$errorArray = array();
		// This query check whether there are duplicate report names
		$query = "SELECT COUNT(*) as repetitions, `rep_name` FROM `hs_hr_empreport` GROUP BY `rep_name` HAVING repetitions > 1";
		$result = mysql_query($query, $this->conn);
		if (mysql_num_rows($result) > 0) {
			$flag = false;
			$errorArray[] = "You have duplicate report names in Report Module (Reports > View Reports)";
		}
		// This query check whether there are duplicate pay grades
		$query = "SELECT COUNT(*) as repetitions, `sal_grd_name` FROM `hs_pr_salary_grade` GROUP BY `sal_grd_name` HAVING repetitions > 1";
		$result = mysql_query($query, $this->conn);
		if (mysql_num_rows($result) > 0) {
			$flag = false;
			$errorArray[] = "You have duplicate pay grade names in Admin Module (Admin > Job > Pay Grades)";
		}
		$this->errorArray = $errorArray;
		return $flag;
	}

	public function applyConstraints($sqlPath, $dbName) {

		$result = $this->executeSql($sqlPath, $dbName);
		return $result;
	}

	public function createNewTables($sqlPath, $dbName) {

		$result = $this->executeSql($sqlPath, $dbName);
		return $result;
	}

	public function applyDbAlterations($sqlPath, $dbName) {

		$result = $this->executeSql($sqlPath, $dbName);
		return $result;
	}

	public function storeDefaultData($sqlPath, $dbName) {

		$result1 = $this->executeSql($sqlPath, $dbName);
		$result2 = $this->_fillEmployeeHistoryTable($dbName);
		
		if($result1 && $result2){
			return true;
		}else{
			return false;
		}
		
	}
	
	private function _fillEmployeeHistoryTable($dbName){

		mysql_selectdb($dbName);
		/* Get employee data from `hs_hr_employee` and `hs_hr_job_title` tables */

		$query = "SELECT a.`emp_number` , a.`job_title_code` , a.`joined_date` , b.`jobtit_name` FROM hs_hr_employee a, hs_hr_job_title b WHERE a.`job_title_code` IS NOT NULL AND a.`job_title_code` = b.`jobtit_code`";
		$result = mysql_query($query);
		
		if (mysql_num_rows($result) > 0) {

			while($row = mysql_fetch_array($result)){

				if(isset($insertSqlSub)){
                	$insertSqlSub = $insertSqlSub .  ' , '  . '(' .  "'" .  $row['emp_number'] .  "'" . ' , ' . "'" . $row['job_title_code'] . "'" . ' , ' . "'" . $row['jobtit_name'] .  "'" . ' , ' . "'" . $row['joined_date'] . "'" . ' ) ';
                 }else{
                 	$insertSqlSub = '(' .  "'" .  $row['emp_number'] .  "'" . ' , ' . "'" . $row['job_title_code'] . "'" . ' , ' . "'" . $row['jobtit_name'] .  "'" . ' , ' . "'" . $row['joined_date'] . "'" . ' ) ';
                 }

            }

            /* Insert data to hs_hr_emp_jobtitle_history table  */

            $insqrtSql = "INSERT INTO hs_hr_emp_jobtitle_history (`emp_number` , `code` , `name` , `start_date`) VALUES $insertSqlSub";

            if (!mysql_query($insqrtSql)) {
            	
            	$this->errorArray[] = "Filling Employee History table failed";
    			return false;
    			
    		}

        	return true;
		
		} else {
			return true;
		}
		
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
/* Upgraded from version 2.3 to 2.4.2 */
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
