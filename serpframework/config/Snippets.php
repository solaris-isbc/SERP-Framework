<?php

namespace serpframework\config;

// holds a list of snippets and their order
class Snippets
{
    private const ORDER_TYPE_FIXED = 0;
    private const ORDER_TYPE_RANDOM = 1;

    private $snippets = [];
    private $order;
    private $query;
    public function __construct($data){
        $this->order = strtolower($data->order) == "random" ? self::ORDER_TYPE_RANDOM : self::ORDER_TYPE_FIXED;
        $this->query = $data->query;

        foreach($data->snippets as $snippetRaw) {
            $snippet = new Snippet($snippetRaw);
            $this->snippets[] = $snippet;
        }
        if($this->order == self::ORDER_TYPE_RANDOM){
            shuffle($this->snippets);
        }
    }

    public function getSnippets() {
   
        return $this->snippets;
    }

    public function getQuery() {
        return $this->query;
    }
}