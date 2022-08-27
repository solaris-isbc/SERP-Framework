<?php

namespace serpframework\config;

// holds a list of Questions 
class Questionnaire
{
    private $id;
    private $name;
    private $description;
    private $questions = [];

    //TODO Create this alike the snippets class

    public function __construct($data) {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->description = $data->description;

        foreach($data->questions as $questionRaw) {
            $question = new Question($questionRaw);
            $this->questions[] = $question;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName()  {
        return $this->name ;
    }

    public function getQuestions() {
        return $this->questions;
    }
    
    public function getDescription() {
        return $this->description;
    }
}