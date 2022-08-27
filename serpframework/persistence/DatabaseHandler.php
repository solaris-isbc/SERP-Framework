<?php

namespace serpframework\persistence;

use serpframework\persistence\models\Participant;
use SleekDB\Store;

class DatabaseHandler
{
    private $databasePath;

    // create stores for datan
    public $participantsStore;

    public function __construct()
    {
        $this->databasePath = __DIR__ . "/../../serp_database";
        $this->participantsStore = new Store("participants", $this->databasePath, [
            "timeout" => false,
        ]);
    }

    public function addParticipant($participant)
    {
        $this->participantsStore->insert($participant->getDbRepresentation());
    }

    public function findParticipant($id) {
        $data = $this->participantsStore->findOneBy(["id", "=", $id]);
        $participant = new Participant();
        $participant->fillFromDbRepresentation($data);
        return $participant;
    }

    public function fetchAllParticipants() {
        return $this->participantsStore->findAll();
    }

    public function getSystemUserCounts() {
        $participants = $this->participantsStore->findAll();
        if(!$participants) {
            return [];
        }

        // TODO: join it to only select ones, which also have no answers for the users

        $participants = array_map(function($data) {
            $participant = new Participant();
            $participant->fillFromDbRepresentation($data);
            return $participant;
        }, $participants);
        $counts = [];
        foreach($participants as $participant) {
            if(!isset($counts[$participant->getSystemId()])) {
                $participants[$participant->getSystemId()] = 0;
            }
            $participants[$participant->getSystemId()] = $participants[$participant->getSystemId()] + 1;
        }
        return $counts;
    }
}
