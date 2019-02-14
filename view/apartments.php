<?php

$apartments = mysqli_query($mysql_db_conn, "SELECT * FROM apartment;");

while ($apartment = mysqli_fetch_assoc($apartments)) {
	if (isset($isAdmin)) {
		?>
                <a href="admin.php?action=apartment_edit&apartment_id=<?php echo $apartment["apartment_id"]; ?>"><table border=1 width=600><tr><td>
                <?php
	} else {
		?>
		<a href="./apartment/<?php echo $apartment["apartment_id"]; ?>"><table border=1 width=600><tr><td>
		<?php
	}
	?>
	<h3><b><?php echo $apartment["title"]; ?></b></h3>
	<table border=0><tr><td>
	<?php
	$pictures = explode(";", $apartment["pictures"]);
	foreach ($pictures as $picture) {
		if ($picture == "") continue;
		?>
		<img src="<?php echo $picture; ?>" width=300><br>
		<?php
		break;
	}
	?>
	</td><td>
	<?php echo $apartment["street"]; ?><br>
	<?php echo $apartment["zipcode"]; ?> <?php echo $apartment["city"]; ?><br><br>
	<table border=0>
		<tr><td><?php echo $apartment["rent"]; ?> &euro;</td><td><?php echo $apartment["room_count"]; ?></td><td><?php echo $apartment["living_area"]; ?> m&sup2;</td></tr>
		<tr><td>Kaltmiete</td><td>Zi.</td><td>Fläche</td></tr>
	</table><br>

	</td></tr></table>

	</td></tr></table></a><br>
	<?php
}
?>
