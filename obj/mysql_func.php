<?php

function mysql_func_database_create() {
	global $mysql_db_conn;
	global $mysql_db_db;
        global $utils_line_separator;
        $database_create_str = "CREATE DATABASE IF NOT EXISTS " . $mysql_db_db . ";";
        echo $utils_line_separator;
        echo "trying to create database " . $mysql_db_db ."<br>creation ";
        if (mysqli_query($mysql_db_conn, $database_create_str)) {
                echo "successful";
        } else {
                echo "failed";
        }
        echo "<br>";
        echo $utils_line_separator;
}

function mysql_func_database_drop() {
	global $mysql_db_conn;
        global $mysql_db_db;
        global $utils_line_separator;
        $database_drop_str = "DROP DATABASE IF EXISTS " . $mysql_db_db . ";";
        echo $utils_line_separator;
        echo "trying to drop database " . $mysql_db_db ."<br>drop ";
        if (mysqli_query($mysql_db_conn, $database_drop_str)) {
                echo "successful";
        } else {
                echo "failed";
        }
        echo "<br>";
        echo $utils_line_separator;
}

function mysql_func_tables_create() {
	global $mysql_db_conn;
        global $utils_line_separator;
        $files = scandir("./tables/");
        foreach ($files as $filename) {
                if (utils_ends_with($filename, ".tbl.php")) {
                        $tablename = substr($filename, 0, strlen($filename)-8);
                        echo $utils_line_separator;
                        echo "trying to read table " . $tablename . "<br>";
                        include("./tables/" . $filename);
                        echo "read " . $tablename . " successfully<br>";
                        $tables_create_str =    "CREATE TABLE IF NOT EXISTS " . $tablename . " (";
                        $tables_create_str .=    $tablename . "_id INT AUTO_INCREMENT,";
                        foreach ($tables_fields as $field_name => $field) {
				if ($field["type"] == "hash") {
					$tables_create_str .= $field_name . " varchar(250) NOT NULL,";
				} else {
	                                $tables_create_str .= $field_name . " " . $field["type"] . " " . $field["flags"] . ",";
				}
                        }
                        $tables_create_str .= "PRIMARY KEY (" . $tablename ."_id)) ENGINE=INNODB;";
                        echo $tables_create_str ."<br>";
                        echo "trying to create it<br>creation ";
                        if (mysqli_query($mysql_db_conn, $tables_create_str)) {
                                echo "successful";
                        } else {
                                echo "failed";
                        }
                        echo "<br>";
			echo $utils_line_separator;
                }
        }
}

function mysql_func_tables_drop() {
        global $mysql_db_conn;
        global $utils_line_separator;
        $files = scandir("./tables/");
        foreach ($files as $filename) {
                if (utils_ends_with($filename, ".tbl.php")) {
                        $tablename = substr($filename, 0, strlen($filename)-8);
                        echo $utils_line_separator;
                        echo "trying to read table " . $tablename . "<br>";
                        include("./tables/" . $filename);
                        echo "read " . $tablename . " successfully<br>";
                        $tables_drop_str =    "DROP TABLE " . $tablename . ";";
                        echo "trying to drop it<br>drop ";
                        if (mysqli_query($mysql_db_conn, $tables_drop_str)) {
                                echo "successful";
                        } else {
                                echo "failed";
                        }
                        echo "<br>";
                        echo $utils_line_separator;
                }
        }
}

