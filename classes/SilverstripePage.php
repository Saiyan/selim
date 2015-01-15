<?php

namespace Selim;

use SebastianBergmann\Exporter\Exception;
use Symfony\Component\Yaml\Yaml;


class SilverstripePage {
    private $path_configphp;
    private $path_project;
    private $path_configyml;
    private $path_root;
    private $path_ssversion;
    private $name;
    private $version;
    private $defadmin;
    private $maillog;
    private $envtype;
    private $modules;

    function __construct(SiteConfig $sc){
        $this->name = $sc->name;
        $this->path_project = realpath($sc->path);
        $this->path_configphp = realpath($this->path_project.'/_config.php');
        $this->path_configyml =  realpath($this->path_project.'/_config/config.yml');
        $this->path_root = realpath($this->path_project.'/..');
        $this->path_ssversion = realpath($this->path_project. '/../framework/')
            ? realpath($this->path_project . '/../framework/silverstripe_version')
            : realpath($this->path_project .'/../sapphire/silverstripe_version');

        if(!$this->path_configphp){
            throw new Exception("No _config.php found at: $sc->path ($sc->name)");
        }

        $this->readVersion();
        $this->readDefaultAdmin();
        $this->readEmailLogging();
        $this->readEnvironmentType();
        $this->readModules();
    }

    private function readVersion(){
        $this->version = 'N/A';
        if($this->path_ssversion) {
            $content = file_get_contents($this->path_ssversion);
            $v = array();
            preg_match_all("/\\d+\\.\\d+\\.\\d+/",$content,$v);

            if ($v && $v[0] && $v[0][0]) {
                $this->version = $v[0][0];
            }else{
                $this->version = realpath($this->path_root.'/sapphire') ? "2" : "3";
            }
        }
    }

    private function readDefaultAdmin(){
        $d = $this->matchInConfigPhp("/^\\s*Security::setDefaultAdmin/m");
        $this->defadmin = $d && $d[0] && $d[0][0] ? true : false;
    }

    private function readEmailLogging(){
        $m = $this->matchInConfigPhp("/\\s*SS_Log::add_writer\\(\\s*new\\s*SS_LogEmailWriter/m");
        $this->maillog = $m && $m[0] && $m[0][0] ? true : false;
    }

    private function readEnvironmentType(){
        $et = $this->matchInConfigPhp("/\\s*Director::set_environment_type\\(\\s*['\"](?<env>dev|live|test*)['\"]\\s*\\);/m");
        if($et && $et["env"]) {
            $this->envtype = $et["env"][0];
        }else{
            $this->envtype = "N/A";
        }

        if($this->path_configyml ) {
            $content = file_get_contents($this->path_configyml);
            foreach(preg_split("/^---/m",$content) as $block){
                try {
                    //Seems like the Yaml parser doesnt like the commas in the first block of the _config.yml
                    if(preg_match("~'framework/\*','cms/\*'~",$block)) continue;
                    $yml = Yaml::parse($block);
                    if ($yml && array_key_exists("Director",$yml) && array_key_exists("environment_type",$yml["Director"])) {
                        $this->envtype = $yml["Director"]["environment_type"];
                    }
                }catch(\ParseException $e){
                    echo $e->getMessage().PHP_EOL;
                }
            }
        }
    }

    private function readModules(){
        $modules = array();
        $proj = basename($this->path_project);

        if($this->path_root){
            foreach(scandir($this->path_root) as $f){
                $abs = "$this->path_root/$f";
                if(is_dir($abs) && realpath("$abs/_config.php") && $f !== $proj){
                    switch($f){
                        case "assets":
                        case "cms":
                        case "framework":
                        case "sapphire":
                            break;
                        default:
                            array_push($modules,$f);
                            break;
                    }
                }
            }
        }
        $this->modules = $modules;
    }

    private function matchInConfigPhp($regex){
        if($this->path_configphp) {
            $content = file_get_contents($this->path_configphp);
            $content = Util::stripPhpComments($content);
            $matches = array();
            preg_match_all($regex,$content,$matches);
            return $matches;
        }
        return null;
    }

    public function hasModule($regex){
        foreach($this->modules as $m){
            if(preg_match($regex,$m) === 1){
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @return string
     */
    function getVersion() {
        return $this->version;
    }

    /**
     * @return bool which indicates if a DefaultAdmin is specified
     */
    function hasDefaultAdmin(){
        return $this->defadmin;
    }

    /**
     * @return bool which indicates if EmailLogging is activated
     */
    function hasEmailLogging(){
        return $this->maillog;
    }

    /**
     * @return string with the EnvironmentType
     */
    function getEnvironmentType(){
        return $this->envtype;
    }

    /**
     * @return array with the module folder names
     */
    function getModules(){
        return $this->modules;
    }

    function getConfigPhpPath(){
        return $this->path_configphp;
    }

    function getConfigYmlPath(){
        return $this->path_configyml;
    }

    public function getRootPath() {
        return $this->path_root;
    }
}

?>