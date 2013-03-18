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

include "mysql.php";
include "crashes.php";

ob_start();
$obj = array();

$sel = "issue_id = '?'";
$selA = array($_GET[issue_id]);

if ($_GET[action] == "update_status") {
	$obj['status'] = intval($_GET[status]);
}

$sql = bicou_mysql_update($obj, $sel, $selA);
$res = mysql_query($sql);

if ($res) {
	ob_end_clean();
	echo "OK ($sql)";
} else {
	$file = fopen("last_ajax_fail", "w");
	fputs($file, "Unable to execute query: $sql\n");
	print_r($obj);
	echo "\n_GET: ";
	print_r($_GET);
	echo "\n_POST: ";
	print_r($_POST);
	fputs($file, "Object: ".ob_get_clean()."\n");
	fclose($file);

	echo "KO";
}

?>
