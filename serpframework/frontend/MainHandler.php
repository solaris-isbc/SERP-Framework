<?php

namespace serpframework\frontend;

use serpframework\config\Configuration;

class MainHandler
{
    private $config;
    private $f3;

    public function __construct($f3)
    {
        $this->config = new Configuration(dirname(__FILE__) . '/../../resources/config.json');   
        $this->f3 = $f3;
    }

    public function handleEntry() {
        // cookie user for IDing
        $userId = $this->identifyUser();

        // user might already be assigned to a system, get it 
        $system = $this->getUserSystem();
        if(!$system){
            // assign user to system
            $system = $this->chooseSystem($userId);
        }
        // display entry page
        $this->displayEntryPage($system);
    }

    private function displayEntryPage($system) {
        $this->f3->set('projectTitle', $system->getMember('task')->getTitle());
        $this->f3->set('author', $system->getMember('task')->getAuthor());
        $this->f3->set('taskDescription', $system->getMember('task')->getTaskDescription());
        $this->f3->set('agreementText', $system->getMember('task')->getAgreementText());
        $this->f3->set('systemName', $system->getIdentifier());

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