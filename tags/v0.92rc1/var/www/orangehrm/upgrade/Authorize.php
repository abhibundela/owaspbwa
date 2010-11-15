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

require_once '../../lib/confs/Conf.php';

class Authorize {

    public static function authAdmin($username, $password) {

		$oldConfObj = new Conf();
		mysql_connect($oldConfObj->dbhost, $oldConfObj->dbuser, $oldConfObj->dbpass);
		mysql_select_db($oldConfObj->dbname);

		$query = "SELECT `id` FROM `hs_hr_users` WHERE `user_name` = '$username' AND `user_password` = '".md5($password)."' AND `is_admin` = 'Yes'";
		$result = mysql_query($query);

		if (mysql_num_rows($result) == 1) {
		    return true;
		} else {
		    return false;
		}

    }

}

?>
