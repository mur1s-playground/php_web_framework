<?php
if (isset($_GET["sub"]) && $_GET["sub"] == "save") {
	include("./view/request_save.php");
} else if (isset($_GET["sub"])) {
	$_SESSION["apartment_ids[0]"] = $_GET["sub"];
}

$i = 0;
while (isset($_SESSION["prospective_tenant_id[" . $i . "]"])) {
	echo $_SESSION["prospective_tenant_id[" . $i . "]"]["name"] . ", " . $_SESSION["prospective_tenant_id[" . $i . "]"]["prename"] . "<br>";
	$i++;
}
?>
<br><a href="../prospective_tenant/add">add prospective_tenant</a><br>

<form action="../request/save" method="post">
<table border=0>
	<tr><td>apartment_ids</td>		<td><textarea name="apartment_ids"><?php
		$i = 0;
		while(isset($_SESSION["apartment_ids[" . $i . "]"])) {
			echo $_SESSION["apartment_ids[" . $i . "]"];
			$i++;
		}
											?></textarea></td></tr>
	<tr><td>Mietbeginn:</td>		<td><input type="text" name="rent_start_day" size="2" /> <input type="text" name="rent_start_month" size="2" /> <input type="text" name="rent_start_year" size="4" /></td></tr>
	<tr><td>Anzahl Nutzer Mietobjekt:</td>  <td><input type="text" name="user_count" /></td></tr>
	<tr><td></td>                           <td><input type="submit"></td></tr>
</table>
</form>
<br><br><br>
