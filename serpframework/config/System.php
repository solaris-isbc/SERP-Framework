<?php

namespace serpframework\config;

class System {

    private const VAR_TYPE_PRIMITIVE = 1;
    private const VAR_TYPE_ARRAY = 2;
    private const VAR_TYPE_OBJECT = 2;

    private $members = [];

    private $folder;

    public function __construct($path, $filename)
    {
        $data = json_decode(file_get_contents($path . '/' .$filename));
        
        $this->folder = basename($path);

        // first we register all relevant members from the json file
        $this->registerMember('name', $data->name, self::VAR_TYPE_PRIMITIVE);
        $this->registerMember('task', $data->task, self::VAR_TYPE_OBJECT);
        $this->registerMember('pageOrder', $data->pageOrder, self::VAR_TYPE_OBJECT);


        // if needed, the members will be transformed into their correct types
        $this->transformMember('task', Task::class);
        $this->transformMember('pageOrder', PageOrder::class);
    }

    private function registerMember($name, $value, $type) {
        $member = new \stdClass();
        $member->type = $type;
        $member->value = $value;
        $this->members[$name] = $member;    
    }

    public function getMember($name) {
        return isset($this->members[$name]) ? $this->members[$name]->value : null;
    }

    private function transformMember($name, $class) {
        $value = new $class();
        $memberValue = $this->getMember($name);
        if($memberValue) {
            $this->members[$name]->value = $value->transform($memberValue);
        }
    }

    public function getIdentifier() {
        return base64_encode($this->getMember('name'));
    }

    public function getPage($idx) {
        return $this->getMember('pageOrder')->getPage($idx);
    }

    public function getFolder() {
        return $this->folder;
    }

    public function getTemplatePath() {
        return 'resources/'  . $this->folder . '/snippets/template.htm';
    }
}