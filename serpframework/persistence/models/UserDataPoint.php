<?php

namespace serpframework\persistence\models;

/* Represent direct connection for user to datapoint -> for when not using serp or questionnaire => join table */
class UserDataPoint implements DbRepresentation
{
    private $participantId;
    private $_id;
    private $dataPointId;
    private $createdAt;

    public function __construct($participantId = null, $dataPointId = null, $createdAt = null, $_id = null)
    {
        $this->_id = $_id;
        $this->participantId = $participantId;
        $this->dataPointId = $dataPointId;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "_id" => $this->_id,
            "participantId" => $this->participantId,
            "dataPointId" => $this->dataPointId,
            "createdAt" => $this->createdAt,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->_id = $data['_id'];
        $this->createdAt = $data['createdAt'];
        $this->participantId = $data['participantId'];
        $this->dataPointId = $data['dataPointId'];
    }

    public function getId() {
        return $this->_id;
    }

    public function getParticipantId() {
        return $this->participantId;
    }

    public function getDataPointId() {
        return $this->dataPointId;
    }

}
