<?php


namespace Frame;


class FunctionMax {
    protected $args_expr = null;
    protected $argc = 1;

    public function getDescription() {
        return array(
            "Field"     => "MAX",
            "Type"      => "int(11)",
            "Null"      => "NO",
            "Key"       => "",
            "Default"   => null,
            "Extra"     => ""
        );
    }

    public function getSkeleton() {
        return array(
            "pre"       => "MAX(",
            "arg"       => 0,
            "post"      => ")"
        );
    }
}