<?php

if ($_GET["sub"] == "add") {
	include("./view/prospective_tenant_add.php");
} else if ($_GET["sub"] == "save") {
	include("./view/prospective_tenant_save.php");
}

?>
