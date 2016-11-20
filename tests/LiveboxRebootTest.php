<?php

require __DIR__.'/../vendor/autoload.php';

use Alc\Livebox\Livebox;
use Alc\Curl\Curl;

$api = new Livebox(new Curl(), '192.168.1.1');
$api->login('admin', 'admin');

print_r($api->reboot());
