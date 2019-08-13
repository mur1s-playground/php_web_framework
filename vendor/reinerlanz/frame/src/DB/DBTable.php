<?php

namespace Frame;

class DBTable {
    protected $DBO = null;
    protected $table_name = null;
    protected $fields = null;
    protected $resultSet = null;

    public function __construct($table_name, $fields, $values = null) {
        $this->DBO = $GLOBALS['Boot']->getDBO();
        $this->table_name = $table_name;
        $this->fields = json_decode($fields, true);
        foreach ($this->fields as $field_name_camel => $field) {
            $child_setter_function = "set{$field_name_camel}";
            if (!is_null($values) && array_key_exists($field_name_camel, $values)) {
                $this->$child_setter_function($values[$field_name_camel]);
            } else {
                $this->$child_setter_function($field['Default']);
            }
        }
    }

    public function insert() {
        $query = "INSERT INTO `{$this->table_name}` (";
        foreach ($this->fields as $field_name_camel => $field) {
            if ($field['Extra'] == "auto_increment") continue;
            $query .= "`{$field['Field']}`,";
        }
        $query = rtrim($query, ',');
        $query .= ") VALUES (";

        $error = array();

        foreach ($this->fields as $field_name_camel => $field) {
            if ($field['Extra'] == "auto_increment") continue;

            $child_getter_function = "get{$field_name_camel}";
            $value = $this->$child_getter_function();

            if (is_null($value)) {
                if ($field['Null'] == 'NO') {
                    $error[] = "{$field['Field']} = NULL not allowed for insertion in {$this->table_name}";
                }
                $query .= 'NULL,';
                continue;
            }

            if (strpos($field['Type'], "char") !== false || strpos($field['Type'], "text") !== false) {
                $query .= "'{$value}',";
            } else {
                $query .= "{$value},";
            }
        }
        $query = rtrim($query, ',');
        $query .= ");";

        if (sizeof($error) > 0) {
            die(json_encode(array('error' => $error), JSON_PRETTY_PRINT));
        }

        $this->DBO->query($query);
    }

    public function find($conditions = null, $joins = null) {
        $query = "SELECT * FROM `{$this->table_name}`";
        $error = array();
        if (!is_null($conditions)) {
            $where = " WHERE ";
            $where_count = sizeof($conditions);
            $where_loop_count = 0;
            foreach ($conditions as $column => $value) {
                if (array_key_exists($column, $this->fields)) {
                    $where_loop_count++;
                    $field = $this->fields[$column];
                    $where .= "`{$field['Field']}` = ";
                    if (strpos($field['Type'], "char") !== false || strpos($field['Type'], "text") !== false) {
                        $where .= "'{$value}'";
                    } else {
                        $where .= "{$value}";
                    }
                    if ($where_loop_count < $where_count) {
                        $where .= " AND ";
                    }
                } else {
                    $error[] = "unknown column {$column} for {$this->table_name}";
                }
            }
            $query .= $where;
        }
        $query .= ";";

        if (sizeof($error) > 0) {
            die(json_encode(array('error' => $error)));
        }

        $this->resultSet = $this->DBO->query($query);
    }

    public function next() {
        if (!is_null($this->resultSet) && ($row = mysqli_fetch_assoc($this->resultSet)) != null) {
            foreach ($this->fields as $field_name_camel => $field) {
                $child_setter_function = "set{$field_name_camel}";
                $this->$child_setter_function($row[$field['Field']]);
            }
            return true;
        }
        return false;
    }
}