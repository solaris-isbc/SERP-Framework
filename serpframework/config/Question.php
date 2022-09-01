<?php

namespace serpframework\config;

use serpframework\traits\answerable;

// holds a question for the questionnaire
class Question
{
    use answerable;
    public const ANSWER_TYPE_TEXT = 0;
    public const ANSWER_TYPE_CHECKBOX = 1;
    public const ANSWER_TYPE_RADIO = 2;
    public const ANSWER_TYPE_LIKERT = 3;

    private $id;

    public function __construct($data)
    {
        $this->id = $data->id;
        $this->question = $data->question;
        $this->required = isset($data->required) &&  boolval($data->required);
        $this->answerType = $this->parseAnswerType($data->answerType);
        if (isset($data->minDescription)) {
            $this->setMinDescription($data->minDescription);
        }
        if (isset($data->maxDescription)) {
            $this->setMaxDescription($data->maxDescription);
        }

        if (isset($data->answers) && is_array($data->answers)) {
            $this->answers = [];
            foreach ($data->answers as $answer) {
                $this->answers[] = $answer;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }
}
