<?php

namespace serpframework\persistence\models;

/* Represent any action taken by the user */
class DataPoint implements DbRepresentation
{
    private $createdAt;
    private $_id;
    private $key;
    private $type;

    public function __construct($value = null, $key = null, $createdAt = null, $_id = null)
    {
        $this->_id = $_id;
        $this->value = $value;
        $this->key = $key;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "id" => $this->_id,
            "value" => $this->value,
            "key" => $this->key,
            "createdAt" => $this->createdAt,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->id = $data['id'];
        $this->createdAt = $data['createdAt'];
        $this->value = $data['value'];
        $this->key = $data['key'];
    }

    public function getId() {
        return $this->_id;
    }

}
