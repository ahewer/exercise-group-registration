<?php
header("Content-Type: text/html; charset=utf-8");

function __autoload($class_name) {
    include 'lib/' . $class_name . '.inc.php';
}

Init::output();

$builder = new Builder();
$builder->build();

?>
