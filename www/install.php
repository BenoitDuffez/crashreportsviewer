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
include "crashes.php";
include "html.php";

if (file_exists(HTACCESS_FILE) && file_exists(CONFIG_FILE)) {
	echo '<div class="ok">Installation is already done.</div>';
	exit;
}

$host = isset($_POST['host']) ? $_POST['host'] : "localhost";
$username = isset($_POST['username']) ? $_POST['username'] : "username";
$password = isset($_POST['password']) ? $_POST['password'] : "password";
$database = isset($_POST['database']) ? $_POST['database'] : "database";
$table_prefix = isset($_POST['table_prefix']) ? $_POST['table_prefix'] : "acra_";
$table = $table_prefix . ( isset($_POST['table']) ? $_POST['table'] : "crashes" );

if (!isset($_POST['submit'])) {
	$show_form = true;
} else {
	$show_form = false;
	
	$mysql = mysql_connect($_POST['host'], $_POST['username'], $_POST['password']);
	if (!$mysql) {
		$show_form = true;
		echo '<div class="error">Unable to connect to mysql server. Check host, user name and password</div>';
	} else {
		echo '<div class="ok">Connected to mysql server, logged in using '.$_POST['username'].'</div>';
		
		if (!mysql_select_db($_POST['database'])) {
			$show_form = true;
			echo '<div class="error">Unable to select database. Check that you have created the database `'.$_POST['database'].'`.</div>';
		} else {
			echo '<div class="ok">Selected the  database `'.$_POST['database'].'`.</div>';
			
			// Write config.php
			$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/../config.php", "w");
			if (!$file) {
				echo '<div class="error">Unable to create `config.php`. Check file/folder permissions.</div>';
			} else {
				fprintf($file, "<?php

\$mysql_server = '$host';
\$mysql_user = '$username';
\$mysql_password = '$password';
\$mysql_db = '$database';
\$table_prefix = '$table_prefix';

?>");
				fclose($file);
				echo '<div class="ok">Wrote `config.php`.</div>';

				// Create table
				$sql = <<<SQL_CREATE
CREATE TABLE IF NOT EXISTS `{$table_prefix}crashes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `added_date` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `issue_id` varchar(32) NOT NULL,
  `report_id` text NOT NULL,
  `app_version_code` text NOT NULL,
  `app_version_name` text NOT NULL,
  `package_name` text NOT NULL,
  `file_path` text NOT NULL,
  `phone_model` text NOT NULL,
  `android_version` text NOT NULL,
  `build` text NOT NULL,
  `brand` text NOT NULL,
  `product` text NOT NULL,
  `total_mem_size` int(11) NOT NULL,
  `available_mem_size` int(11) NOT NULL,
  `custom_data` text NOT NULL,
  `stack_trace` text NOT NULL,
  `initial_configuration` text NOT NULL,
  `crash_configuration` text NOT NULL,
  `display` text NOT NULL,
  `user_comment` text NOT NULL,
  `user_app_start_date` text NOT NULL,
  `user_crash_date` text NOT NULL,
  `dumpsys_meminfo` text NOT NULL,
  `dropbox` text NOT NULL,
  `logcat` text NOT NULL,
  `eventslog` text NOT NULL,
  `radiolog` text NOT NULL,
  `is_silent` text NOT NULL,
  `device_id` text NOT NULL,
  `installation_id` text NOT NULL,
  `user_email` text NOT NULL,
  `device_features` text NOT NULL,
  `environment` text NOT NULL,
  `settings_system` text NOT NULL,
  `settings_secure` text NOT NULL,
  `shared_preferences` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8

SQL_CREATE;
				$res = mysql_query($sql);
				if (!$res) {
					echo '<div class="error">Create table failed.</p>';
				} else {
					echo '<div class="ok">Created the table</div>';
				}
				// .htaccess
				$file = fopen( $_SERVER['DOCUMENT_ROOT'] . "/.htaccess", "w");
				if (!$file) {
					echo '<div class="error">Unable to create `.htaccess`. Check file/folder permissions.</div>';
				} else {
					fwrite($file, $_POST['htaccess']);
					fclose($file);
					echo '<div class="ok">Wrote `.htaccess`.</div>';
				}
			}
		}
	}
}

if ($show_form) { 
?>
		<form method="post" action="install.php" class="form">
			<h1>.htaccess</h1>
			<p>Create your .htaccess file here (remember to update the path to your passwords file):</p>
			<div><textarea name="htaccess" cols="100" rows="15">
AuthUserFile <?php echo $_SERVER['DOCUMENT_ROOT']; ?>/crashes.htpasswd
AuthGroupFile /dev/null
AuthName "Oh hai."
AuthType Basic

&lt;Limit GET POST&gt;
require valid-user
&lt;/Limit&gt;

Options +FollowSymlinks
RewriteCond %{REQUEST_URI} ^/dist.* [NC]
RewriteRule .* - [L]

RewriteEngine on
RewriteRule ^([^/]+)/report/([0-9]+)/field/([^/]+)/?$      field.php?package=$1&id=$2&field=$3 [QSA]
RewriteRule ^([^/]+)/issue/([a-z0-9]+)/?$                  report.php?package=$1&issue_id=$2 [QSA]
RewriteRule ^([^/]+)/([^/]+)/([0-9]+)/?$                   $2.php?package=$1&v=$3 [QSA]
RewriteRule ^([^/]+)/([^/]+)/?$                            $2.php?package=$1 [QSA]
</textarea>

			<h1>MySQL server connection</h1>
			<p>Server host:<br />
				<input type="text" name="host" value="<?php echo $host; ?>" /></p>
			<p>Server username:<br />
				<input type="text" name="username" value="<?php echo $username; ?>" /></p>
			<p>Server password:<br />
				<input type="text" name="password" value="<?php echo $password; ?>" /></p>
			<p>Server database name:<br />
				<input type="text" name="database" value="<?php echo $database; ?>" /></p>
			<p>Table prefix:<br />
				<input type="text" name="table_prefix" value="<?php echo $table_prefix; ?>" /></p>
			<?php /*<p>Table name:<br />
				<input type="text" name="table" value="<?php echo $table; ?>" /></p>*/ ?>

			<h1>Go!</h1>
			<p><input type="submit" name="submit" value="Process installation" /></p>
		</form>
<?php
}

?>
