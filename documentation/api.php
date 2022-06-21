<?php
require($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");

spl_autoload_register('autoloader');

function autoloader(string $name) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'].'/model/'.$name.'.php')){
        require_once $_SERVER['DOCUMENT_ROOT'].'/model/'.$name.'.php';
    }
}
$openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/model']);
header('Content-Type: application/json; charset=utf-8');
echo $openapi->toJSON();
?>