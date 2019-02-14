<?php

include("./obj/mysql_vars.php");
include("./obj/mysql_func.php");

$mysql_db_conn = mysqli_connect($mysql_db_server, $mysql_db_username, $mysql_db_password);

if (!$mysql_db_conn) {
	echo $utils_line_separator;
	die("Connection failed: " . mysqli_connect_error());
	echo $utils_line_separator;
}

if (!mysqli_select_db($mysql_db_conn, $mysql_db_db)) {
	echo $utils_line_separator;
	echo "database_selection failed<br>";
	echo $utils_line_separator;
}
