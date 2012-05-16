<?php

include "crashes.php";
include "mysql.php";

// HTML Headers
echo implode('', file('html_head'));

display_crashes_vs_date();

?>