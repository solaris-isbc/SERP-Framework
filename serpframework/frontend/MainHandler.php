<?php

namespace serpframework\frontend;

use serpframework\config\Configuration;

class MainHandler
{
    private $config;
    private $f3;

    private $system;

    public function __construct($f3)
    {
        $this->config = new Configuration(dirname(__FILE__) . '/../../resources/config.json');   
        $this->f3 = $f3;
    }

    private function handleEntry() {
        // cookie user for IDing
        $userId = $this->identifyUser();

        // user might already be assigned to a system, get it 
        $system = $this->getUserSystem();
        if(!$system){
            // assign user to system
            $system = $this->chooseSystem($userId);
        }
        $this->system = $system;
    }

    public function displayPage($page) {
        $this->handleEntry();
        echo $this->findPage($this->system->getPage($page));
    }

    private function findPage($filename) {
        // scan the system folders for the file, prioritizing first questionnaires, then snippets
        $mainPath = $this->system->getPath();
        if(file_exists($mainPath . '/questionnaires/' . $filename)) {
            return file_get_contents($mainPath . '/questionnaires/' . $filename);
        }
        if(file_exists($mainPath . '/snippets/' . $filename)) {
            return file_get_contents($mainPath . '/snippets/' . $filename);
        }

        return null;
    }

    public function displayEntryPage() {
        $this->handleEntry();
        $this->f3->set('projectTitle', $this->system->getMember('task')->getTitle());
        $this->f3->set('author', $this->system->getMember('task')->getAuthor());
        $this->f3->set('taskDescription', $this->system->getMember('task')->getTaskDescription());
        $this->f3->set('agreementText', $this->system->getMember('task')->getAgreementText());
        $this->f3->set('systemName', $this->system->getIdentifier());

        echo \Template::instance()->render('views/entry_page.htm');
    }

    private function chooseSystem($userId) {
        // TODO: based on amount of participating people, choose the system with the least participants
        // if there's multiple equally low participated systems, choose one randomly
        // also lock user into the currently chosen system, but only increase user count once he has answered at least one page
        // for this, the database layer needs to be implemented         
        $chosenSystem = $this->config->getSystems()[0];

        $this->assignUserToSystem($chosenSystem);

        return $chosenSystem;
    }

    private function identifyUser() {
        if(isset($_COOKIE['serp_system_user'])) {
            return $_COOKIE['serp_system_user'];
        }
        $userId = base64_encode(random_bytes(18));
        setcookie( "serp_system_user", $userId, strtotime( '+30 days' ) );

        return $userId;
    }

    private function assignUserToSystem($system) {
        if(isset($_COOKIE['serp_system_system'])) {
            return $_COOKIE['serp_system_system'];
        }
        echo "assigned user to system";
        setcookie( "serp_system_system", $system->getIdentifier(), strtotime( '+30 days' ) );
    }

    private function getUserSystem(){
        $systemId =  $_COOKIE['serp_system_system'] ?? null;
        return $this->config->getSystemById($systemId);
    }
}