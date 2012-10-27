<?php

include "mysql.php";
include "crashes.php";

$f = fopen("last_access", "w");
fputs($f, "access on ".date("d/M/Y G:i:s")."\n");
fclose($f);

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

$f = fopen("log", "w+");
fputs($f, "Output of ".date("d/M/Y G:i:s").":\n".ob_get_clean());
fclose($f);

echo "Oh, hai!";

?>
