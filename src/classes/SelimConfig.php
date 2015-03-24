<?php

namespace Selim;

class SelimConfig
{
    private $sites = array();
    private $vulnerabilities = array();
    private static $uniqueInstance = null;
    private $path_config;
    public static $path_vulnerabilities = "/../json/vulnerabilities.json";

    public static function getInstance()
    {
        if (self::$uniqueInstance === null) {
            self::$uniqueInstance = new SelimConfig();
        }

        return self::$uniqueInstance;
    }

    private function loadVulnerabilites() {
        $this->vulnerabilities = json_decode(file_get_contents(__DIR__.self::$path_vulnerabilities), true);
    }

    final private function __clone()
    {
    }

    protected function __construct()
    {
        $conf_dir = "{$_SERVER['HOMEDRIVE']}{$_SERVER['HOMEPATH']}/.selim/";
        if(!file_exists($conf_dir)){
            mkdir($conf_dir);
        }

        $this->path_config = $conf_dir."config.json";

        if (file_exists($this->path_config)) {
            self::load();
        }
        self::loadVulnerabilites();
    }

    public function load()
    {
        $sites = array();
        $json = json_decode(file_get_contents($this->path_config), true);

        if (!isset($json["sites"])) {
            return;
        }
        foreach ($json["sites"] as $s) {
            array_push($sites, new SiteConfig($s["name"], $s["path"]));
        }
        $this->sites = $sites;
    }

    public function write()
    {
        file_put_contents($this->$path_config, json_encode(array(
            "sites" => $this->sites,
        )));
    }

    /**
     * @param string $name
     *
     * @return SiteConfig
     */
    public function getSite($name)
    {
        foreach ($this->sites as $s) {
            if ($s->name === $name) {
                return $s;
            }
        }
        return null;
    }

    public function getSites()
    {
        return $this->sites;
    }

    /**
     * checks if a site with specific name already exists in this config.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function siteExists($name)
    {
        $exists = false;
        foreach ($this->sites as $s) {
            if ($s->name === $name) {
                $exists = true;
                break;
            }
        }

        return $exists;
    }

    /**
     * Adds the path of a Silverstripe project directory (e.g. mysite) to this config.
     *
     * @param string $name
     * @param string $path
     *
     * @return boolean
     */
    public function addSite($name, $path)
    {
        array_push($this->sites, new SiteConfig($name, $path));
    }

    public function removeSite($name)
    {
        for ($i = 0; $i < count($this->sites);$i++) {
            $s = $this->sites[$i];
            if ($s->name === $name) {
                array_splice($this->sites, $i, 1);
                break;
            }
        }
    }

    /**
     * @return array all Vulnerabilities from json/vulnerabilities.json
     */
    public function getVulnarabilityDb()
    {
        return $this->vulnerabilities;
    }

    public function setPath($config_path = "",$is_cli = true) {
        $dir = dirname($config_path);
        if(file_exists($dir)) {
            if(!file_exists($config_path) && $is_cli){
                echo "The file $config_path doesn't exist. Do you want to create it? yes/[no]";
                $line = fgets(STDIN);
                if(preg_match("/^y|yes/", $line)) {
                    file_put_contents($config_path, '{"sites":{}}');
                }else{
                    Util::reportError("Aborting...");
                }
            }
            $this->path_config = $config_path;
            $this->load();
        }else{
            Util::reportError("The directory \"$dir\" doesnt exist.");
        }
    }
}
