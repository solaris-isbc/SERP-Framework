<?php

namespace serpframework\persistence;

class DatanaseHandler
{
    private $databasePath;

    // create stores for datan
    //$newsStore = new \SleekDB\Store("news", $databaseDirectory);


    public function __construct()
    {
        $this->databasePath = __DIR__ . "/../../serp_database";

    }
}