<?php
require 'vendor/autoload.php';

use serpframework\frontend\MainHandler;
use serpframework\persistence\DatabaseHandler;

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

        $mainHandler->storeData();

        $f3->reroute('/page/' . ($page + 1));
    }
);

$f3->route('GET /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayEntryPage();
    }
);


$f3->route('GET /dump/@store',
    function($f3) {
        $store = $f3->get('PARAMS.store');
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        // $databaseHandler->participantsStore->deleteStore();
        // $databaseHandler->answerStore->deleteStore();
        // $databaseHandler->dataPointStore->deleteStore();

        var_dump($databaseHandler->{$store}->findAll());

        echo "</pre>";
    }
);


$f3->route('GET /clearStores',
    function($f3) {
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        $databaseHandler->participantsStore->deleteStore();
        $databaseHandler->answerStore->deleteStore();
        $databaseHandler->dataPointStore->deleteStore();
        echo "</pre>";
    }
);

$f3->route('GET /dbtest',
    function($f3) {
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        $databaseHandler->getSystemUserCounts();
        echo "</pre>";
    }
);


$f3->run();