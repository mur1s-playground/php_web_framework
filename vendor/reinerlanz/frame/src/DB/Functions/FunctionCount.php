<?php


namespace Frame;


class FunctionCount {
    protected $args_expr = null;
    protected $argc = 1;

    public function getDescription() {
        return array(
            "Field"     => "COUNT",
            "Type"      => "int(11)",
            "Null"      => "NO",
            "Key"       => "",
            "Default"   => null,
            "Extra"     => ""
        );
    }

    public function getSkeleton() {
        return array(
            "pre"       => "COUNT(",
            "arg"       => 0,
            "post"      => ")"
        );
    }
}