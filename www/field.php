<?php

include "mysql.php";
include "crashes.php";

$field = $_GET[field] == 'overview' ? "stack_trace" : $_GET[field];

// Display reports
$sql = bicou_mysql_select(array($field), "crashes", "id = ?", array($_GET[id]), null, null, "0, 100");
$res = mysql_query($sql);

if (!$res) {
	bicou_log("Unable to query: $sql");
	echo "<p>Server error.</p>\n";
	return;
}
$tab = mysql_fetch_assoc($res);
if ($_GET[field] == 'overview') {
	$tab[$field] = "<ul><li>" . str_replace("<br />", "</li><li>", bicou_stack_trace_overview($tab[$field], null)) . "</li></ul>";
	$tab[$field] = str_replace("<li></li>", "", $tab[$field]);
}
echo '<pre>' . $tab[$field] . '</pre>';
?>
