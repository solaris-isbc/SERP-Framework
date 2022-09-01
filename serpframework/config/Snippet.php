<?php

namespace serpframework\config;

// holds a list of snippets and their order
class Snippet {

    // only img and css and id are the fixed properties, the rest can be customly defined and used from template, so we dont implicitly define them
    public $img = null;
    private $css = null;
    private $id = null;
    public function __construct($data, $context)
    {
        foreach(get_object_vars($data) as $key => $value) {
            if($key == 'img') {
                $this->setImg($value, $context);
                continue;
            }
            if($key == 'css') {
                $this->setCss($value);
                continue;
            }
            $this->{$key} = $value;

        }       
        
    }

    public function getProperty($name) {
        return $this->{$name} ?? null;
    }

    public function getImg() {
        return $this->img;
    }

    public function getCSS() {
        return $this->css;
    }

    public function setCSS($css) {
        $this->css = $css ? preg_replace('/([^{]+{[^{]+})/', '#serp_result_' . $this->getId() . ' $1', $css) : null;
    }

    public function setImg($img, $contextSystem) {
        $this->img = $img ? '<img src="' . $contextSystem->getPathWeb() . '/snippets/' . $img . '" title="' . $img . '" />' : '';
    }

    public function getId() {
        return $this->id;
    }
}