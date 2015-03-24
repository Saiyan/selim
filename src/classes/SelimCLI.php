<?php

namespace Selim;

class SelimCLI
{
    public function __construct($arguments)
    {
        $this->arguments= $arguments;
        $this->config =  SelimConfig::getInstance();
        $this->loadConfig();
    }

    private $arguments;
    private $config;

    /**
     * @param string $name
     * @param string $path
     */
    public function addSite($name, $path)
    {
        if (!$this->config->siteExists($name)) {
            $this->config->addSite($name, $path);
            $this->config->write();
            echo "added: '$name'".PHP_EOL;
        } else {
            echo "Site with name '$name' already exists!".PHP_EOL;
        }
    }

    /**
     * @param string $name
     */
    public function removeSite($name)
    {
        if ($this->config->siteExists($name)) {
            $this->config->removeSite($name);
            $this->config->write();
            echo "removed: '$name'".PHP_EOL;
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }

    /**
     * @param string $name
     */
    public function securityCheck($name)
    {
        if ($this->config->siteExists($name)) {
            echo "Security-test for $name:".PHP_EOL;
            $site = $this->config->getSite($name);
            $sc = new \Selim\SecurityChecker(new \Selim\SilverstripePage($site));
            $vulns = $sc->findVulnerabilities(true);
            foreach ($vulns as $vul) {
                $severity = $vul["severity"] ? $vul["severity"] : "Warning";
                Util::forceStringMinLength($severity, 9);
                echo "$severity ".$vul["title"].PHP_EOL;
            }
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }

    public function start(){
        $sites = $this->config->getSites();

        $filter_name = $this->getArgumentValue("--filter-name=");
        if (strlen($filter_name)) {
            $sites = Util::filterSitesByName($sites, $filter_name);
        }

        $sspages = array();
        foreach ($sites as $sc) {
            if ($sc instanceof SiteConfig) {
                array_push($sspages, new SilverstripePage($sc));
            }
        }

        $filter_module = $this->getArgumentValue("--filter-module=");
        if (strlen($filter_module)) {
            $sspages = Util::filterPagesByModules($sspages, $filter_module);
        }

        if (in_array("--table", $this->arguments)) {
            $output = new ConsoleOutputTable($sspages);
        } else {
            $output = new ConsoleOutput($sspages);
        }

        $format = $this->getArgumentValue("--format=");
        if (strlen($format)) {
            $output->write($format);
        } else {
            $output->write();
        }
    }

    private function getArgumentValue($argname){

        $argval = Util::findInArrayWithRegex($this->arguments, "/^$argname/");
        if ($argval) {
            $argval = preg_replace("/^$argname/", "", $argval);
            return $argval;
        }
        return "";
    }

    private function loadConfig() {
        $cfg_path = $this->getArgumentValue("--config=");
        if ($cfg_path) {
            $this->config->setPath($cfg_path);
        }
    }
}
