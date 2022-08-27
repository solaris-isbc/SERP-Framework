<?php

namespace serpframework\config;

// holds a question for the questionnaire
class Question
{
    public const ANSWER_TYPE_TEXT = 0;
    public const ANSWER_TYPE_CHECKBOX = 1;
    public const ANSWER_TYPE_RADIO = 2;

    private $id;
    private $question;
    private $answerType;
    private $answers;
    private $required;

    public function __construct($data) {
        $this->id = $data->id;
        $this->question = $data->question;
        $this->required = isset($data->required) &&  boolval($data->required);
        $this->answerType = $this->parseAnswerType($data->answerType);
        if(isset($data->answers) && is_array($data->answers)) {
            $this->answers = [];
            foreach($data->answers as $answer) {
                $this->answers[] = $answer;
            }
        }
    }

    public function getId() {
        return $this->id;
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

    public function isRequired() {
        return $this->required;
    }
}