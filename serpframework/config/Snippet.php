<?php

namespace serpframework\config;

// holds a list of snippets and their order
class Snippet {

    // only img and css and id are the fixed properties, the rest can be customly defined and used from template, so we dont implicitly define them
    private $img = null;
    private $css = null;
    private $id = null;
    public function __construct($data)
    {
        foreach(get_object_vars($data) as $key => $value) {
            $this->{$key} = $value;
        }       
        
    }

    public function getProperty($name) {
        return $this->{$name} ?? null;
    }

    public function getImageUrl() {
        return $this->img;
    }

    public function getCSS() {
        return $this->css ? preg_replace('/([^{]+{[^{]+})/', '#serp_result_' . $this->getId() . ' $1', $this->css) : null;
    }

    public function getId() {
        return $this->id;
    }
}