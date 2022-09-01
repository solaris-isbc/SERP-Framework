<?php

namespace serpframework\config;

class PageOrder implements Transformable
{
    private const ORDER_TYPE_FIXED = 0;
    private const ORDER_TYPE_RANDOM = 1;

    private $order = '';
    private $pages = [];
    private $pageObjects = [];

    public function transform($data) {
        $this->order = strtolower($data->order) == "random" ? self::ORDER_TYPE_RANDOM : self::ORDER_TYPE_FIXED;

        // load raw pages and page groups
        foreach($data->pages as $page) {
            if(!is_object($page)) {
                $this->pages[] = $page;
                continue;
            }
            $pageObject = new PageOrder();
            $this->pages[] = $pageObject->transform($page);
        }

        return $this;
    }

    public function initialize($systemContext) {
        foreach($this->pages as $page) {
            if(!is_object($page)) {
                $this->pageObjects[] = $this->findPageData($page, $systemContext);
                continue;
            }
            $this->pageObjects[] = $page->initialize($systemContext);
        }
        if($this->order == self::ORDER_TYPE_RANDOM){
            shuffle($this->pageObjects);
        }
        return $this;
    }

    private function findPageData($filename, $systemContext) {
        if(!$filename) {
            return null;
        }
        $returnValue = null;
        // scan the system folders for the file, prioritizing first questionnaires, then snippets
        $mainPath = dirname(__FILE__) . '/../../resources/' . $systemContext->getFolder();
        if(file_exists($mainPath . '/questionnaires/' . $filename)) {
            $pageData = json_decode(file_get_contents($mainPath . '/questionnaires/' . $filename));
            $returnValue =  new Questionnaire($pageData);
        }
        if(file_exists($mainPath . '/snippets/' . $filename)) {
            $pageData = json_decode(file_get_contents($mainPath . '/snippets/' . $filename));
            $returnValue = new Snippets($pageData, $systemContext);
        }

        return $returnValue;
    }
    
    public function getPage($completedPages) {
        foreach($this->pageObjects as $page) {
            if($page instanceof PageOrder) {
                $res = $page->getPage($completedPages);
                if($res){
                    // found either a qst or snip in the page order that has not been completed yet
                    return $res;
                }
            }
            // either no incomplete qst or snip has been found or it is not a pageOrder
            if(!($page instanceof PageOrder)) {
                // current page is not a page order -> check if its already completed, if it is not, return it
                if (!in_array($page->getId(), $completedPages)) {
                    return $page;
                }
            }
            // re-loop and check all other entries
        }
        // no incomplete page has been found
        return null;
    }
}