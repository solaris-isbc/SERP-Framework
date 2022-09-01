<?php
namespace serpframework\traits;

use serpframework\config\Question;

trait answerable {

    private $question;
    private $answerType;
    private $answers = [];
    private $minDescription = "";
    private $maxDescription = "";
    private $required = false;

    public function setQuestion($question) {
        $this->questoin = $question;
    }

    public function setAnswerType($answerType) {
        $this->answerType = $this->parseAnswerType($answerType);
    }
    
    public function addAnswer($answer) {
        $this->answers[] = $answer;
    }

    public function setMinDescription($minDescription) {
        $this->minDescription = $minDescription;
    }

    public function setMaxDescription($maxDescription) {
        $this->maxDescription = $maxDescription;
    }

    public function getMinDescription() {
        return $this->minDescription;
    }

    public function getMaxDescription() {
        return $this->maxDescription;
    }

    public function setIsRequired($isRequired) {
        $this->required = boolval($isRequired);
    }

    public function getQuestion()
    {
        return $this->question;
    }

    public function getAnswerType()
    {
        return $this->answerType;
    }

    public function getAnswers()
    {
        return $this->answers ?? [];
    }

    private function parseAnswerType($answerType)
    {
        $type = strtolower($answerType);
        switch($type) {
            case 'radio':
                return Question::ANSWER_TYPE_RADIO;
            case 'checkbox':
                return Question::ANSWER_TYPE_CHECKBOX;
            case 'likert':
                return Question::ANSWER_TYPE_LIKERT;
            case 'text':
            default:
                // default to text
                return Question::ANSWER_TYPE_TEXT;
        }
    }

    public function isRequired()
    {
        return $this->required;
    }
}