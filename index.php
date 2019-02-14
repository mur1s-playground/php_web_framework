<?php
session_start();
?>

<body>
<?php

include("obj/utils.php");
include("obj/mysql_db.php");

if (isset($_GET["action"])) {
	if ($_GET["action"] == "apartment") {
		include("view/apartment_detail.php");
	} else if ($_GET["action"] == "request") {
		include("view/request.php");
	} else if ($_GET["action"] == "prospective_tenant") {
		include("view/prospective_tenant.php");
	} else {
		include("view/apartments.php");
	}
} else {
	include("view/apartments.php");
}
?>
</body>
