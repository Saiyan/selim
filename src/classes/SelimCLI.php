<?php

namespace Selim;

class SelimCLI
{
    public function __construct()
    {
    }

    /**
     * @param string $name
     * @param string $path
     */
    public function addSite($name, $path)
    {
        $config = SelimConfig::getInstance();
        if (!$config->siteExists($name)) {
            $config->addSite($name, $path);
            $config->write();
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
        $config = SelimConfig::getInstance();
        if ($config->siteExists($name)) {
            $config->removeSite($name);
            $config->write();
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
        $config = SelimConfig::getInstance();
        if ($config->siteExists($name)) {
            echo "Security-test for $name:".PHP_EOL;
            $site = $config->getSite($name);
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

    public function start($arguments)
    {
        $config = SelimConfig::getInstance();

        $cfg_path = Util::findInArrayWithRegex($arguments, "/^--config=/");
        if ($cfg_path) {
            $cfg_path = preg_replace("/^--config=/", "", $cfg_path);
            $config->setPath($cfg_path);
        }

        $sites = $config->getSites();

        $filter_name = Util::findInArrayWithRegex($arguments, "/^--filter-name=/");
        if ($filter_name) {
            $sites = Util::filterSitesByName($sites, preg_replace("/^--filter-name=/", "", $filter_name));
        }

        $sspages = array();
        foreach ($sites as $sc) {
            if ($sc instanceof SiteConfig) {
                array_push($sspages, new SilverstripePage($sc));
            }
        }

        $filter_module = Util::findInArrayWithRegex($arguments, "/^--filter-module=/");
        if ($filter_module) {
            $sspages = Util::filterPagesByModules($sspages, preg_replace("/^--filter-module=/", "", $filter_module));
        }

        $format = Util::findInArrayWithRegex($arguments, "/^--format=/");
        if ($format) {
            $format = preg_replace("/^--format=/", "", $format);
        } else {
            $format = '';
        }

        if (in_array("--table", $arguments)) {
            $output = new ConsoleOutputTable($sspages);
        } else {
            $output = new ConsoleOutput($sspages);
        }
        if (strlen($format)) {
            $output->write($format);
        } else {
            $output->write();
        }
    }
}
