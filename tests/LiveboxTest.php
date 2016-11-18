<?php

require __DIR__.'/../vendor/autoload.php';

use Alc\Livebox\Livebox;
use Alc\Curl\Curl;

$api = new Livebox(new Curl(), '192.168.1.1');
$api->login('admin', 'admin');

print_r($api->userManagementGetUsers());
print_r($api->getInfoDSL());
print_r($api->getDSLStats());
print_r($api->getWANStatus());