function mysql_func_insertform_create($tablename) {
	global $utils_line_separator;
	echo $utils_line_separator;
	include("./tables/" . $tablename . ".tbl.php");
	?>
	<table border=0>
	<tr><td>&nbsp;</td><td><b>create <?php echo $tablename; ?></b></td></tr>

	<form action="admin.php?action=table_insert&table=<?php echo $tablename; ?>" method="post">
	<?php
	foreach ($tables_fields as $field_name => $field) {
		if (utils_starts_with($field["type"], "varchar")) {
			?>
			<tr><td>
				<?php echo $field_name; ?>
			</td><td>
				<input type="text" name="<?php echo $field_name; ?>" size="35" /><br>
			</td></tr>
			<?php
		} else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
			?>
			<tr><td>
	                        <?php echo $field_name; ?>
			</td><td>
				<input type="text" name="<?php echo $field_name; ?>" size="5" /><br>
			</td></tr>
                        <?php
		} else if (utils_starts_with($field["type"], "float")) {
                        ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>" size="5" /><br>
                        </td></tr>
                        <?php
		} else if (utils_starts_with($field["type"], "date")) {
	                ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>_day" size="2" />
				<input type="text" name="<?php echo $field_name; ?>_month" size="2" />
				<input type="text" name="<?php echo $field_name; ?>_year" size="4" />
                        </td></tr>
                        <?php
		} else if (utils_starts_with($field["type"], "blob")) {
                       ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <textarea rows="4" cols="50" name="<?php echo $field_name; ?>"></textarea>
                        </td></tr>
                        <?php
		} else if (utils_starts_with($field["type"], "hash")) {
			//ignore
		}
	}
	?>
	<tr><td>
		<input type="reset" />
	</td><td>
		<input type="submit" />
	</td></tr>
	</form>
	</table>
	<?php
	echo $utils_line_separator;
}

function mysql_func_editform_create($tablename, $edit_id) {
	global $mysql_db_conn;
        global $utils_line_separator;
        echo $utils_line_separator;
        include("./tables/" . $tablename . ".tbl.php");
	$tablerows = mysqli_query($mysql_db_conn, "SELECT * FROM `" . $tablename . "` WHERE `" . $tablename . "_id` = " . $edit_id . ";");
	$tablerow = mysqli_fetch_assoc($tablerows);
        ?>
        <table border=0>
        <tr><td>&nbsp;</td><td><b>edit <?php echo $tablename; ?></b></td></tr>

        <form action="admin.php?action=table_edit&table=<?php echo $tablename; ?>&<?php echo $tablename . "_id=" . $edit_id; ?>" method="post">
        <?php
        foreach ($tables_fields as $field_name => $field) {
                if (utils_starts_with($field["type"], "varchar")) {
                        ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>" size="35" value="<?php echo $tablerow[$field_name];?>" /><br>
                        </td></tr>
                        <?php
                } else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
                        ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>" size="5" value="<?php echo $tablerow[$field_name]; ?>" /><br>
                        </td></tr>
                        <?php
                } else if (utils_starts_with($field["type"], "float")) {
                        ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>" size="5" value="<?php echo $tablerow[$field_name] ;?>"/><br>
                        </td></tr>
                        <?php
                } else if (utils_starts_with($field["type"], "date")) {
                        ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <input type="text" name="<?php echo $field_name; ?>_day" size="2" value="<?php echo substr($tablerow[$field_name], 8, 2); ?>" />
                                <input type="text" name="<?php echo $field_name; ?>_month" size="2" value="<?php echo substr($tablerow[$field_name], 5, 2); ?>" />
                                <input type="text" name="<?php echo $field_name; ?>_year" size="4" value="<?php echo substr($tablerow[$field_name], 0, 4); ?>" />
                        </td></tr>
                        <?php
                } else if (utils_starts_with($field["type"], "blob")) {
                       ?>
                        <tr><td>
                                <?php echo $field_name; ?>
                        </td><td>
                                <textarea rows="4" cols="50" name="<?php echo $field_name; ?>"><?php echo $tablerow[$field_name]; ?></textarea>
                        </td></tr>
                        <?php
                } else if (utils_starts_with($field["type"], "hash")) {
                        //ignore
                }

        }
        ?>
        <tr><td>
                <input type="reset" />
        </td><td>
                <input type="submit" />
        </td></tr>
        </form>
        </table>
        <?php
        echo $utils_line_separator;
}

