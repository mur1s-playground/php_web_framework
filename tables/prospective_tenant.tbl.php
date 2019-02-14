<?php

$tables_fields = array(
	"name"				=> array("type" => "varchar(250)",	"flags" => "NOT NULL"),
	"prename"			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"birthdate"			=> array("type" => "date",      	"flags" => "NOT NULL"),
	"nationality"			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"street" 			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"zipcode" 			=> array("type" => "int(5)",      	"flags" => "NOT NULL"),
	"city" 				=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"phone"				=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"phone_2"                       => array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"email" 			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"occupation"	 		=> array("type" => "varchar(250)",     	"flags" => "NOT NULL"),
	"employer"			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"occupied_since"		=> array("type" => "date",	     	"flags" => "NOT NULL"),
	"children"			=> array("type" => "varchar(250)",     	"flags" => "NOT NULL"),
	"pets"				=> array("type" => "varchar(250)",     	"flags" => "NOT NULL"),
	"address_since"			=> array("type" => "date",		"flags" => "NOT NULL"),
	"reason_move"			=> array("type" => "varchar(250)",      "flags" => "NOT NULL"),
	"available_for_rent"		=> array("type" => "float(7, 2)",      	"flags" => "NOT NULL"),
	"insertion_hash"		=> array("type" => "hash",       	"flags" => "NOT NULL"),
);
