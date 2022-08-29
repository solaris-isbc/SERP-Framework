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

    public function findParticipant($id)
    {
        $data = $this->participantsStore->findOneBy(["id", "=", $id]);
        // if participant somehow was not found
        if (!$data) {
            return null;
        }
        $participant = new Participant();
        $participant->fillFromDbRepresentation($data);
        return $participant;
    }

    public function markCompleted(&$participant)
    {
        $participant->setCompleted();
        $this->participantsStore->update($participant->getDbRepresentation());
    }

    public function fetchAllParticipants()
    {
        return $this->participantsStore->findAll();
    }

    public function getSystemUserCounts()
    {
        $answerStore = $this->answerStore;
        $participantsWithAnswers = $this->participantsStore
        ->createQueryBuilder()
        ->join(function ($participant) use ($answerStore) {
            return $answerStore->findBy(["participantId", "=", $participant["id"]]);
        }, "answers")
        ->getQuery()
        ->fetch();


        if (!$participantsWithAnswers) {
            return [];
        }

        $counts = [];
        foreach ($participantsWithAnswers as $participant) {
            if (!isset($counts[$participant['systemId']])) {
                $counts[$participant['systemId']] = 0;
            }
            // only count participants that have already submitted answers
            if (isset($participant['answers']) && count($participant['answers']) > 0) {
                $counts[$participant['systemId']] = $counts[$participant['systemId']] + 1;
            }
        }

        return $counts;
    }

    public function storeAnswer($participant, $system, $questionId, $value, $type, $pageId)
    {
        $type = $this->parseAnswerType($type);

        // insert data point first
        $dataPoint = $this->storeDataPoint($value, $questionId);

        $answer = new Answer($participant->getId(), $system->getIdentifier(), $dataPoint->getId(), $pageId, $type, new \DateTime());

        // insert the answer
        $this->answerStore->insert($answer->getDBRepresentation());
    }

    public function storeDataPoint($value, $key)
    {
        $dataPoint = new DataPoint($value, $key, new \DateTime());
        $data = $this->dataPointStore->insert($dataPoint->getDBRepresentation());
        $dataPoint->fillFromDbRepresentation($data);
        return $dataPoint;
    }

    public function getCompletedPages($participant)
    {
        // $this->answerStore->findOneBy()
        // TODO: fix invalid join qurey
        $answerStore = $this->answerStore;
        $dataPointsWithAnswers = $this->dataPointStore
        ->createQueryBuilder()
        ->join(function ($dataPoint) use ($answerStore, $participant) {
            return $answerStore->findBy(
                ["dataPointId", "=",  $dataPoint["_id"]]
            );
        }, "answer")
        ->getQuery()
        ->fetch();

        // fetch the completed pages from the datapoints
        $completedPages = [];
        foreach ($dataPointsWithAnswers as $dataPoint) {
            $pageId = isset($dataPoint['answer'][0]['pageId']) ? $dataPoint['answer'][0]['pageId'] : null;
            if (!$pageId || in_array($pageId, $completedPages) || $dataPoint['answer'][0]['participantId'] != $participant->getId()) {
                continue;
            }
            $completedPages[] = $pageId;
        }

        return $completedPages;
    }


    public function exportData()
    {
        // TODO: somehow make compatible so only data points get stored which are based on systems
        // give them context as well?
        // export main data
        // query EVERYTHING
        $answerStore = $this->answerStore;
        $participantsStore = $this->participantsStore;
        $fullData = $this->dataPointStore
        ->createQueryBuilder()
        ->join(function ($dataPoint) use ($answerStore, $participantsStore) {
            // returns QueryBuilder
            return $answerStore
            ->createQueryBuilder()
            ->where([ "dataPointId", "=", $dataPoint["_id"] ])
            ->join(function ($answer) use ($participantsStore) {
                // returns result
                return $participantsStore->findBy(["participantId", "=", $answer["participantId"]]);
            }, "participants");
        }, "questionData")
        ->getQuery()
        ->fetch();

        $outrows = [];
        $data = [];
        foreach($fullData as $row) {
            $outrow = [];
            foreach($row['questionData'] as $questionData) {
                $outrow = [
                    "participant" => $questionData['participantId'],
                    "system" => base64_decode($questionData['systemId']),
                    "created" => $row['createdAt']['date'],
                    "question" => $row['key'],
                    "answer" => $row['value'],
                ];
                $outrows[] = $outrow;
    
            }
        }  

     
        $parsedOutrows = [];
        foreach($outrows as $row) {
            if(!isset($parsedOutrows[$row["participant"]])){
                $parsedOutrows[$row['participant']] = [
                    "participant" => $row["participant"],
                    "system" => $row["system"]
                ];
            }
            $key = $row['question'];
            $parsedOutrows[$row['participant']]['answer_' . $key] = $row['answer'];
            $parsedOutrows[$row['participant']]['time_answer_' . $key] = $row['created'];
        }


        // output csv

        $file = "output.csv";
        $txt = fopen($file, "w");
        $isHeader = true;
        foreach($parsedOutrows as $key => $value) {
            // TODO: escape csv
            // TODO: remove pre tags wherever they are coming from
            if($isHeader) {
                $line = implode(';', array_keys($value)) . PHP_EOL;
                fwrite($txt, $line);
                $isHeader = false;
            }
            $line = implode(';', $value) . PHP_EOL;
            fwrite($txt, $line);
        }

        fclose($txt);

        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        header("Content-Type: text/plain");
        readfile($file);

    }

    private function parseAnswerType($answerType)
    {
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
