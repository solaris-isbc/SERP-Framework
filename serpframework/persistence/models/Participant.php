<?php

namespace serpframework\persistence\models;

class Participant implements DbRepresentation
{
    private $createdAt;
    private $id;
    private $systemId;
    private $_id;
    private $completed = false;

    public function __construct($id = null, $systemId = null, $createdAt = null)
    {
        $this->id = $id;
        $this->systemId = $systemId;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "id" => $this->id,
            "_id" => $this->_id,
            "systemId" => $this->systemId,
            "completed" => $this->completed,
            "createdAt" => $this->createdAt,
        ];
    }

    public function setCompleted() {
        $this->completed = true;
    }

    
    public function isCompleted() {
        return $this->completed;
    }

    public function fillFromDbRepresentation($data)
    {
        $this->id = $data['id'];
        $this->_id = $data['_id'];
        $this->systemId = $data['systemId'];
        $this->completed = $data['completed'];
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
