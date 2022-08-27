<?php
require 'vendor/autoload.php';

use serpframework\frontend\MainHandler;

error_reporting(-1);
ini_set('display_errors', '1');
$f3 = \Base::instance();

// display the nth page
$f3->route('GET /page/@page',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $page = intval($f3->get('PARAMS.page'));
        $mainHandler->displayPage($page);
    }
);

// store date for nth page and redirect to next one
$f3->route('POST /page/@page',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $page = intval($f3->get('PARAMS.page'));

//        $mainHandler->storeData();

        $f3->reroute('/page/' . ($page + 1));
    }
);

$f3->route('GET /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayEntryPage();
    }
);
$f3->run();