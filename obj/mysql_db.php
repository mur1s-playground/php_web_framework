<?php

include("./obj/mysql_vars.php");
include("./obj/mysql_func.php");

$mysql_db_conn = mysqli_connect($mysql_db_server, $mysql_db_username, $mysql_db_password, $mysql_db_db);

if (!$mysql_db_conn) {
    die("Connection failed: " . mysqli_connect_error());
}
