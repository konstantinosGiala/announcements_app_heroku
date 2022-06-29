<?php
// require($_SERVER['DOCUMENT_ROOT']."/vendor/autoload.php");
require(dirname(__FILE__,2)."/vendor/autoload.php");

spl_autoload_register('autoloader');

function autoloader(string $name) {
    // if (file_exists($_SERVER['DOCUMENT_ROOT'].'/model/'.$name.'.php')){
    //     require_once $_SERVER['DOCUMENT_ROOT'].'/model/'.$name.'.php';
    // }
    if (file_exists(dirname(__FILE__,2).'/model/'.$name.'.php')){
        require_once dirname(__FILE__,2).'/model/'.$name.'.php';
    }
}
// $openapi = \OpenApi\Generator::scan([$_SERVER['DOCUMENT_ROOT'].'/model']);
$openapi = \OpenApi\Generator::scan([dirname(__FILE__,2).'/model']);
header('Content-Type: application/json; charset=utf-8');
echo $openapi->toJSON();
?>