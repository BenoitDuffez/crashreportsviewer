<?php

include "mysql.php";
include "crashes.php";

// Display reports
$sql = bicou_mysql_select(array($_GET[field]), "id = '?'", array($_GET[id]));
$sql .= " LIMIT 0, 100";
$res = mysql_query($sql);

if (!$res) {
	bicou_log("Unable to query: $sql");
	echo "<p>Server error.</p>\n";
	return;
}
$tab = mysql_fetch_assoc($res);
echo '<pre>' . $tab[$_GET[field]] . '</pre>';
?>
