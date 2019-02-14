<?php
session_start();
?>
<body>
<?php

include("./obj/utils.php");
include("./obj/mysql_db_admin.php");

$isAdmin = "true";

if (isset($_GET["action"])) {
	$action = $_GET["action"];
	if ($action == "database_create") {
		mysql_func_database_create();
	} else if ($action == "database_drop") {
		mysql_func_database_drop();
	} else if ($action == "tables_create") {
		mysql_func_tables_create();
	} else if ($action == "tables_drop") {
		mysql_func_tables_drop();
	} else if ($action == "apartment_create") {
		mysql_func_insertform_create("apartment");
	} else if ($action == "apartment_edit") {
		if (isset($_GET["apartment_id"])) {
			mysql_func_editform_create("apartment", $_GET["apartment_id"]);
		}
	} else if ($action == "table_insert") {
		if (isset($_GET["table"])) {
			mysql_func_table_insert($_GET["table"]);
		}
	} else if ($action == "table_edit") {
		if (isset($_GET["table"]) && isset($_GET[$_GET["table"] . "_id"])) {
			mysql_func_table_edit($_GET["table"], $_GET[$_GET["table"] . "_id"]);
		}
	} else if ($action == "session_clear") {
		session_unset();
	}
} else {
	include("./view/apartments.php");
}
?>
<a href=admin.php>reload</a><br><br>

<a href=admin.php?action=database_create>create database</a> | <a href=admin.php?action=database_drop>drop database</a><br><br>

<a href=admin.php?action=tables_create>create tables</a> | <a href=admin.php?action=tables_drop>drop tables</a><br><br>

<a href=admin.php?action=apartment_create>create apartment</a><br><br>

<a href=admin.php?action=session_clear>clear session</a><br>

</body>
