<?php

namespace serpframework\frontend;

use serpframework\persistence\DatabaseHandler;

class ExportHandler
{
    private $databaseHandler;

    private $participants;
    private $answers;
    private $dataPoints;
    private $userDataPoints;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
        $this->loadData();
    }

    private function loadData()
    {        

        $this->participants = $this->indexArray($this->databaseHandler->fetchAllParticipants());
        $this->answers = $this->databaseHandler->fetchAllAnswers();
        $this->dataPoints = $this->indexArray($this->databaseHandler->fetchAllDataPoints());
        $this->userDataPoints = $this->databaseHandler->fetchAllParticipantDataPoints();
       
        $this->mapData('answers');
        $this->mapData('userDataPoints');     
    }

    public function exportAnswers() {
        $flattenedAnswers = $this->flatten('answers');
        $this->outputCsvFile($flattenedAnswers, 'answers.csv');
    }

    public function exportUserDataPoints() {
        // this cannot be flattened since one event coult be logged multiple times
        $outrows = [];
        foreach($this->userDataPoints as $userDataPoint) {
            $outrows[] = [
                'participant' => $userDataPoint->participant->getId(),
                'system' => base64_decode($userDataPoint->participant->getSystemId()),
                'key' => $userDataPoint->dataPoint->getKey(),
                'value' => $userDataPoint->dataPoint->getValue(),
            ];
        }
        $this->outputCsvFile($outrows, 'dataPoints.csv');
    }

    private function mapData($prop)
    {
        foreach ($this->$prop as $key => $value) {

            $dataPoint = $this->dataPoints[$value->getDataPointId()];
            $participant = $this->participants[$value->getParticipantId()];

            // abuse php to just randomly set properties on objects
            $this->$prop[$key]->participant = $participant;
            $this->$prop[$key]->dataPoint = $dataPoint;
        }
    }

    private function flatten($prop) {
        $uniqueKeys = array_unique(array_map(function ($item) {
            return $item->dataPoint->getKey();
        }, $this->$prop));
        $uniqueTimestampKeys = array_unique(array_map(function ($item) {
            return $item->dataPoint->getKey() . '_timestamp';
        }, $this->$prop));

        $keys = array_fill_keys(array_merge($uniqueKeys, $uniqueTimestampKeys), null);
        $participantData = [];
        foreach($this->$prop as $row) {
        // these fields will all be exported in one row in addition with participant data etc
            $participantId = $row->participant->getId();
            $systemId =  $row->participant->getSystemId();
            if (!array_key_exists($participantId, $participantData)) {
                $participantData[$participantId] = $keys;
                $participantData[$participantId]['participant'] = $participantId;
                $participantData[$participantId]['system'] = base64_decode($systemId);
                $participantData[$participantId]['isMobile'] = $row->participant->getIsMobile() ? "1" : "0";
            }
            $key = $row->dataPoint->getKey();
            $value = $row->dataPoint->getValue();
            $timestamp = $row->dataPoint->getTimestamp();
            $participantData[$participantId][$key] = $value;
            $participantData[$participantId][$key . '_timestamp'] = $timestamp['date'];
        }
        return $participantData;

    }

    private function indexArray($array)
    {
        $out = [];

        foreach ($array as $value) {
            $out[$value->getId()] = $value;
        }
        return $out;
    }

    private function outputCsvFile($outrows, $filename)
    {
        // output csv
        $file = $filename;
        $txt = fopen($file, "w");
        $isHeader = true;
        foreach ($outrows as $key => $value) {
            if ($isHeader) {
                fputcsv($txt, array_keys($value), ';');
                $isHeader = false;
            }
            fputcsv($txt, $value, ';');
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
}
