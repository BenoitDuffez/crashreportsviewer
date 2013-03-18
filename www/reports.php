<?php

include "html.php";
include "mysql.php";
include "crashes.php";

echo " 
<center>
  <a href=\"?status=".STATE_NEW."\">New reports</a> | <a href=\"?status=".STATE_FIXED."\">Fixed reports</a> | <a href=\"?status=".STATE_INVALID."\">Invalid reports</a>
</center>";

if (!isset($_GET[status])) {
	$status = STATE_NEW;
} else {
	$status = $_GET[status];
}

display_versions_table();
if (isset($_GET[v])) {
	display_crashes($status);
} else {
	display_versions_pie_chart();
	display_crashes_vs_date_per_version($_GET[package]);
}

mysql_close();

?>
<p>&nbsp;</p>

