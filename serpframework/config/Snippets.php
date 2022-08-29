<?php

namespace serpframework\config;

// holds a list of snippets and their order
class Snippets
{
    public const ANSWER_TYPE_TEXT = 0;
    public const ANSWER_TYPE_CHECKBOX = 1;
    public const ANSWER_TYPE_RADIO = 2;

    private const ORDER_TYPE_FIXED = 0;
    private const ORDER_TYPE_RANDOM = 1;

    private $snippets = [];
    private $order;
    private $query;
    private $id;

    private $question;
    private $answerType;
    private $answers;

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

        $this->question = $data->question;
        $this->id = $data->id;
        $this->answerType = $this->parseAnswerType($data->answerType);
       
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

    public function getQuestion() {
        return $this->question;
    }

    public function getAnswerType() {
        return $this->answerType;
    }

    public function getAnswers() {
        return $this->answers ?? [];
    }

    public function getId() {
        return $this->id;
    }

    private function parseAnswerType($answerType) {
        $type = strtolower($answerType);
        switch($type) {
            case 'radio':
                return self::ANSWER_TYPE_RADIO;
            case 'checkbox': 
                return self::ANSWER_TYPE_CHECKBOX;
            case 'text':
            default:
                // default to text
                return self::ANSWER_TYPE_TEXT;
        }
    }
}