function mysql_func_table_insert($tablename) {
	global $mysql_db_conn;
	global $utils_line_separator;
	echo $utils_line_separator;

	$insertion_hash = array();

	include("./tables/" . $tablename . ".tbl.php");
	$tables_insert_str =    "INSERT INTO `" . $tablename . "` (";
//        $tables_insert_str .=    "`" . $tablename . "_id`,";
        foreach ($tables_fields as $field_name => $field) {
        	$tables_insert_str .= "`" . $field_name . "`,";
        }
	$tables_insert_str = substr($tables_insert_str, 0, strlen($tables_insert_str)-1);
        $tables_insert_str .= ") VALUES (";
        foreach ($tables_fields as $field_name => $field) {
		if (utils_starts_with($field["type"], "varchar")) {
	                $tables_insert_str .= "'" . $_POST[$field_name] . "',";
		} else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
			$tables_insert_str .= $_POST[$field_name] . ",";
		} else if (utils_starts_with($field["type"], "float")) {
			$tables_insert_str .= $_POST[$field_name] . ",";
		} else if (utils_starts_with($field["type"], "date")) {
			$tables_insert_str .= $_POST[$field_name . "_year"] . $_POST[$field_name . "_month"] . $_POST[$field_name . "_day"] . ",";
		} else if (utils_starts_with($field["type"], "blob")) {
			$tables_insert_str .= "'" . $_POST[$field_name] . "',";
		} else if (utils_starts_with($field["type"], "hash")) {
			$insertion_hash[0] = sha1($tables_insert_str . time());
                        $tables_insert_str .= "'"  . $insertion_hash[0] . "',";
                }
        }
	$tables_insert_str = substr($tables_insert_str, 0, strlen($tables_insert_str)-1);
	$tables_insert_str .= ");";
        echo $tables_insert_str ."<br>";
        echo "trying to insert it<br>insertion ";
        if (mysqli_query($mysql_db_conn, $tables_insert_str)) {
        	echo "successful";
       	} else {
                echo "failed";
        }
	echo "<br>";
	echo $utils_line_separator;
	return $insertion_hash;
}

function mysql_func_table_edit($tablename, $edit_id) {
	global $mysql_db_conn;
        global $utils_line_separator;
        echo $utils_line_separator;

        include("./tables/" . $tablename . ".tbl.php");
        $tables_edit_str =    "UPDATE `" . $tablename . "` SET ";
        foreach ($tables_fields as $field_name => $field) {
		if ($field["type"] != "hash") {
                	$tables_edit_str .= "`" . $field_name . "` = ";
		}

		if (utils_starts_with($field["type"], "varchar")) {
                        $tables_edit_str .= "'" . $_POST[$field_name] . "',";
                } else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
                        $tables_edit_str .= $_POST[$field_name] . ",";
                } else if (utils_starts_with($field["type"], "float")) {
                        $tables_edit_str .= $_POST[$field_name] . ",";
                } else if (utils_starts_with($field["type"], "date")) {
                        $tables_edit_str .= $_POST[$field_name . "_year"] . $_POST[$field_name . "_month"] . $_POST[$field_name . "_day"] . ",";
                } else if (utils_starts_with($field["type"], "blob")) {
                        $tables_edit_str .= "'" . $_POST[$field_name] . "',";
                }

        }
        $tables_edit_str = substr($tables_edit_str, 0, strlen($tables_edit_str)-1);
	$tables_edit_str .= " WHERE `" . $tablename . "_id` = " . $edit_id . ";";
        echo $tables_edit_str ."<br>";
        echo "trying to edit it<br>edit ";
        if (mysqli_query($mysql_db_conn, $tables_edit_str)) {
                echo "successful";
        } else {
                echo "failed";
        }
        echo "<br>";
        echo $utils_line_separator;
}

