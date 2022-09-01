<?php

namespace serpframework\config;

class Configuration {

    private $configPath = '';
    private $resourcesDirectory = '';
    private $sections = [];

    private $systems = [];

    public function __construct($configPath)
    {
        $this->configPath = $configPath;
        $this->resourcesDirectory = dirname($this->configPath);
        $this->parseConfig();
        $this->loadSystems();
    }

    public function getSystems() {
        return $this->systems;
    }

    private function parseConfig() {
        $configRaw = json_decode(file_get_contents($this->configPath));
        $this->sections = [];
        foreach($configRaw as $section => $data) {
            $this->registerSection($section, $data);
        }
    }

    private function registerSection($name, $data){
        $this->sections[$name] = $data;
    }

    public function getSections() {
        return $this->sections;
    }

    private function loadSystems() {
        // iterate over every folder in resources folder and check for system.json files in them
        $dir = new \DirectoryIterator($this->resourcesDirectory);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot() && $fileinfo->isDir() && file_exists($fileinfo->getPathName() . '/system.json')) {
                $this->registerSystem($fileinfo->getPathName(), 'system.json');
            }
        }
    }

    public function getSystemById($id) {
        $systemName = base64_decode($id ?? '');
        foreach($this->systems as $system) {
            if($system->getMember('name') == $systemName) {
                return $system;
            }
        }
        return null;
    }

    private function registerSystem($path, $filename) {
        $system = new System($path, $filename);
        $this->systems[] = $system;
    }

    public function updateSection($name, $value) {
        $this->sections[$name] = $value;
    }

    public function getSetting($section, $name) {
        if(isset($this->sections[$section]) && isset($this->sections[$section]->{$name})) {
            return $this->sections[$section]->{$name};
        }
        return null;
    }

    public function persist() {
        // store the current config
        $jsonStr = '{' . PHP_EOL;
        $continued = false;
        foreach($this->sections as $name => $section) {
            if (!$continued) {
                $continued = true;
                $jsonStr .= PHP_EOL;
            }else {
                // add line ending
                $jsonStr .= ',' . PHP_EOL;
            }
            $jsonStr .= '"' . $name . '": ' . json_encode($section);
        }
        $jsonStr .= '}';
        file_put_contents($this->configPath, $jsonStr);
        // re-read the config file
        $this->parseConfig();
    }
}