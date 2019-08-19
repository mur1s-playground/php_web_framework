<?php

namespace Frame;

class Order {
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";

    private $class = "";
    private $field = "";
    private $ordering = "";

    public function __construct($class, $field, $ordering) {
        $this->class = $class;
        $this->field = $field;
        $this->ordering = $ordering;
    }

    public function getClass() {
        return $this->class;
    }

    public function getField() {
        return $this->field;
    }

    public function getOrdering() {
        return $this->ordering;
    }
}
