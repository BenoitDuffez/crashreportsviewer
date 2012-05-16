<?php

include "html.php";
include "mysql.php";
include "crashes.php";

function showReport($tab) {
	echo "<h1>Report #".$tab['id']."</h1>\n";
	echo '<div style="margin: 45px;">'."\n";
	foreach ($tab as $k => $v) {
		if ($k == "id") {
			continue;
		} else if ($k == "added_date") {
			if (intval($v) > 0) {
				$v = date('d/M/Y G:i:s', intval($v));
			} else {
				$v = "Date unknown";
			}
		} else if ($k == "status") {
			if (intval($v) == STATE_FIXED) {
				$v = 'fixed';
			} else {
				$v = 'new';
			}
		}

		echo "<h2>$k</h2>\n<pre>$v</pre>\n";
	}
	echo "</div>\n";
}

// Show button
echo '<div style="float: right; margin-right: 100px;">';
echo '<a class="button" href="javascript:setStatusAndGo(\''.$_GET['issue_id'].'\', \''.STATE_FIXED.'\', \'/reports.php\');">mark as fixed</a> ';
echo '<a class="button" href="javascript:setStatusAndGo(\''.$_GET['issue_id'].'\', \''.STATE_INVALID.'\', \'/reports.php\');">mark as invalid</a> ';
echo '<a class="button" href="javascript:setStatusAndGo(\''.$_GET['issue_id'].'\', \''.STATE_NEW.'\', \'/reports.php\');">mark as new</a> ';
echo "</div>\n";

// Display reports
$sql = bicou_mysql_select(null, "issue_id = '?'", array($_GET[issue_id]));
$sql .= " LIMIT 0, 100";
$res = mysql_query($sql);

if (!$res) {
	bicou_log("Unable to query: $sql");
	echo "<p>Server error.</p>\n";
	return;
}

echo "<p>".mysql_num_rows($res)." crashes match the issue ID #".$_GET[issue_id]."</p>\n";
while ($tab = mysql_fetch_assoc($res)) {
	showReport($tab);
}

?></body>
</html>
