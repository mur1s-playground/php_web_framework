<?php

$tables_fields = array(
	"title"				=> array("type" => "varchar(250)", 	"flags" => "NOT NULL"),
	"street" 			=> array("type" => "varchar(250)", 	"flags" => "NOT NULL"),
	"zipcode" 			=> array("type" => "int(5)", 		"flags" => "NOT NULL"),
	"city" 				=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"living_area" 			=> array("type" => "tinyint(4)",      	"flags" => "NOT NULL"),
	"built_in_kitchen" 		=> array("type" => "tinyint(1)",      	"flags" => "NOT NULL"),
	"built_in_kitchen_comment"	=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"type"				=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"floor"				=> array("type" => "tinyint(2)",      	"flags" => "NOT NULL"),
	"floor_total"			=> array("type" => "tinyint(2)",        "flags" => "NOT NULL"),
	"room_count"			=> array("type" => "tinyint(2)",	"flags" => "NOT NULL"),
	"sleeping_room_count"		=> array("type" => "tinyint(2)",        "flags" => "NOT NULL"),
	"bath_room_count"           	=> array("type" => "tinyint(2)",        "flags" => "NOT NULL"),
	"rentable_start"		=> array("type" => "date",		"flags" => "NOT NULL"),
	"pets"				=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"rent"				=> array("type" => "float(7, 2)",      	"flags" => "NOT NULL"),
	"utilities"			=> array("type" => "float(7, 2)",     	"flags" => "NOT NULL"),
	"heating_costs"			=> array("type" => "float(7, 2)",      	"flags" => "NOT NULL"),
	"deposit"			=> array("type" => "float(7, 2)",      	"flags" => "NOT NULL"),
//bausubstanz & energieausweis


	"pictures"			=> array("type" => "blob", 		"flags" => "NOT NULL"),
	"visible"			=> array("type" => "tinyint(1)",        "flags" => "NOT NULL"),
);

