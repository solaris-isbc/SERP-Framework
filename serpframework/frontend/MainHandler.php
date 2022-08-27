<?php

namespace serpframework\frontend;

use DateTime;
use serpframework\config\Configuration;
use serpframework\config\Snippets;
use serpframework\config\Questionnaire;
use serpframework\persistence\DatabaseHandler;
use serpframework\persistence\models\Participant;

class MainHandler
{
    private $config;
    private $f3;

    private $system;
    private $participant;

    private $databaseHandler;

    public function __construct($f3)
    {
        $this->config = new Configuration(dirname(__FILE__) . '/../../resources/config.json');   
        $this->f3 = $f3;
        $this->databaseHandler = new DatabaseHandler();
    }

    private function handleEntry() {
        // cookie user for IDing
        $participant = $this->identifyUser();
        // user might already be assigned to a system, get it 
        $system = $this->getUserSystem($participant);
        if(!$system){
            // assign user to system
            $system = $this->chooseSystem($participant);
            // store user data in DB
            $this->databaseHandler->addParticipant($participant);
        }
        $this->system = $system;        
    }

    public function storeData(){
        // var_dump($_POST);
        // die;
    }

    public function displayPage($page) {
        $this->handleEntry();
        $pageData = $this->findPageData($this->system->getPage($page));

        if(!$pageData) {
            // reached the end of the experiment
            // show thank you page
            // TODO: mark participant as completed
            echo \Template::instance()->render('views/thank_you.htm');    

            return;
        }

        $templatePath = $this->system->getTemplatePath();
        $this->f3->set('system', $this->system);
        if(isset($pageData->snippets)){
            $snippets = new Snippets($pageData);
            $this->f3->set('templatePath', $templatePath);
            $this->f3->set('snippets', $snippets);
            $this->f3->set('scope', 'serp');
    
            echo \Template::instance()->render('views/page.htm');    
        }else{
            $questionnaire = new Questionnaire($pageData);
            $this->f3->set('scope', 'questionnaire');
            $this->f3->set('questionnaire', $questionnaire);
            echo \Template::instance()->render('views/page.htm');    
        }
    }

    private function findPageData($filename) {
        if(!$filename) {
            return null;
        }
        // scan the system folders for the file, prioritizing first questionnaires, then snippets
        $mainPath = dirname(__FILE__) . '/../../resources/' . $this->system->getFolder();
        if(file_exists($mainPath . '/questionnaires/' . $filename)) {
            return json_decode(file_get_contents($mainPath . '/questionnaires/' . $filename));
        }
        if(file_exists($mainPath . '/snippets/' . $filename)) {
            return json_decode(file_get_contents($mainPath . '/snippets/' . $filename));
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

    private function chooseSystem(&$participant) {
        // if there's multiple equally low participated systems, choose one randomly
        // also lock user into the currently chosen system, but only increase user count once he has answered at least one page
        // for this, the database layer needs to be implemented         

        //sleek DB doesn't support count, so we do the counting ourselves
        $participantCounts = $this->databaseHandler->getSystemUserCounts();
      
        $allSystems = [];
        // initialize a list of all system ids with count 0
        foreach($this->config->getSystems() as $system) {
            $allSystems[$system->getIdentifier()] = 0;
        }

        // merge it with the participant counts, later key values override earlier ones
        $allSystems = array_merge($allSystems, $participantCounts);

        // get list of minimum values
        $minSystems = array_keys($allSystems, min($allSystems));
    
        // choose randomly from minimal systems
        $chosenSystem = $this->config->getSystemById(array_rand(array_flip($minSystems)));

        $this->assignUserToSystem($participant, $chosenSystem);

        return $chosenSystem;
    }

    private function identifyUser() {
        if(isset($_COOKIE['serp_system_user'])) {
            // try to load user from DB
            $participant = $this->databaseHandler->findParticipant($_COOKIE['serp_system_user']);
            if($participant){
                return $participant;                
            }
        }
        // user is new or not found in DB, generate ID and create "empty" participant
        $userId = base64_encode(random_bytes(18));
        setcookie( "serp_system_user", $userId, strtotime( '+30 days' ), '/' );
        $participant = new Participant($userId, null, (new \DateTime())->format('Y-m-d H:i:s'));
        return $participant;
    }

    private function assignUserToSystem(&$participant, $system) {
        $participant->setSystemId($system->getIdentifier());
    }

    private function getUserSystem($participant){
        $systemId = $participant->getSystemId() ?? null;
        return $this->config->getSystemById($systemId) ?? '';
    }
}