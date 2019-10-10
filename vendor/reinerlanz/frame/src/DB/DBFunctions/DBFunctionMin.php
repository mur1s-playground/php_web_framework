<?php


namespace Frame;


class DBFunctionMin {
    protected $args_expr = null;
    protected $argc = 1;

    public function getDescription() {
        return array(
            "Field"     => "MIN",
            "Type"      => "int(11)",
            "Null"      => "NO",
            "Key"       => "",
            "Default"   => null,
            "Extra"     => ""
        );
    }

    public function getSkeleton() {
        return array(
            "pre"       => "MIN(",
            "arg"       => 0,
            "post"      => ")"
        );
    }
}