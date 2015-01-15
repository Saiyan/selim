<?php

namespace Selim;

class SelimConfig {
    private $sites = array();
    private static $uniqueInstance = null;
    public static $path_config = "config.json";

    public static function getInstance()
    {
        if (self::$uniqueInstance === null){
            self::$uniqueInstance = new SelimConfig();
        }
        return self::$uniqueInstance;
    }

    private final function __clone(){}

    protected function __construct(){
        if(!file_exists(self::$path_config)){
            self::write();
        }
        self::load();
    }

    public function load(){
        $sites = array();
        $json = json_decode(file_get_contents(self::$path_config),true);
        if(!isset($json["sites"])) return null;
        foreach($json["sites"] as $s){
            array_push($sites,new SiteConfig($s["name"],$s["path"]));
        }
        $this->sites = $sites;
    }

    public function write(){
        file_put_contents(self::$path_config,json_encode(array(
            "sites" => $this->sites
        )));
    }

    /**
     * @param $name
     * @return SiteConfig
     */
    public function getSite($name){
        foreach($this->sites as $s){
            if($s->name === $name) return $s;
        }
    }

    public function getSites(){
        return $this->sites;
    }

    public function siteExists($name){
        $exists = false;
        foreach ($this->sites as $s){
            if ($s->name === $name){
                $exists = true;
                break;
            }
        }
        return $exists;
    }

    public function addSite($name, $path){
        array_push($this->sites, new SiteConfig($name,$path));
    }

    public function removeSite($name){
        for($i=0; $i < count($this->sites);$i++){
            $s = $this->sites[$i];
            if($s->name === $name){
                array_splice($this->sites,$i,1);
                break;
            }
        }
    }
}