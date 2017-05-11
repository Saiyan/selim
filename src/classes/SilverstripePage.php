<?php

namespace Selim;

use Exception;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class SilverstripePage
{
    private $path_configphp;
    private $path_project;
    private $path_configyml;
    private $path_root;
    private $path_ssversion;
    private $path_composer;
    private $name;
    private $version;
    private $defadmin;
    private $maillog;
    private $envtype;
    private $modules;

    public function __construct(SiteConfig $sc)
    {
        $this->name = $sc->name;

        if ($this->setupPaths($sc->path)) {
            if (!$this->path_configphp) {
                throw new Exception("No _config.php found at: $sc->path ($sc->name)");
            }
        }

        $this->reload();
    }

    /**
     * gets the path of all required files and folders.
     *
     * @param string $path
     *
     * @return boolean
     */
    public function setupPaths($path)
    {
        $this->path_project = realpath($path);
        $this->path_configphp = realpath($this->path_project.'/_config.php');
        $this->path_configyml = realpath($this->path_project.'/_config/config.yml');
        $this->path_root = realpath($this->path_project.'/..');
        $this->path_ssversion = realpath($this->path_project.'/../framework/')
            ? realpath($this->path_project.'/../framework/silverstripe_version')
            : realpath($this->path_project.'/../sapphire/silverstripe_version');
        $this->path_composer = realpath($this->path_root.'/composer.lock');
    }

    public function reload()
    {
        $this->readVersion();
        $this->readDefaultAdmin();
        $this->readEmailLogging();
        $this->readEnvironmentType();
        $this->readModules();
    }

    private function readVersion()
    {
        $this->version = null;
        if ($this->path_ssversion) {
            $content_ssv = file_get_contents($this->path_ssversion);
            $v = array();
            preg_match_all("/\\d+\\.\\d+\\.\\d+/", $content_ssv, $v);

            if (!empty($v) && !empty($v[0]) && isset($v[0][0])) {
                $this->version = $v[0][0];
            }
        }

        if ($this->version === null) {
            if ($this->path_composer) {
                $content_c = file_get_contents($this->path_composer);
                $json = json_decode($content_c);
                if ($json->packages) {
                    foreach ($json->packages as $package) {
                        if ($package->name && $package->name == "silverstripe/cms") {
                            if (is_string($package->version)) {
                                $this->version = $package->version;
                            }
                        }
                    }
                }
            } else {
                $this->version = realpath($this->path_root.'/sapphire') ? "2" : "3";
            }
        }
    }

    private function readDefaultAdmin()
    {
        $d = $this->matchInConfigPhp("/^\\s*Security::setDefaultAdmin/m");
        $this->defadmin = $d && $d[0] && $d[0][0] ? true : false;
    }

    private function readEmailLogging()
    {
        $m = $this->matchInConfigPhp("/\\s*SS_Log::add_writer\\(\\s*new\\s*SS_LogEmailWriter/m");
        $this->maillog = $m && $m[0] && $m[0][0] ? true : false;
    }

    private function readEnvironmentType()
    {
        $et = $this->matchInConfigPhp("/\\s*Director::set_environment_type\\(\\s*['\"](?<env>dev|live|test*)['\"]\\s*\\);/m");
        if ($et && $et["env"]) {
            $this->envtype = $et["env"][0];
        } else if ($this->path_configyml) {
            $content = file_get_contents($this->path_configyml);
            foreach (preg_split("/^---/m", $content) as $block) {
                try {
                    //Seems like the Yaml parser doesnt like the commas in the first block of the _config.yml
                    if (preg_match("~'framework/\\*','cms/\\*'~", $block)) {
                        continue;
                    }
                    $yml = Yaml::parse($block);
                    if ($yml && array_key_exists("Director", $yml) && array_key_exists("environment_type", $yml["Director"])) {
                        $this->envtype = $yml["Director"]["environment_type"];
                    }
                } catch (ParseException $e) {
                    echo $e->getMessage().PHP_EOL;
                }
            }
        }

        if(!$this->envtype) $this->envtype = "live";
    }

    private function readModules()
    {
        $modules = array();
        $proj = basename($this->path_project);

        if ($this->path_root) {
            foreach (scandir($this->path_root) as $f) {
                $abs = "$this->path_root/$f";
                if (is_dir($abs) && realpath("$abs/_config.php") && $f !== $proj) {
                    switch ($f) {
                        case "assets":
                        case "cms":
                        case "framework":
                        case "sapphire":
                        case "..":
                        case ".":
                            break;
                        default:
                            array_push($modules, $f);
                            break;
                    }
                }
            }
        }
        $this->modules = $modules;
    }

    /**
     * searches for a regex string in PROJECT/_config.php.
     *
     * @param string $regex
     *
     * @return boolean|array
     */
    private function matchInConfigPhp($regex)
    {
        if ($this->path_configphp) {
            $content = file_get_contents($this->path_configphp);
            $content = Util::stripPhpComments($content);
            $matches = array();
            preg_match_all($regex, $content, $matches);

            return $matches;
        }

        return null;
    }

    /**
     * @param string $regex
     * @return bool
     */
    public function hasModule($regex)
    {
        foreach ($this->modules as $m) {
            if (preg_match($regex, $m) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return boolean indicates if a DefaultAdmin is specified
     */
    public function hasDefaultAdmin()
    {
        return $this->defadmin;
    }

    /**
     * @return boolean indicates if EmailLogging is activated
     */
    public function hasEmailLogging()
    {
        return $this->maillog;
    }

    /**
     * @return string with the EnvironmentType
     */
    public function getEnvironmentType()
    {
        return $this->envtype;
    }

    /**
     * @return array with the module folder names
     */
    public function getModules()
    {
        return $this->modules;
    }

    public function getConfigPhpPath()
    {
        return $this->path_configphp;
    }

    public function getConfigYmlPath()
    {
        return $this->path_configyml;
    }

    public function getRootPath()
    {
        return $this->path_root;
    }
}
