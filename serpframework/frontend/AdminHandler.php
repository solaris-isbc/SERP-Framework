<?php

namespace serpframework\frontend;

use serpframework\config\Configuration;
use serpframework\persistence\DatabaseHandler;

class AdminHandler
{
    public $config;
    private $f3;
    private $databaseHandler;

    private $isLoggedIn = false;

    public function __construct($f3)
    {
        $this->config = new Configuration(dirname(__FILE__) . '/../../resources/config.json');
        $this->f3 = $f3;
        $this->databaseHandler = new DatabaseHandler();
        $this->loginCheck();
    }

    public function displayAdminMainPage() {
        if(!$this->isLoggedIn) {
            $this->displayLoginPage();
            return;
        }
 
        $this->f3->set('systems', $this->config->getSystems());
        
        echo \Template::instance()->render('views/admin/index.htm');

    }

    public function showSystemOptions() {
        if(!$this->isLoggedIn) {
            $this->displayLoginPage();
            return;
        }
 
        $this->f3->set('systems', $this->config->getSystems());
        $this->f3->set('system', $this->currentSystem);
        
        echo \Template::instance()->render('views/admin/system.htm');

    }

    public function showSystemConfiguration() {
        if(!$this->isLoggedIn) {
            $this->displayLoginPage();
            return;
        }
 
        $this->f3->set('systems', $this->config->getSystems());
        $this->f3->set('system', $this->currentSystem);
        
        echo \Template::instance()->render('views/admin/system_configuration.htm');

    }

    public function displayExportPage() {
        if(!$this->isLoggedIn) {
            $this->displayLoginPage();
            return;
        }
 
        $this->f3->set('systems', $this->config->getSystems());
        
        echo \Template::instance()->render('views/admin/export.htm');

    }

    private function loginCheck() {
        $this->isLoggedIn = isset($_COOKIE['serp_system_admin']);
        return $this->isLoggedIn;
    }

    public function setSystemId($id) {
        $this->currentSystem = $this->config->getSystemById($id);
    }

    public function displaySerpPreview() {

        $this->f3->set('systems', $this->config->getSystems());
        $this->f3->set('system', $this->currentSystem);

        echo \Template::instance()->render('views/admin/preview.htm');

    }

    public function echoSystemPreview() {
        $samplePage = $this->currentSystem->getSamplePage();
        $this->f3->set('system', $this->currentSystem);

        $this->f3->set('templatePath',  $this->currentSystem->getTemplatePath());
        $this->f3->set('snippets', $samplePage);
        $this->f3->set('scope', 'serp');
        $this->f3->set('environment', 'preview');

        echo \Template::instance()->render('views/page.htm');
         
       
    }

    public function validateLogin($username, $password) {
        $referenceUser = $this->config->getSetting('backend', 'username');
        $referencePassword = $this->config->getSetting('backend', 'password');
        $success = $username == $referenceUser && $password == $referencePassword;

        if($success) {
            setcookie("serp_system_admin", "1", strtotime('+30 days'), '/');
        }
        return $success;
    }

    public function displayLoginPage($hasError = false) {
        $this->f3->set('hasError', $hasError);
        echo \Template::instance()->render('views/admin/login.htm');
    }
}