<?php
require 'vendor/autoload.php';

use serpframework\frontend\MainHandler;

error_reporting(-1);
ini_set('display_errors', '1');
$f3 = \Base::instance();

$f3->route('GET /page/@page',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $page = intval($f3->get('PARAMS.page'));
        $mainHandler->displayPage($page);
    }
);

$f3->route('GET /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayEntryPage();
    }
);
$f3->run();