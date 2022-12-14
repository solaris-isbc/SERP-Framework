<?php

namespace serpframework\persistence\models;

/* Represent any action taken by the user */
class DataPoint implements DbRepresentation
{
    private $createdAt;
    private $_id;
    private $key;
    private $value;

    public function __construct($value = null, $key = null, $createdAt = null, $_id = null)
    {
        $this->_id = $_id;
        $this->value = $value;
        $this->key = $key;
        $this->createdAt = $createdAt;
    }

    public function getDBRepresentation() {
        return [
            "_id" => $this->_id,
            "value" => $this->value,
            "key" => $this->key,
            "createdAt" => $this->createdAt,
        ];
    }

    public function fillFromDbRepresentation($data)
    {
        $this->_id = $data['_id'];
        $this->createdAt = $data['createdAt'];
        $this->value = $data['value'];
        $this->key = $data['key'];
    }

    public function getId() {
        return $this->_id;
    }

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    } 

    public function getTimestamp() {
        return $this->createdAt;
    }

}
