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

function show_output() {
	$html = ob_get_clean();
	$template = file_get_contents($_SERVER[DOCUMENT_ROOT]."/template.html");

	$regex = "#^(.*)/".$_GET[package]."#";
	if ($_GET[package] && preg_match($regex, $_SERVER[REQUEST_URI], $matches)) {
		$root = "/".$matches[1];
	} else if (preg_match("#(.*)/([^\.]+)\.php#", $_SERVER[REQUEST_URI], $matches)) {
		$root = $matches[1];
	} else {
		$root = "/";
	}

	$patt = array("%ROOT%", "%HTML%", "%APP_PACKAGE_URI%");
	$repl = array($root, $html, APP_PACKAGE_URI);
	echo str_replace($patt, $repl, $template);
}

ob_start();
register_shutdown_function("show_output");

?>
