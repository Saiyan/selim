<?php

namespace Selim;

class Util {

    public static function stripPhpComments($str){
        $regex_multiline = "/\\/\\*[^\\Z]*\\*\\//m";
        $regex_singleline = "/\\/\\/.*/m";
        $result = preg_filter($regex_multiline,"",$str);
        if($result) $str = $result;
        return preg_filter($regex_singleline,"",$str);
    }

    public static function boolToString($bool){
        return $bool ? 'true' : 'false';
    }

    public static function filterSitesByName(array $sites,$filterRegex) {
        $arr = array();
        foreach($sites as $sc){
            if(!$sc instanceof SiteConfig) continue;
            if(preg_match("/$filterRegex/",$sc->name)){
                array_push($arr,$sc);
            }
        }
        return $arr;
    }

    public static function findInArrayWithRegex(array $arr, $regex) {
        foreach($arr as $a){
            if(preg_match($regex,$a)){
                return $a;
            }
        }
        return null;
    }


    /**
     * @param array $sspages should contain only objects of class SilverstripePage
     * @return returns an array with all SilverstripePages that match the regex
     */
    public static function filterPagesByModules(array $sspages,$filterRegex) {
        $arr = array();
        foreach($sspages as $sspage){
            if(!$sspage instanceof SilverstripePage) continue;
            if($sspage->hasModule("/$filterRegex/")) {
                array_push($arr, $sspage);
            }
        }
        return $arr;
    }
} 