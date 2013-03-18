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

