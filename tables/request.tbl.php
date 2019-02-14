<?php

$tables_fields = array(
	"apartment_ids"			=> array("type" => "blob",		"flags" => "NOT NULL"),
	"rent_start"			=> array("type" => "date", 		"flags" => "NOT NULL"),
	"prospective_tenant_ids"	=> array("type" => "blob",      	"flags" => "NOT NULL"),
	"user_count"			=> array("type" => "tinyint(2)",	"flags" => "NOT NULL"),
	"clearance_for_showing"		=> array("type" => "tinyint(1)",      	"flags" => "NOT NULL"),
	"clearance_for_rent"		=> array("type" => "tinyint(1)",        "flags" => "NOT NULL"),
	"request_hash"			=> array("type" => "varchar(250)", 	"flags" => "NOT NULL"),
);
