<?php

namespace Selim;

class SiteConfig {
    function __construct($name, $path){
        $this->name = (string) $name;
        $this->path = (string) $path;
    }

    public $name;
    public $path;
}