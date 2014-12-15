<?php

spl_autoload_register('autoload');

function autoload(){
        require_once 'vendor/autoload.php';
        require_once 'vendor/pear/console_table/Table.php';
        require_once 'classes/Output.php';
        require_once 'classes/IOutput.php';
        require_once 'classes/ConsoleOutput.php';
        require_once 'classes/ConsoleOutputTable.php';
        require_once 'classes/SilverstripePage.php';
        require_once 'classes/SiteConfig.php';
        require_once 'classes/Util.php';
}

$sites = array();

if(!file_exists(Selim\Util::$path_config)){
    Selim\Util::saveSites($sites);
}

if($argv[1] && $argv[1] === "add" && $argv[2] && $argv[3]){
    $name = $argv[2];
    $path = $argv[3];
    Selim\Util::addSite($name,$path);
    return;
}

if($argv[1] && $argv[1] === "rm" && $argv[2]){
    $name = $argv[2];
    Selim\Util::removeSite($name,$path);
    return;
}

$sites = Selim\Util::loadSites();
$filter_name = Selim\Util::findInArrayWithRegex($argv,"/^--filter-name=/");
if($filter_name){
    $sites = Selim\Util::filterSitesByName($sites, preg_replace("/^--filter-name=/",$filter_name,""));
}

$sspages = array();
foreach ($sites as $sc) {
    if($sc instanceof \Selim\SiteConfig)
    array_push($sspages, new Selim\SilverstripePage($sc));
}

if(in_array("--table",$argv)){
    $output = new Selim\ConsoleOutputTable($sspages);
}else {
    $output = new Selim\ConsoleOutput($sspages);
}
$output->write();

