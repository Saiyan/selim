<?php

namespace Selim;

class Output{
    function __construct(array $pages){
        $this->pages = $pages;
    }

    protected  $pages = array();

    protected static function DefaultAdminText(SilverstripePage $sspage){
        return Util::boolToString($sspage->hasDefaultAdmin());
    }

    protected static function EmailLoggingText(SilverstripePage $sspage){
        return Util::boolToString($sspage->hasEmailLogging());
    }

    protected static function ModuleText(SilverstripePage $sspage) {
        return implode(" ", $sspage->getModules());
    }

}
