<?php

namespace serpframework\config;

class Task implements Transformable
{
    private $taskDescription = '';
    private $author = '';
    private $title = '';
    private $agreementText = '';
    
    public function transform($data) {
        $this->taskDescription = $data->taskDescription ?? '';
        $this->author = $data->author ?? '';
        $this->title = $data->title ?? '';
        $this->agreementText = $data->agreementText ?? '';
        return $this;
    }

    public function getTaskDescription() {
        return $this->taskDescription;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAgreementText() {
        return $this->agreementText;
    }
}