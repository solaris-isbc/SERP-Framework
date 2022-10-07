<?php

require 'vendor/autoload.php';

use serpframework\frontend\ExportHandler;
use serpframework\frontend\MainHandler;
use serpframework\frontend\AdminHandler;
use serpframework\persistence\DatabaseHandler;
use serpframework\persistence\Models\DataPoint;
use serpframework\persistence\Models\Participant;

$f3 = \Base::instance();

// display admin overview
$f3->route(
    'GET /administration',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $adminHandler->displayAdminMainPage();
    }
);

// store admin voerview data
$f3->route(
    'POST /administration',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $sections = $f3->get('POST.sections');
        $header = $f3->get('POST.header');
        $adminHandler->storeMainConfiguration($sections, $header);
        $adminHandler->displayAdminMainPage();
    }
);


// admin login check
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

// main participant view
$f3->route(
    'GET /forbidden',
    function ($f3) {
        $mainHandler = new MainHandler($f3);
        $mainHandler->displayForbiddenPage();
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

// configuration overview page for system 
$f3->route(
    'POST /systemConfiguration/@system',
    function ($f3) {
        $adminHandler = new AdminHandler($f3);
        $systemId = $f3->get('PARAMS.system');
       
        $jsonConfig = $f3->get('POST.jsonConfig');
        $templateConfig = $f3->get('POST.templateConfig');
        $cssConfig = $f3->get('POST.cssConfig');
        
        $adminHandler = new AdminHandler($f3);
        $adminHandler->setSystemId($systemId);
        $adminHandler->storeSystemConfiguration($jsonConfig, $templateConfig, $cssConfig);

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


$f3->run();
