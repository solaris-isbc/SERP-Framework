<?php

require 'vendor/autoload.php';

use serpframework\frontend\ExportHandler;
use serpframework\frontend\MainHandler;
use serpframework\frontend\AdminHandler;
use serpframework\persistence\DatabaseHandler;
use serpframework\persistence\Models\DataPoint;
use serpframework\persistence\Models\Participant;

error_reporting(-1);
ini_set('display_errors', '1');
$f3 = \Base::instance();

// store date for nth page and redirect to next one
$f3->route(
    'GET /administration',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $adminHandler->displayAdminMainPage();
    }
);

// store date for nth page and redirect to next one
$f3->route(
    'POST /administration',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $sections = $f3->get('POST.sections');
        $adminHandler->storeMainConfiguration($sections);
        $adminHandler->displayAdminMainPage();
    }
);


// store date for nth page and redirect to next one
$f3->route(
    'POST /login',
    function ($f3) {
        $user = $f3->get('POST.username');
        $pass = $f3->get('POST.password');

        $adminHandler = new AdminHandler($f3);
        $success = $adminHandler->validateLogin($user, $pass);
        if ($success) {
            $f3->reroute('/administration');
        } else {
            $adminHandler->displayLoginPage(true);
        }
    }
);

// container page for preview
$f3->route(
    'GET /showSystem/@system',
    function ($f3) {
        $systemId = $f3->get('PARAMS.system');

        $adminHandler = new AdminHandler($f3);
        $adminHandler->setSystemId($systemId);

        $adminHandler->displaySerpPreview();
    }
);

// actual output for preview page
$f3->route(
    'GET /preview/@system',
    function ($f3) {
        $systemId = $f3->get('PARAMS.system');

        $adminHandler = new AdminHandler($f3);
        $adminHandler->setSystemId($systemId);

        $adminHandler->echoSystemPreview();
    }
);

// main participant view
$f3->route(
    'GET /',
    function ($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayPage();
    }
);

// store date for nth page and redirect to next one
$f3->route(
    'POST /',
    function ($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->storeData();
        $f3->reroute('/');
    }
);

// admin page for export
$f3->route(
    'GET /export',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $adminHandler->displayExportPage();
    }
);

// configuration overview page for system 
$f3->route(
    'GET /system/@system',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $systemId = $f3->get('PARAMS.system');

        $adminHandler = new AdminHandler($f3);
        $adminHandler->setSystemId($systemId);

        $adminHandler->showSystemOptions();
    }
);

// configuration overview page for system 
$f3->route(
    'GET /systemConfiguration/@system',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $systemId = $f3->get('PARAMS.system');

        $adminHandler = new AdminHandler($f3);
        $adminHandler->setSystemId($systemId);

        $adminHandler->showSystemConfiguration();
    }
);

// file export page
$f3->route(
    'GET /export/@type',
    function ($f3) {
        $exportHandler = new ExportHandler();
        $type = $f3->get('PARAMS.type');
        if ($type == 'answers') {
            $exportHandler->exportAnswers();
        }
        if ($type == 'dataPoints') {
            $exportHandler->exportUserDataPoints();
        }
    }
);

// store a single data point
$f3->route(
    'GET /storeDataPoint/@participant/@key/@value',
    function ($f3) {
        $value = $f3->get('PARAMS.value');
        $key = $f3->get('PARAMS.key');
        $participant = $f3->get('PARAMS.participant');
        $databaseHandler = new DatabaseHandler();
        $dataPoint = new DataPoint($value, $key);
        $participant = $databaseHandler->findParticipant($participant);
        $databaseHandler->storeParticipantDataPoint($participant, $dataPoint);
    }
);




// HELPER-ROUTES

$f3->route(
    'GET /dump/@store',
    function ($f3) {
        $store = $f3->get('PARAMS.store');
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        var_dump($databaseHandler->{$store}->findAll());
        echo "</pre>";
    }
);

$f3->route(
    'GET /clearStore/@store',
    function ($f3) {
        $store = $f3->get('PARAMS.store');
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        var_dump($databaseHandler->{$store}->deleteStore());
        echo "</pre>";
    }
);


$f3->route(
    'GET /clearStores',
    function ($f3) {
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



$f3->run();
