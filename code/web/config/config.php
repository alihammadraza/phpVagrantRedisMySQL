<?php
/**
 * Created by PhpStorm.
 * User: hammad
 * Date: 12/18/2015
 * Time: 1:30 AM
 */

ini_set("display_errors", "1");
error_reporting(E_ALL);

$settings=parse_ini_file('config.ini');
foreach($settings as $key=>$value){
    define($key,$value);
}

function __autoload($class){
    $classPath=str_replace('\\', '/',$class);
    $classPath= dirname(__FILE__).'/../../'.$classPath.'.php';
    require_once($classPath);
}
