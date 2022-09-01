<?php

namespace serpframework\config;

use serpframework\traits\answerable;

// holds a list of snippets and their order
class Snippets
{
    private const ORDER_TYPE_FIXED = 0;
    private const ORDER_TYPE_RANDOM = 1;

    private $snippets = [];
    private $order;
    private $query;
    private $id;

    use answerable;

    public function __construct($data, $context){
        $this->order = strtolower($data->order) == "random" ? self::ORDER_TYPE_RANDOM : self::ORDER_TYPE_FIXED;
        $this->query = $data->query;

        foreach($data->snippets as $snippetRaw) {
            $snippet = new Snippet($snippetRaw, $context);
            $this->snippets[] = $snippet;
        }
        if($this->order == self::ORDER_TYPE_RANDOM){
            shuffle($this->snippets);
        }

        $this->question = $data->question;
        $this->id = $data->id;
        $this->answerType = $this->parseAnswerType($data->answerType);
       
        if(isset($data->minDescription)) {
            $this->setMinDescription($data->minDescription);
        }
        if(isset($data->maxDescription)) {
            $this->setMaxDescription($data->maxDescription);
        }

        if(isset($data->answers) && is_array($data->answers)) {
            $this->answers = [];
            foreach($data->answers as $answer) {
                $this->answers[] = $answer;
            }
        }
    }

    public function getSnippets() {
   
        return $this->snippets;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getId() {
        return $this->id;
    }

}