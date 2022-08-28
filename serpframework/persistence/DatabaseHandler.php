<?php

namespace serpframework\persistence;

use serpframework\persistence\models\Answer;
use serpframework\persistence\models\DataPoint;
use serpframework\persistence\models\Participant;
use SleekDB\Store;

class DatabaseHandler
{
    public const ANSWER_TYPE_SERP = "SERP";
    public const ANSWER_TYPE_QUESTIONNAIRE = "QUESTIONNAIRE";

    private $databasePath;

    // create stores for datan
    public $participantsStore;
    public $answerStore;
    public $dataPointStore;

    public function __construct()
    {
        $this->databasePath = __DIR__ . "/../../serp_database";
        $this->participantsStore = new Store("participants", $this->databasePath, [
            "timeout" => false,
        ]);
        $this->answerStore = new Store("answers", $this->databasePath, [
            "timeout" => false,
        ]);
        $this->dataPointStore = new Store("dataPoints", $this->databasePath, [
            "timeout" => false,
        ]);
    }

    public function addParticipant($participant)
    {
        $this->participantsStore->insert($participant->getDbRepresentation());
    }

    public function findParticipant($id) {
        $data = $this->participantsStore->findOneBy(["id", "=", $id]);
        // if participant somehow was not found
        if(!$data){
            return null;
        }
        $participant = new Participant();
        $participant->fillFromDbRepresentation($data);
        return $participant;
    }

    public function markCompleted(&$participant) {
        $participant->setCompleted();
        $this->participantsStore->update($participant->getDbRepresentation());
    }

    public function fetchAllParticipants() {
        return $this->participantsStore->findAll();
    }

    public function getSystemUserCounts() {
        $participants = $this->participantsStore->findAll();

        $answerStore = $this->answerStore;
        $participantsWithAnswers = $this->participantsStore
        ->createQueryBuilder()
        ->join(function($participant) use ($answerStore) {
            return $answerStore->findBy(["participantId", "=", $participant["id"]]);
        }, "answers")
        ->getQuery()
        ->fetch();


        if(!$participantsWithAnswers) {
            return [];
        }

        $counts = [];
        foreach($participantsWithAnswers as $participant) {
            if(!isset($counts[$participant['systemId']])) {
                $counts[$participant['systemId']] = 0;
            }
            // only count participants that have already submitted answers
            if(isset($participant['answers']) && count($participant['answers']) > 0){
                $counts[$participant['systemId']] = $counts[$participant['systemId']] + 1;
            }
        }

        return $counts;
    }

    public function storeAnswer($participant, $system, $questionId, $value, $type) {
        $type = $this->parseAnswerType($type);

        // insert data point first
        $dataPoint = $this->storeDataPoint($value, $questionId);

        $answer = new Answer($participant->getId(), $system->getIdentifier(), $dataPoint->getId(), $type, new \DateTime());

        // insert the answer 
        $this->answerStore->insert($answer->getDBRepresentation());
    }

    public function storeDataPoint($value, $key) {
        $dataPoint = new DataPoint($value, $key, new \DateTime());
        $data = $this->dataPointStore->insert($dataPoint->getDBRepresentation());
        $dataPoint->fillFromDbRepresentation($data);
        return $dataPoint;
    }

    private function parseAnswerType($answerType) {
        $type = strtolower($answerType);
        switch($type) {
            case 'serp':
                return self::ANSWER_TYPE_SERP;
            case 'questionnaire': 
            default:
                // default to text
                return self::ANSWER_TYPE_QUESTIONNAIRE;
        }
    }
}
