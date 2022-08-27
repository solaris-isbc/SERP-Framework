<?php

namespace serpframework\persistence\models;

interface DbRepresentation
{   
    /* puts the raw data and transforms it into the given class */
    public function getDbRepresentation();
    public function fillFromDbRepresentation($data);
}