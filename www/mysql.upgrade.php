<?php

$DB_UPGRADE_1_2 = <<<SQL
ALTER TABLE {$table_prefix}crashes
ADD (`application_log` text NOT NULL DEFAULT '',
`media_codec_list` text NOT NULL DEFAULT '',
`thread_details` text NOT NULL DEFAULT '',
`user_ip` text NOT NULL DEFAULT '')
SQL;

define('DB_VERSION_FILE', "../db.version");
define('DB_CURRENT_VERSION', 2);

// ----------------------------------------------------------------------------

// Check DB version
$dbv = @file_get_contents(DB_VERSION_FILE);
if ($dbv === false) {
        $version = 1;
} else {
        $version = 0 + $dbv;
}

// Upgrade, if needed
if ($version < 2) {
	mysql_query($DB_UPGRADE_1_2);
}

// Save new version
$file = fopen(DB_VERSION_FILE, "w");
fprintf($file, "%d", DB_CURRENT_VERSION);
fclose($file);

