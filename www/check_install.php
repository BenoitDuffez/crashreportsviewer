<?php

if (!file_exists($_SERVER[DOCUMENT_ROOT]."/.htaccess") || !file_exists($_SERVER[DOCUMENT_ROOT]."/../config.php")) {
	echo '<p class="error">Project setup is not complete.<br />-&gt; <a href="install.php">Go to installation</a>?</p>';
}

?>
