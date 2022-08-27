<?php

namespace serpframework\persistence\models;

class Participant implements DbRepresentation
{
    private $createdAt;
    private $id;
    private $systemId;

    public function __construct($id = null, $systemId = null, $createdAt = null)
    {
        $this->id = $id;
        $this->systemId = $systemId;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "id" => $this->id,
            "systemId" => $this->systemId,
            "createdAt" => $this->createdAt,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->id = $data['id'];
        $this->systemId = $data['systemId'];
        $this->createdAt = $data['createdAt'];
    }

    public function getId() {
        return $this->id;
    }

    public function setSystemId($systemId) {
        $this->systemId = $systemId;
    }

    public function getSystemId() {
        return $this->systemId;
    }
}
