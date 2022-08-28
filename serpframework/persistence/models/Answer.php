<?php

namespace serpframework\persistence\models;

/* Represent an answer from the user */
class Answer implements DbRepresentation
{
    private $createdAt;
    private $systemId;
    private $participantId;
    private $dataPointId;
    private $type;
    private $_id;

    public function __construct($participantId = null, $systemId = null, $dataPointId = null, $type = null, $createdAt = null)
    {
        $this->participantId = $participantId;
        $this->dataPointId = $dataPointId;
        $this->type = $type;
        $this->systemId = $systemId;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "participantId" => $this->participantId,
            "dataPointId" => $this->dataPointId,
            "type" => $this->type,
            "systemId" => $this->systemId,
            "createdAt" => $this->createdAt,
            "_id" => $this->_id,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->participantId = $data['participantId'];
        $this->systemId = $data['systemId'];
        $this->createdAt = $data['createdAt'];
        $this->dataPointId = $data['dataPointId'];
        $this->type = $data['type'];
        $this->_id = $data['_id'];
    }

}
