<?php

namespace Frame;

class DBTable {
    protected $DBO = null;

    protected $table_name = null;
    protected $fields = null;
    protected $joins = null;

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

            if ($this->isTextType($field['Type'])) {
                $sanitised_value = $this->DBO->real_escape_string($value);
                $query .= "'{$sanitised_value}',";
            } else {
                if (!is_numeric($value)) {
                    $error[] = "non numeric value {$value} for {$this->table_name}.{$field['Field']}";
                }
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

    public function find($condition = null, $joins = null) {
        $error = array();

        $query = "SELECT *";

        $query_join = "";
        if (!is_null($joins)) {
            if (!is_array($joins)) {
                $joins = [$joins];
            }

            $this->joins = $joins;

            /*
            $join_table_counter = 0;
            foreach ($this->joins as $join) {
                //$query .= ",`frame_join_{$join_table_counter}`.*";
                $join_table_counter++;
            }
            */

            $join_table_counter = 0;
            foreach ($this->joins as $join) {
                $query_join .= " {$join->getJoinType()} `{$join->getModel()->table_name}` AS `frame_join_{$join_table_counter}` ON ";
                $join_expr = $join->getExpr();

                $placeholders = array();
                preg_match_all("/\[[A-Za-z0-9_]+\]/", $join_expr, $placeholders, PREG_OFFSET_CAPTURE);

                $join_expr_array = array();

                $expr_cur_pos = 0;
                foreach ($placeholders[0] as $placeholder_arr) {
                    $placeholder_name = $placeholder_arr[0];
                    $placeholder_pos = $placeholder_arr[1];
                    if ($expr_cur_pos < $placeholder_pos) {
                        $join_expr_array[] = substr($join_expr, $expr_cur_pos, $placeholder_pos - $expr_cur_pos);
                        $expr_cur_pos = $placeholder_pos;
                    }
                    $join_expr_array[] = substr($join_expr, $expr_cur_pos, strlen($placeholder_name));
                    $expr_cur_pos += strlen($placeholder_name);
                }

                for ($i = 0; $i < sizeof($join_expr_array); $i++) {
                    if (array_key_exists($join_expr_array[$i], $join->getValueArray())) {
                        $replacement = $join->getValueArray()[$join_expr_array[$i]];

                        if ($replacement[0][0] == get_class($this)) {
                            $field = $this->getFields()[$replacement[0][1]];
                            $join_expr_array[$i] = "`frame_maintable`.`{$field['Field']}`";
                        } else if ($replacement[0][0] == Condition::CONDITION_CONST) {
                            if (is_numeric($replacement[0][1])) {
                                $join_expr_array[$i] = $replacement[0][1];
                            } else {
                                $join_expr_array[$i] = "'" . $this->DBO->real_escape_string($replacement[0][1]) . "'";
                            }
                        } else {
                            for ($j = 0; $j < sizeof($this->joins); $j++) {
                                if ($replacement[0][0] == get_class($this->joins[$j]->getModel())) {
                                    $field = $this->joins[$j]->getModel()->getFields()[$replacement[0][1]];
                                    $join_expr_array[$i] = "`frame_join_{$j}`.`{$field['Field']}`";
                                    break;
                                }
                            }
                        }

                        $join_expr_array[$i] .= " {$replacement[1]} ";

                        if ($replacement[2][0] == get_class($this)) {
                            $field = $this->getFields()[$replacement[2][1]];
                            $join_expr_array[$i] .= "`frame_maintable`.`{$field['Field']}`";
                        } else if ($replacement[2][0] == Condition::CONDITION_CONST) {
                            if (is_numeric($replacement[2][1])) {
                                $join_expr_array[$i] .= $replacement[2][1];
                            } else {
                                $join_expr_array[$i] .= "'" . $this->DBO->real_escape_string($replacement[2][1]) . "'";
                            }
                        } else {
                            for ($j = 0; $j < sizeof($this->joins); $j++) {
                                if ($replacement[2][0] == get_class($this->joins[$j]->getModel())) {
                                    $field = $this->joins[$j]->getModel()->getFields()[$replacement[2][1]];
                                    $join_expr_array[$i] .= "`frame_join_{$j}`.`{$field['Field']}`";
                                    break;
                                }
                            }
                        }
                    }
                }
                $imploded_join = implode('', $join_expr_array);
                $query_join .= "({$imploded_join})";

                $join_table_counter++;
            }
        }

        $query .= " FROM `{$this->table_name}` AS `frame_maintable`";

        $query .= "{$query_join}";

        if (!is_null($condition)) {
            $where = " WHERE ";

            $condition_expr = $condition->getExpr();

            $placeholders = array();
            preg_match_all("/\[[A-Za-z0-9_]+\]/", $condition_expr, $placeholders, PREG_OFFSET_CAPTURE);

            $condition_expr_array = array();

            $expr_cur_pos = 0;
            foreach ($placeholders[0] as $placeholder_arr) {
                $placeholder_name = $placeholder_arr[0];
                $placeholder_pos = $placeholder_arr[1];
                if ($expr_cur_pos < $placeholder_pos) {
                    $condition_expr_array[] = substr($condition_expr, $expr_cur_pos, $placeholder_pos - $expr_cur_pos);
                    $expr_cur_pos = $placeholder_pos;
                }
                $condition_expr_array[] = substr($condition_expr, $expr_cur_pos, strlen($placeholder_name));
                $expr_cur_pos += strlen($placeholder_name);
            }

            for ($i = 0; $i < sizeof($condition_expr_array); $i++) {
                if (array_key_exists($condition_expr_array[$i], $condition->getValueArray())) {
                    $replacement = $condition->getValueArray()[$condition_expr_array[$i]];

                    if ($replacement[0][0] == get_class($this)) {
                        $field = $this->getFields()[$replacement[0][1]];
                        $condition_expr_array[$i] = "`frame_maintable`.`{$field['Field']}`";
                    } else if ($replacement[0][0] == Condition::CONDITION_CONST) {
                        if (is_numeric($replacement[0][1])) {
                            $condition_expr_array[$i] = $replacement[0][1];
                        } else {
                            $condition_expr_array[$i] = "'" . $this->DBO->real_escape_string($replacement[0][1]) . "'";
                        }
                    } else {
                        for ($j = 0; $j < sizeof($this->joins); $j++) {
                            if ($replacement[0][0] == get_class($this->joins[$j]->getModel())) {
                                $field = $this->joins[$j]->getModel()->getFields()[$replacement[0][1]];
                                $condition_expr_array[$i] = "`frame_join_{$j}`.`{$field['Field']}`";
                                break;
                            }
                        }
                    }

                    $condition_expr_array[$i] .= " {$replacement[1]} ";

                    if ($replacement[2][0] == get_class($this)) {
                        $field = $this->getFields()[$replacement[2][1]];
                        $condition_expr_array[$i] .= "`frame_maintable`.`{$field['Field']}`";
                    } else if ($replacement[2][0] == Condition::CONDITION_CONST) {
                        if (is_numeric($replacement[2][1])) {
                            $condition_expr_array[$i] .= $replacement[2][1];
                        } else {
                            $condition_expr_array[$i] .= "'" . $this->DBO->real_escape_string($replacement[2][1]) . "'";
                        }
                    } else {
                        for ($j = 0; $j < sizeof($this->joins); $j++) {
                            if ($replacement[2][0] == get_class($this->joins[$j]->getModel())) {
                                $field = $this->joins[$j]->getModel()->getFields()[$replacement[2][1]];
                                $condition_expr_array[$i] .= "`frame_join_{$j}`.`{$field['Field']}`";
                                break;
                            }
                        }
                    }
                }
            }
            $query .= $where . implode('', $condition_expr_array);
        }
        $query .= ";";

        if (sizeof($error) > 0) {
            die(json_encode(array('error' => $error)));
        }

        $this->resultSet = $this->DBO->query($query);
    }

    public function next() {
        if (!is_null($this->resultSet) && ($row = mysqli_fetch_row($this->resultSet)) != null) {
            $field_counter = 0;
            foreach ($this->fields as $field_name_camel => $field) {
                $child_setter_function = "set{$field_name_camel}";
                $this->$child_setter_function($row[$field_counter]);
                $field_counter++;
            }
            foreach ($this->joins as $join) {
                foreach ($join->getModel()->getFields() as $field_name_camel => $field) {
                    $child_setter_function = "set{$field_name_camel}";
                    $join->getModel()->$child_setter_function($row[$field_counter]);
                    $field_counter++;
                }
            }
            return true;
        }
        return false;
    }

    private function isTextType($type) {
        if (strpos($type, "char") !== false || strpos($type, "text") !== false) {
            return true;
        }
        return false;
    }

    public function getFields() {
        return $this->fields;
    }
}