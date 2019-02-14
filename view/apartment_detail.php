<a href=../><</a><br>
<?php

$apartments = mysqli_query($mysql_db_conn, "SELECT * FROM apartment WHERE apartment_id = '". $_GET["sub"] . "';");

while ($apartment = mysqli_fetch_assoc($apartments)) {
	$pictures = explode(";", $apartment["pictures"]);
	foreach ($pictures as $picture) {
		if ($picture == "") continue;
		?>
		<img src=".<?php echo $picture; ?>" width=600><br>
		<?php
	}
	?>
	<h2><b><?php echo $apartment["title"]; ?></b></h2><br>
	<?php echo $apartment["street"]; ?><br>
	<?php echo $apartment["zipcode"]; ?> <?php echo $apartment["city"]; ?><br><br>
	<table border=0>
		<tr><td><?php echo $apartment["rent"]; ?> &euro;</td><td><?php echo $apartment["room_count"]; ?></td><td><?php echo $apartment["living_area"]; ?> m&sup2;</td></tr>
		<tr><td>Kaltmiete</td><td>Zi.</td><td>Fläche</td></tr>
	</table><br>
	<table border=0>
		<tr><td>Typ:</td>     		<td><?php echo $apartment["type"]; ?></td>     							<td>Zimmer</td>   	<td><?php echo $apartment["room_count"]; ?></td></tr>
		<tr><td>Etage:</td>		<td><?php echo $apartment["floor"]; ?> von <?php echo $apartment["floor_total"]; ?></td>	<td>Schlafzimmer</td>	<td><?php echo $apartment["sleeping_room_count"]; ?></td></tr>
		<tr><td>Wohnfläche ca.:</td>    <td><?php echo $apartment["living_area"]; ?> m&sup2;</td>     					<td>Badezimmer</td>   <td><?php echo $apartment["bath_room_count"]; ?></td></tr>
		<tr><td>Bezugsfrei ab:</td>    	<td><?php echo $apartment["rentable_start"]; ?></td>     					<td>Haustiere</td>   <td><?php echo $apartment["pets"]; ?></td></tr>
	</table>
	<h3>Kosten<br></h3>
	<table border=0>
		<tr><td>Kaltmiete:</td>		<td><?php echo $apartment["rent"]; ?> &euro;</td>									<td>Kaution:</td>	<td><?php echo $apartment["deposit"]/$apartment["rent"]; ?> Kaltmonatsmieten</td></tr>
		<tr><td>Nebenkosten:</td>       <td><?php echo $apartment["utilities"]; ?> &euro;</td>									<td></td>		<td></td></tr>
		<tr><td>Heizkosten:</td>        <td><?php echo $apartment["heating_costs"]; ?> &euro;</td>								<td></td>               <td></td></tr>
		<tr><td>Gesamtmiete:</td>       <td><b><?php echo ($apartment["rent"]+$apartment["utilities"]+$apartment["heating_costs"]); ?> &euro;</b></td>     	<td></td>               <td></td></tr>
	</table>
	<a href="../request/<?php echo $_GET["sub"]; ?>">put request</a>
	<?php
}
?>
