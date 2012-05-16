<?php

include "mysql.php";
include "crashes.php";

// HTML Headers
echo implode('', file('html_head'));

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

display_versions();
display_crashes($status);
mysql_close();

?></body>
</html>
