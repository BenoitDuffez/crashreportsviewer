<?php

include "html.php";
include "mysql.php";
include "crashes.php";

// Search form
echo '<form method="get" action="reports.php">'."\n";
echo 'Filter by phone_model: <input type="text" name="q" value="'.$_GET[q].'" /> <input type="submit" value="Search" />'."\n";
echo "</form>\n";

echo '<center><a href="?status='.STATE_NEW.'">New reports</a> | <a href="?status='.STATE_FIXED.'">Fixed reports</a> | <a href="?status='.STATE_INVALID.'">Invalid reports</a></center>'."\n";

if (!isset($_GET[status])) {
	$status = STATE_NEW;
} else {
	$status = $_GET[status];
}

//dbug(__FILE__, __LINE__);
display_versions();

display_crashes_vs_date_per_version($_GET[package]);

//dbug(__FILE__, __LINE__);
display_crashes($status);
//dbug(__FILE__, __LINE__);
mysql_close();
//dbug(__FILE__, __LINE__);

?>
