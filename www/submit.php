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

//$f = fopen("last_access", "w");
//fputs($f, "access on ".date("d/M/Y G:i:s")."\n");
//fclose($f);

ob_start();

// Check _POST
if (count($_POST) == 0) {
        bicou_log("Empty _POST query");
        die();
}

foreach($_POST as $k => $v) {
        if (array_search(strtolower($k), $values) === FALSE) {
                continue;
        }

        $object[strtolower($k)] = mysql_real_escape_string($v);
}

// Add custom data
$object['added_date'] = time();
$object['status'] = STATE_NEW;
$object['issue_id'] = bicou_issue_id($object['stack_trace'], $object['package_name']);

// Save to DB
$sql = bicou_mysql_insert($object);
$success = mysql_query($sql);

if ($success != TRUE) {
        bicou_log("Unable to save record: ".mysql_error());
        bicou_log("Query was: ".$sql);
}

// Close MySQL
mysql_close($mysql);

//$f = fopen("log", "w+");
//fputs($f, "Output of ".date("d/M/Y G:i:s").":\n".ob_get_clean());
//fclose($f);

echo "Oh, hai!";

?>
