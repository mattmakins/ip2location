<?php

use Ip2Location\Autoloader;
use Ip2Location\Ip2Location;

include_once __DIR__ . '/src/autoloader.php';

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    include_once 'vendor/autoload.php';
}

Autoloader::load();

$ip2location = new Ip2Location(array(
    'db_host' => 'localhost',
    'db_user' => 'ip2location',
    'db_pass' => 'pass1234',
));

// Should return MY
echo $ip2location->getCountry('202.186.13.4');