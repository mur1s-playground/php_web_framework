<?php

$utils_line_separator = "------------------------------------------------------------------------------------<br>";

function utils_starts_with( $str, $sub ) {
    return ( substr( $str, 0, strlen( $sub ) ) == $sub );
}

function utils_ends_with( $str, $sub ) {
    return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

?>
