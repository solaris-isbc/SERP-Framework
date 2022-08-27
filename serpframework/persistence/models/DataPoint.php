<?php

namespace serpframework\persistence\models;

/* Represent any action taken by the user */
class DataPoint implements DbRepresentation
{
    private $createdAt;
    private $systemId;
    private $participantId;
    private $value;
    private $type;

    public function __construct($participantId = null, $systemId = null, $value = null, $type = null, $createdAt = null)
    {
        $this->participantId = $participantId;
        $this->value = $value;
        $this->type = $type;
        $this->systemId = $systemId;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "participantId" => $this->participantId,
            "value" => $this->value,
            "type" => $this->type,
            "systemId" => $this->systemId,
            "createdAt" => $this->createdAt,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->participantId = $data['participantId'];
        $this->systemId = $data['systemId'];
        $this->createdAt = $data['createdAt'];
        $this->value = $data['value'];
        $this->type = $data['type'];
    }

}
