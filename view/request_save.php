<?php

	$insertion_hashes = mysql_func_table_insert_from_session("prospective_tenant");

	$prospective_tenant_ids = mysql_func_get_row_ids_from_insertion_hash("prospective_tenant", $insertion_hashes);

	$insert_str = "INSERT INTO `request` (apartment_ids, rent_start, prospective_tenant_ids, user_count, clearance_for_showing, clearance_for_rent, request_hash) VALUES ('";
	$i = 0;
	while (isset($_SESSION["apartment_ids[". $i . "]"])) {
		$insert_str .= $_SESSION["apartment_ids[". $i . "]"] . ";";
		$i++;
	}

	$insert_str = substr($insert_str, 0, strlen($insert_str)-1) . "',";

	$insert_str .= $_POST["rent_start_year"] . $_POST["rent_start_month"] . $_POST["rent_start_day"] . ",'";

	foreach ($prospective_tenant_ids as $prospective_tenant_id) {
		$insert_str .= $prospective_tenant_id . ";";
	}
	$insert_str = substr($insert_str, 0, strlen($insert_str)-1) . "',";

	$insert_str .= $_POST["user_count"] . ",";

	$insert_str .= "0, 0,";

	$request_hash = sha1($insert_str . time());
	$insert_str .= "'" . $request_hash . "');";
	echo $insert_str;

	mysqli_query($mysql_db_conn, $insert_str);
?>
