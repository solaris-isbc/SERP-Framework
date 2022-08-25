<?php

namespace serpframework\config;

interface Transformable
{   
    /* puts the raw data and transforms it into the given class */
    public function transform($rawData);
}