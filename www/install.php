<?php

include "crashes.php";
include "html.php";

if (file_exists($_SERVER[DOCUMENT_ROOT]."/.htaccess") && file_exists($_SERVER[DOCUMENT_ROOT]."/config.php")) {
	echo '<div class="ok">Installation is already done.</div>';
	exit;
}

$host = isset($_POST[host]) ? $_POST[host] : "localhost";
$username = isset($_POST[username]) ? $_POST[username] : "username";
$password = isset($_POST[password]) ? $_POST[password] : "password";
$database = isset($_POST[database]) ? $_POST[database] : "database";
$table = isset($_POST[table]) ? $_POST[table] : "crashes";

if (!isset($_POST[submit])) {
	$show_form = true;
} else {
	$show_form = false;
	
	$mysql = mysql_connect($_POST[host], $_POST[username], $_POST[password]);
	if (!$mysql) {
		$show_form = true;
		echo '<div class="error">Unable to connect to mysql server. Check host, user name and password</div>';
	} else {
		echo '<div class="ok">Connected to mysql server, logged in using '.$_POST[username].'</div>';
		
		if (!mysql_select_db($_POST[database])) {
			$show_form = true;
			echo '<div class="error">Unable to select database. Check that you have created the database `'.$_POST[database].'`.</div>';
		} else {
			echo '<div class="ok">Selected the  database `'.$_POST[database].'`.</div>';
			
			// Write config.php
			$file = fopen("config.php", "w");
			if (!$file) {
				echo '<div class="error">Unable to create `config.php`. Check file/folder permissions.</div>';
			} else {
				fprintf($file, "<?php

\$mysql_server = '$host';
\$mysql_user = '$username';
\$mysql_password = '$password';
\$mysql_db = '$database';

?>");
				fclose($file);
				echo '<div class="ok">Wrote `config.php`.</div>';

				// .htaccess
				$file = fopen(".htaccess", "w");
				if (!$file) {
					echo '<div class="error">Unable to create `.htaccess`. Check file/folder permissions.</div>';
				} else {
					fprintf($file, $_POST[htaccess]);
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
			<div><textarea name="htaccess" cols="100" rows="15">AuthUserFile D:/www/crashreportsviewer/www/crashes.htpasswd
AuthGroupFile /dev/null
AuthName "Oh hai."
AuthType Basic

&lt;Limit GET POST&gt;
require valid-user
&lt;/Limit&gt;

Options +FollowSymlinks
RewriteEngine on
RewriteRule ^([^/]+)/([^\.]+).php       $2.php?package=$1 [QSA]
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
			<?php /*<p>Table name:<br />
				<input type="text" name="table" value="<?php echo $table; ?>" /></p>*/ ?>

			<h1>Go!</h1>
			<p><input type="submit" name="submit" value="Process installation" /></p>
		</form>
<?php
}

?>