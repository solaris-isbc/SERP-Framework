<?php
require 'vendor/autoload.php';

use serpframework\config\Configuration;

$f3 = \Base::instance();
$f3->route('GET /',
    function() {
        $config = new Configuration(dirname(__FILE__) . '/resources/config.json');
    }
);
$f3->run();