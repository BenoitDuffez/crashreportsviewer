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

$max = 0 + $_GET[max];
if ($max > 0 && $max < 1000) {
	define('MAX_REPORTS', 0 + $_GET[max]);
} else {
	define('MAX_REPORTS', 20);
}

include "html.php";
include "mysql.php";
include "crashes.php";

function summaryLine($name, $value) {
	echo "<tr><th>";
	echo $name;
	echo "</th><td>";
	echo $value;
	echo "</td></tr>\n";
}

function detailsLink($tab, $field, $name, $end, $field_alias = null) {
	if($tab[$field] != "") {
		echo '<a href="javascript:void(0);" onclick="setDetailsContent(';
		echo  $tab[id];
		echo ", '";
		echo $field_alias != null ? $field_alias : $field;
		echo "'";
		echo ')">';
		echo $name;
		echo '</a>';
		if($end) {
			echo " | ";
		}
	}
}

function showReport($tab) {
	echo '<div id="report_' . $tab[id] . '" class="report">'."\n";
	echo "<h1>Report #".$tab[id]."</h1>\n";
	echo '<div id="summary_' . $tab[id] . '" class="summary">';
	echo '<table>';
	summaryLine("Report #", $tab[id]);
	$added = '';
	if (intval($tab[added_date]) > 0) {
		$added = date('d/M/Y G:i:s', intval($tab[added_date]));
	} else {
		$added = "Date unknown";
	}
	summaryLine("Added", $added);
	$status = "";
	if (intval($tab['status']) == STATE_FIXED) {
		$status = 'fixed';
	} else {
		$status = 'new';
	}
	summaryLine("Status", $status);
	summaryLine("Report ID", $tab['report_id']);
	summaryLine("Issue ID", $tab['issue_id']);
	$device = $tab['brand'] . ' ' . $tab['phone_model'] . ' (' . $tab['product'] . ')'. " Running Android " . $tab['android_version'];
	summaryLine("Device", $device);
	summaryLine("Package", $tab['package_name']);
	$version = $tab['app_version_name'] . ' (' . $tab['app_version_code'] . ')';
	summaryLine("Version", $version);
	summaryLine("File Path", $tab['file_path']);
	summaryLine("Total Mem", intval(intval($tab['total_mem_size']) / 1024 / 1024) . "M");
	summaryLine("Available Mem", intval(intval($tab['available_mem_size']) / 1024 / 1024) . "M");
	summaryLine("User App Start Date", $tab['user_app_start_date']);
	summaryLine("User Crash Date", $tab['user_crash_date']);
	summaryLine("Is Silent", $tab['is_silent']);
	summaryLine("Device Id", $tab['device_id']);
	summaryLine("Installation Id", $tab['installation_id']);
	summaryLine("User Email", $tab['user_email']);
	echo '</table>';
	echo "</div>";
	echo "<div id='details_" . $tab['id'] . "' style='float:right; width:70%'>";
	echo "<div id='details_header_" . $tab['id'] . "'>";
	echo '<span>';
	detailsLink($tab, 'stack_trace', 'Overview', True, 'overview');
	detailsLink($tab, 'stack_trace', 'Stack Trace', True);
	detailsLink($tab, 'custom_data', 'Custom Data', True);
	detailsLink($tab, 'build', 'Build', True);
	detailsLink($tab, 'initial_configuration', 'Initial Config', True);
	detailsLink($tab, 'crash_config', 'Crash Config', True);
	detailsLink($tab, 'display', 'Display', True);
	detailsLink($tab, 'user_comment', 'User Comment', True);
	detailsLink($tab, 'dumpsys_meminfo', 'Dumpsys Meminfo', True);
	detailsLink($tab, 'dropbox', 'Dropbox', True);
	detailsLink($tab, 'logcat', 'Logcat', True);
	detailsLink($tab, 'eventslog', 'Events Log', True);
	detailsLink($tab, 'radiolog', 'Radio Log', True);
	detailsLink($tab, 'device_features', 'Device Features', True);
	detailsLink($tab, 'environment', 'Environment', True);
	detailsLink($tab, 'settings_system', 'System Settings', True);
	detailsLink($tab, 'settings_secure', 'Secure Settings', True);
	detailsLink($tab, 'shared_preferences', 'Shared Preferences', False);
	echo '</span>';
	echo "</div>";
	echo "<div id='details_content_" . $tab['id'] . "'><pre>";
        echo str_replace("<li></li>", "", "<ul><li>".str_replace("<br />", "</li><li>", bicou_stack_trace_overview($tab[stack_trace], null))."</li></ul>");
	echo "</pre></div>";
	echo "</div>\n";
	echo "</div>\n";
}

// Show button
$buttons = <<<HTML
<div style="float: right; margin-right: 100px;">
  <a class="button" href="javascript:setStatusAndGo('issue_id', 'STATE_FIXED', '../reports/');">mark as fixed</a>
  <a class="button" href="javascript:setStatusAndGo('issue_id', 'STATE_INVALID', '../reports/');">mark as invalid</a>
  <a class="button" href="javascript:setStatusAndGo('issue_id', 'STATE_NEW', '../reports/');">mark as new</a>
</div>
<div style="display: block"><p>&nbsp;</p></div>
HTML;
echo str_replace(array("STATE_FIXED", "STATE_INVALID", "STATE_NEW", "issue_id"),
		 array(STATE_FIXED,    STATE_INVALID,   STATE_NEW,  $_GET['issue_id']),
		 $buttons);

// Display Android Versions pie chart
display_crashes_vs_android_versions_pie_chart($_GET[issue_id]);
display_crashes_vs_app_versions_pie_chart($_GET[issue_id]);

// Display reports
$sql = bicou_mysql_select(null, "crashes", "issue_id = ?", array($_GET[issue_id]));
$sql .= " LIMIT 0, ".MAX_REPORTS;
$res = mysql_query($sql);

if (!$res) {
	bicou_log("Unable to query: $sql");
	echo "<p>Server error.</p>\n";
	return;
}

echo "<p>".mysql_num_rows($res)." crashes match the issue ID #".$_GET[issue_id]."</p>\n";
$first = true;
while ($tab = mysql_fetch_assoc($res)) {
	if ($first) {
		$first = false;
	} else {
		echo '<hr style="height: 2px;color: #EEEEEE; width: 95%; clear:both;"/>';
	}
	showReport($tab);
}

?></body>
</html>
