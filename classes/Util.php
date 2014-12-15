<?php

namespace Selim;

class Util {

    public static $path_config = "config.json";

    public static function stripPhpComments($str){
        $regex_multiline = "/\\/\\*[^\\Z]*\\*\\//m";
        $regex_singleline = "/\\/\\/.*/m";
        $str = preg_filter($regex_multiline,"",$str);
        return preg_filter($regex_singleline,"",$str);
    }

    public static function loadSites(){
        return json_decode(file_get_contents(self::$path_config),true);
    }

    public static function saveSites(array $sites){
        foreach($sites as $s){

        }

        file_put_contents(self::$path_config,json_encode($sites));
    }

    public static function addSite($name,$path){
        $sites = self::loadSites();

        if(!array_key_exists($name,$sites)){
            $sites[$name] = $path;
            self::saveSites($sites);
            echo "added: '$name'";
        }else{
            echo "Site with name '$name' already exists!";
        }
    }


    public static function removeSite($name){
        $sites = self::loadSites();
        if(array_key_exists($name,$sites)){
            $i = array_search($name,$sites);
            array_splice($sites,$i,1);
            self::saveSites($sites);
            echo "removed: '$name'";
        }else{
            echo "Site with name '$name' doesn't exists!";
        }
    }

    public static function boolToString($bool){
        return $bool ? 'true' : 'false';
    }

    public static function filterSitesByName(array $sites,$filterRegex) {
        $arr = array();
        foreach($sites as $n => $p){
            if(preg_match("/$filterRegex/",$n)){
                $arr[$n] = $p;
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
} 