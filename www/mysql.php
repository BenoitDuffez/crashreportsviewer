<?php

/*
 * Copyright 2013 Benoit Duffez
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

@include "../config.php";

$mysql = mysql_connect($mysql_server, $mysql_user, $mysql_password);
if (!$mysql) {
	bicou_log("Unable to connect to mysql server: ".mysql_error());
	die("Server down");
}

if (!mysql_select_db($mysql_db)) {
	bicou_log("Unable to select db: ".mysql_error());
	die("Server down");
}

include "mysql.upgrade.php";

// ----------------------------------------------------------------------------

function bicou_mysql_insert($object) {
	global $table_prefix;
	$cols = "INSERT INTO {$table_prefix}crashes (".implode(", ", array_keys($object)).") ";
	$vals = "VALUES ('".implode("', '", $object)."')";
	return $cols.$vals;
}

function bicou_mysql_select($columns = NULL, $table = NULL, $selection = NULL, $selectionArgs = NULL, $order = NULL, $group = NULL, $limit = NULL) {
	global $table_prefix;
	// Columns
	if ($columns != NULL) {
		$cols = implode(", ", $columns);
	} else {
		$cols = "*";
	}
	
	// Table
	if ($table != NULL) {
		$tbl = "FROM {$table_prefix}" . $table;
	} else {
		$tbl = "FROM {$table_prefix}crashes";
	}
	
	// Selection
	if ($selection == NULL) {
		$condition = "1";
	} else {
		$sel = str_replace(array("%", "?"), array("%%", "%s"), $selection);
		$selA = array();
		foreach($selectionArgs as $s) {
			if (gettype($s) == "integer") {
				$selA[] = "$s";
			} else if (gettype($s) == "double") {
				$selA[] = sprintf("%.5f", 0+$s);
			} else if (gettype($s) == "string") {
				$selA[] = "'". mysql_real_escape_string($s) ."'";
			} else {
				bicou_log("Unhandled SQL type: ".gettype($s)." with value '$s'");
				$selA[] = mysql_real_escape_string($s);
			}
		}
		$condition = vsprintf($sel, $selA);
	}

	// Order
	if ($order == NULL) {
		$order = "id DESC";
	}

	// Group
	if ($group != null) {
		$grp = "GROUP BY ".mysql_real_escape_string($group);
	} else {
		$grp = "";
	}

	// Limit
	if ($limit != null) {
		$lmt = "LIMIT ".mysql_real_escape_string($limit);
	} else {
		$lmt = "";
	}

	$sql = "SELECT $cols $tbl WHERE $condition $grp ORDER BY $order $lmt";
//	echo $sql;
	return $sql;
}

function bicou_mysql_update($object, $selection, $selectionArgs) {
	global $table_prefix;
	
	$sel = str_replace("?", "%s", $selection);
	$selA = array();
	foreach($selectionArgs as $s) {
		$selA[] = mysql_real_escape_string($s);
	}
	$condition = vsprintf($sel, $selA);

	$sql = "";
	foreach ($object as $k => $v) {
		if ($sql == "") {
			$sql = "UPDATE {$table_prefix}crashes SET ";
		} else {
			$sql .= ", ";
		}

		$sql .= "$k = '$v'";
	}

	return $sql . " WHERE " . $condition;
}


/************************
Fields: (* int)
id *
report_id
app_version_code
app_version_name
package_name
file_path
phone_model
android_version
build
brand
product
total_mem_size *
available_mem_size *
custom_data
custom_data
stack_trace
initial_configuration
crash_configuration
display
user_comment
user_app_start_date *
user_crash_date *
dumpsys_meminfo
dropbox
logcat
eventslog
radiolog
is_silent
device_id
installation_id
user_email
device_features
environment
settings_system
settings_secure
shared_preferences
***********************/

// Values
$values = array(	//"id", // auto_increment
	"report_id",
	"app_version_code",
	"app_version_name",
	"package_name",
	"file_path",
	"phone_model",
	"android_version",
	"build",
	"brand",
	"product",
	"total_mem_size",
	"available_mem_size",
	"custom_data",
	"stack_trace",
	"initial_configuration",
	"crash_configuration",
	"display",
	"user_comment",
	"user_app_start_date",
	"user_crash_date",
	"dumpsys_meminfo",
	"dropbox",
	"logcat",
	"eventslog",
	"radiolog",
	"is_silent",
	"device_id",
	"installation_id",
	"user_email",
	"device_features",
	"environment",
	"settings_system",
	"settings_secure",
	"shared_preferences");

?>
