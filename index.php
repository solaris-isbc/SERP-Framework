<?php
require 'vendor/autoload.php';

use serpframework\frontend\MainHandler;
use serpframework\persistence\DatabaseHandler;

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
        echo "COMMENT IN TO NOT ACCIDENTALLY DELETE DATA";
        $databaseHandler = new DatabaseHandler();
        $databaseHandler->participantsStore->deleteStore();
        $databaseHandler->answerStore->deleteStore();
        $databaseHandler->dataPointStore->deleteStore();
        echo "</pre>";
    }
);

$f3->route('GET /export',
    function($f3) {
        echo "<pre>";
        $databaseHandler = new DatabaseHandler();
        $databaseHandler->exportData();
        echo "</pre>";
    }
);

/*
//resources/system-1/documents/s1/s1.html"

$f3->route('GET /jsongenerator',
    function($f3) {
        $mainDir = dirname(__FILE__) . '/resources/system-1/documents/';
        $dirs = [
            "b1",
            "b2",
            "b3",
            "b4",
            "b5",
            "b6",
            "b7",
            "b8",
            "b9",
            "b10",
            "s1",
            "s2",
            "s3",
            "s4",
            "s5",
            "s6",
            "s7",
            "s8",
            "s9",
        ];
        foreach($dirs as $md) {
            $dir = new \DirectoryIterator($mainDir . $md);
            foreach ($dir as $fileinfo) {
                if (!$fileinfo->isDot()) {
                    var_dump(strval($fileinfo));
                    if(str_contains(strval($fileinfo), '.html')) {
                        continue;
                    }
                    $filename = $md . '.html';
                    $content = "<html><body><div style='text-align: center;'><img src='/resources/system-1/documents/" . $md . '/' . strval($fileinfo) ."'/></div></body></html>";
                    echo "'/resources/system-1/documents/" . $md . '/' . strval($filename);
                    file_put_contents($mainDir . $md . '/' . $filename, $content);
                }
            }
        }
       
        echo "<pre>";
  
        
        echo "</pre>";
    }
);
*/


$f3->run();