function mysql_func_table_to_session($tablename) {
        include("./tables/" . $tablename . ".tbl.php");

        $i = 0;
        while (isset($_SESSION[$tablename . "_id[" . $i . "]"])) { $i++; }
        $session_idx = $tablename . "_id[" . $i . "]";

        foreach ($tables_fields as $field_name => $field) {
                if (utils_starts_with($field["type"], "varchar")) {
                        $_SESSION[$session_idx][$field_name] = $_POST[$field_name];
                } else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
                        $_SESSION[$session_idx][$field_name] = $_POST[$field_name];
                } else if (utils_starts_with($field["type"], "float")) {
                        $_SESSION[$session_idx][$field_name] = $_POST[$field_name];
                } else if (utils_starts_with($field["type"], "date")) {
                        $_SESSION[$session_idx][$field_name . "_year"] = $_POST[$field_name . "_year"];
                        $_SESSION[$session_idx][$field_name . "_month"] = $_POST[$field_name . "_month"];
                        $_SESSION[$session_idx][$field_name . "_day"] = $_POST[$field_name . "_day"];
                } else if (utils_starts_with($field["type"], "blob")) {
                        $_SESSION[$session_idx][$field_name] = $_POST[$field_name];
                }
        }
}

function mysql_func_table_insert_from_session($tablename) {
	global $mysql_db_conn;
	include("./tables/" . $tablename . ".tbl.php");

	$insertion_hashes = array();

	$i = 0;
        while (isset($_SESSION[$tablename . "_id[" . $i . "]"])) {
		$session_idx = $tablename . "_id[" . $i . "]";

        	$tables_insert_str =    "INSERT INTO `" . $tablename . "` (";
	        foreach ($tables_fields as $field_name => $field) {
        	        $tables_insert_str .= "`" . $field_name . "`,";
	        }
        	$tables_insert_str = substr($tables_insert_str, 0, strlen($tables_insert_str)-1);
	        $tables_insert_str .= ") VALUES (";
	        foreach ($tables_fields as $field_name => $field) {
        	        if (utils_starts_with($field["type"], "varchar")) {
	                        $tables_insert_str .= "'" .  $_SESSION[$session_idx][$field_name] . "',";
       	        	} else if (utils_starts_with($field["type"], "tinyint") || utils_starts_with($field["type"], "int")) {
                        	$tables_insert_str .=  $_SESSION[$session_idx][$field_name] . ",";
	                } else if (utils_starts_with($field["type"], "float")) {
        	                $tables_insert_str .=  $_SESSION[$session_idx][$field_name] . ",";
                	} else if (utils_starts_with($field["type"], "date")) {
                        	$tables_insert_str .=  $_SESSION[$session_idx][$field_name . "_year"] . $_SESSION[$session_idx][$field_name . "_month"] . $_SESSION[$session_idx][$field_name . "_day"] . ",";
	                } else if (utils_starts_with($field["type"], "blob")) {
        	                $tables_insert_str .= "'" .  $_SESSION[$session_idx][$field_name] . "',";
                	} else if (utils_starts_with($field["type"], "hash")) {
				$insertion_hashes[$i] = sha1($tables_insert_str . time());
	                        $tables_insert_str .= "'"  . $insertion_hashes[$i] . "',";
			}
	        }
        	$tables_insert_str = substr($tables_insert_str, 0, strlen($tables_insert_str)-1);
	        $tables_insert_str .= ");";
        	echo $tables_insert_str ."<br>";
	        echo "trying to insert it<br>insertion ";
	        if (mysqli_query($mysql_db_conn, $tables_insert_str)) {
        	        echo "successful";
	        } else {
        	        echo "failed";
	        }

		$i++;
	}
	return $insertion_hashes;
}

function mysql_func_get_row_ids_from_insertion_hash($tablename, $insertion_hashes) {
	global $mysql_db_conn;
	$select_str = "SELECT `" . $tablename . "_id` FROM `". $tablename . "` WHERE ";
	foreach ($insertion_hash as $hash) {
		$select_str .= "`insertion_hash` = '" . $hash . "' OR ";
	}
	$select_str = substr($select_str, 0, strlen($select_str)-4) . ";";

	$row_ids = mysqli_query($mysql_db_conn, $select_str);
	$result = array();
	while ($row_id = mysqli_fetch_assoc($row_ids)) {
		$result[] = $row_id[$tablename . "_id"];
	}
	return $result;
}
