<?php
require 'vendor/autoload.php';

use serpframework\frontend\ExportHandler;
use serpframework\frontend\MainHandler;
use serpframework\persistence\DatabaseHandler;
use serpframework\persistence\Models\DataPoint;
use serpframework\persistence\Models\Participant;

error_reporting(-1);
ini_set('display_errors', '1');
$f3 = \Base::instance();

// display the nth page
$f3->route('GET /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayPage();
    }
);

// store date for nth page and redirect to next one
$f3->route('POST /',
    function($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->storeData();
        $f3->reroute('/');
    }
);

$f3->route('GET /dump/@store',
    function($f3) {
        $store = $f3->get('PARAMS.store');
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        var_dump($databaseHandler->{$store}->findAll());
        echo "</pre>";
    }
);

$f3->route('GET /clearStore/@store',
    function($f3) {
        $store = $f3->get('PARAMS.store');
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        var_dump($databaseHandler->{$store}->deleteStore());
        echo "</pre>";
    }
);


$f3->route('GET /clearStores',
    function($f3) {
        echo "<pre>";
        echo "COMMENT IN TO NOT ACCIDENTALLY DELETE DATA";
        $databaseHandler = new DatabaseHandler();
        $databaseHandler->participantsStore->deleteStore();
        $databaseHandler->answerStore->deleteStore();
        $databaseHandler->dataPointStore->deleteStore();
        $databaseHandler->participantDataPointStore->deleteStore();
        echo "</pre>";
    }
);

$f3->route('GET /storeDataPoint/@key/@value',
    function($f3) {
        $value = $f3->get('PARAMS.value');
        $key = $f3->get('PARAMS.key');
        $databaseHandler = new DatabaseHandler();
        $dataPoint = new DataPoint($value, $key);
        $participant = $databaseHandler->findParticipant('GNWJsBGspYEQCIMJKW18kmpP');

        $databaseHandler->storeParticipantDataPoint($participant, $dataPoint);
    }
);


$f3->route('GET /export/@type',
    function($f3) {
        $exportHandler = new ExportHandler();
        $type = $f3->get('PARAMS.type');
        if($type == 'answers') {
            $exportHandler->exportAnswers();
        }
        if($type == 'dataPoints') {
            $exportHandler->exportUserDataPoints();
        }
    }
);

$f3->run();