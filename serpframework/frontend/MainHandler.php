<?php

namespace serpframework\frontend;

use DateTime;
use serpframework\config\Configuration;
use serpframework\config\Snippets;
use serpframework\config\Questionnaire;
use serpframework\persistence\DatabaseHandler;
use serpframework\persistence\models\Participant;
use Mobile_Detect;
class MainHandler
{
    public $config;
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

    private function handleEntry()
    {
        // first check if desktop/mobile allowed/forbidden
        $detect = new Mobile_Detect();
 
        // Any mobile device (phones or tablets).
        if (($this->config->getSetting('access', 'denyMobile') && $detect->isMobile()) || ($this->config->getSetting('access', 'denyDesktop') && !$detect->isMobile())) {
            $this->f3->reroute('/forbidden'); 
        }


        // cookie user for IDing
        $userExists = $this->identifyUser();

        // user might already be assigned to a system, get it
        $system = $this->getUserSystem($this->participant);
        $this->participant->setIsMobile($detect->isMobile());
        if (!$system) {
            // assign user to system
            $system = $this->chooseSystem($this->participant);
            // store user data in DB
            $this->databaseHandler->addParticipant($this->participant);
        }
        $this->system = $system;

        return $userExists;
    }

    public function displayForbiddenPage() {
        echo \Template::instance()->render('views/forbidden.htm');
        return;
    }

    public function storeData()
    {
        $userExists = $this->handleEntry();

        if(!$userExists){
            $this->displayEntryPage();
            return;
        }
        $pageType = $_POST["pagetype"] ?? "";
        $pageId = $_POST["pageId"] ?? "";

        $answers = [];

        foreach ($_POST as $id => $value) {
            if (strtolower($id) == "submit" || strtolower($id) == "pagetype" || strtolower($id) == "pageid") {
                // ignore the submit button
                continue;
            }
            $questionId = $id;
            $questionValue = $value;
            $this->databaseHandler->storeAnswer($this->participant, $this->system, $questionId, $questionValue, $pageType, $pageId);
        }
    }

    public function displayPage()
    {
        $userExists = $this->handleEntry();

        if(!$userExists){
            $this->displayEntryPage();
            return;
        }
        $completedPageIds = $this->databaseHandler->getCompletedPages($this->participant);
        $pageData = $this->system->getPage($completedPageIds);

        if (!$pageData || $this->participant->isCompleted()) {
            // reached the end of the experiment
            // show thank you page
            $this->databaseHandler->markCompleted($this->participant);

            echo \Template::instance()->render('views/thank_you.htm');
            return;
        }
        $templatePath = $this->system->getTemplatePath();
        $this->f3->set('system', $this->system);
        $this->f3->set('environment', 'live');
        $this->f3->set('participant', $this->participant);

        if ($pageData instanceof Snippets) {
            $this->f3->set('templatePath', $templatePath);
            $this->f3->set('snippets', $pageData);
            $this->f3->set('scope', 'serp');

            echo \Template::instance()->render('views/page.htm');
        } 
        elseif ($pageData instanceof Questionnaire) {
            $this->f3->set('scope', 'questionnaire');
            $this->f3->set('questionnaire', $pageData);
            echo \Template::instance()->render('views/page.htm');
        }
    }

    private function displayEntryPage()
    {
        $this->f3->set('projectTitle', $this->system->getMember('task')->getTitle());
        $this->f3->set('author', $this->system->getMember('task')->getAuthor());
        $this->f3->set('taskDescription', $this->system->getMember('task')->getTaskDescription());
        $this->f3->set('agreementText', $this->system->getMember('task')->getAgreementText());
        $this->f3->set('systemName', $this->system->getIdentifier());

        echo \Template::instance()->render('views/entry_page.htm');
    }

    private function chooseSystem(&$participant)
    {
        // if there's multiple equally low participated systems, choose one randomly
        // also lock user into the currently chosen system, but only increase user count once he has answered at least one page
        // for this, the database layer needs to be implemented

        //sleek DB doesn't support count, so we do the counting ourselves
        $participantCounts = $this->databaseHandler->getSystemUserCounts();

        $allSystems = [];
        // initialize a list of all system ids with count 0
        foreach ($this->config->getSystems() as $system) {
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

    private function identifyUser()
    {
        if (isset($_COOKIE['serp_system_user'])) {
            // try to load user from DB
            $participant = $this->databaseHandler->findParticipant($_COOKIE['serp_system_user']);
            if ($participant) {
                $this->participant = $participant;
                return true;
            }
        }
        // user is new or not found in DB, generate ID and create "empty" participant
        $userId = base64_encode(random_bytes(18));
        setcookie("serp_system_user", $userId, strtotime('+30 days'), '/');
        $this->participant = new Participant($userId, null, (new \DateTime())->format('Y-m-d H:i:s'));
        return false;
    }

    private function assignUserToSystem(&$participant, $system)
    {
        $participant->setSystemId($system->getIdentifier());
    }

    private function getUserSystem($participant)
    {
        $systemId = $participant->getSystemId() ?? null;
        return $this->config->getSystemById($systemId) ?? '';
    }
}
