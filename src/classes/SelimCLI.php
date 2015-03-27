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

        $filter_version = $this->getArgumentValue("--filter-version=");
        if (strlen($filter_version)) {
            $sspages = Util::filterPagesByVersion($sspages, $filter_version);
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
