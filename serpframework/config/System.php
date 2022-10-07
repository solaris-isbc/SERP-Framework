<?php

namespace serpframework\config;

class System {

    private const VAR_TYPE_PRIMITIVE = 1;
    private const VAR_TYPE_ARRAY = 2;
    private const VAR_TYPE_OBJECT = 2;

    private $data;
    private $members = [];

    private $folder;
    private $path;
    private $configFilePath;
    public function __construct($path, $filename)
    {
        $data = json_decode(file_get_contents($path . '/' .$filename));
        $this->data = $data;
        $this->path = $path;
        $this->configFilePath = $path . '/' .$filename;

        $this->folder = basename($path);

       $this->update($data);
    }

    public function update($data, $writeConfig = false) {
        //unregister any members or fields
        $this->members = [];

        // first we register all relevant members from the json file
        $this->registerMember('name', $data->name, self::VAR_TYPE_PRIMITIVE);
        if(isset($data->hasDocuments)) {
            $this->registerMember('hasDocuments', $data->hasDocuments, self::VAR_TYPE_PRIMITIVE);
        }
        $this->registerMember('task', $data->task, self::VAR_TYPE_OBJECT);
        $this->registerMember('pageOrder', $data->pageOrder, self::VAR_TYPE_OBJECT);


        // if needed, the members will be transformed into their correct types
        $this->transformMember('task', Task::class);
        $this->transformMember('pageOrder', PageOrder::class);
        // special case: pageOrder -> we need the context of the system to load further json files
        // -> pass system for context purpose
        $this->members['pageOrder']->value->initialize($this);
        if($writeConfig) {
            file_put_contents($this->configFilePath, json_encode($data));
        }

    }

    public function updateTemplate($template) {
        file_put_contents($this->path . '/snippets/template.htm', $template);
    }

    public function updateCss($css) {
        file_put_contents($this->path . '/system.css', $css);
    }

    private function registerMember($name, $value, $type) {
        $member = new \stdClass();
        $member->type = $type;
        $member->value = $value;
        $this->members[$name] = $member;    
    }

    public function getConfigFilePath() {
        return $this->configFilePath;
    }

    public function getRawData() {
        return $this->data;
    }

    public function getPathWeb() {
        return '/resources/' . $this->getFolder();
    }

    public function getTemplate() {
        return file_get_contents($this->path . '/snippets/template.htm');
    }


    public function getCss() {
        return file_get_contents($this->path . '/system.css');
    }

    public function getCssPathWeb() {
        return '/resources/' . $this->getFolder() . '/system.css';
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

    public function getPage($completedPages) {
        return $this->getMember('pageOrder')->getPage($completedPages);
    }

    public function getSamplePage() {
        // get the first serp page
        $completedPages = [];
        $searching = true;
        while($searching) {
            $page = $this->getPage($completedPages);
            if(!$page instanceof Snippets) {
                $completedPages[] = $page->getId();
                continue;
            }
            return $page;
        }
        return null;
    }

    public function getFolder() {
        return $this->folder;
    }

    public function getTemplatePath() {
        return 'resources/'  . $this->folder . '/snippets/template.htm';
    }
}