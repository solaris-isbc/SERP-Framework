<?php

namespace serpframework\config;

class PageOrder implements Transformable
{
    private const ORDER_TYPE_FIXED = 0;
    private const ORDER_TYPE_RANDOM = 1;

    private $order = '';
    private $pages = [];
    
    public function transform($data) {
        $this->order = strtolower($data->order) == "random" ? self::ORDER_TYPE_RANDOM : self::ORDER_TYPE_FIXED;

        // load pages and page groups
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

    public function getPage(&$idx) {
        foreach($this->pages as $page) {
            if($idx == 0) {
                if(!is_object($page)) {
                    return $page;
                }
                return $page->getPage($idx);
            }
            $idx--;
        }
    }
}