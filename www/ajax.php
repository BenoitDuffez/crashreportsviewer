<?php

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
