<?php
namespace Ip2Location;

class Autoloader
{
    public static function load()
    {
        $classmap = require 'classmap.php';
        
        foreach (array_keys($classmap) as $class) {
            
            include_once $classmap[$class];
        }
    }
}