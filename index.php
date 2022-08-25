<?php
require 'vendor/autoload.php';

use serpframework\frontend\MainHandler;

error_reporting(-1);
ini_set('display_errors', '1');
$f3 = \Base::instance();
$f3->route('GET /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->handleEntry();
    }
);
$f3->run();