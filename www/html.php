<?php

function show_output() {
	$html = ob_get_clean();
	$template = file_get_contents($_SERVER[DOCUMENT_ROOT]."./template.html");

	$regex = "#^(.*)/".$_GET[package]."#";
	if ($_GET[package] && preg_match($regex, $_SERVER[REQUEST_URI], $matches)) {
		$root = "/".$matches[1];
	} else if (preg_match("#(.*)/([^\.]+)\.php#", $_SERVER[REQUEST_URI], $matches)) {
		$root = $matches[1];
	} else {
		$root = "/";
	}

	$patt = array("%ROOT%", "%HTML%", "%PACKAGE_URI%");
	$repl = array($root, $html, APP_PACKAGE_URI);
	echo str_replace($patt, $repl, $template);
}

ob_start();
register_shutdown_function("show_output");

?>
