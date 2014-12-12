<?php

require_once 'classes/Util.php';
require_once 'classes/ConsoleOutput.php';
require_once 'classes/ConsoleOutputTable.php';
require_once 'classes/SilverstripePage.php';

$sites = array();

if(!file_exists(Util::$path_config)){
    Util::saveSites($sites);
}

if($argv[1] && $argv[1] === "add" && $argv[2] && $argv[3]){
    $name = $argv[2];
    $path = $argv[3];
    Util::addSite($name,$path);
    return;
}

if($argv[1] && $argv[1] === "rm" && $argv[2]){
    $name = $argv[2];
    Util::removeSite($name,$path);
    return;
}


$sites = Util::loadSites();
$filter_name = Util::findInArrayWithRegex($argv,"/^--filter-name=/");
if($filter_name){
    $sites = Util::filterSitesByName($sites, preg_replace("/^--filter-name=/",$filter_name,""));
}


$sspages = array();
foreach ($sites as $n => $p) {
    array_push($sspages, new SilverstripePage($n, $p));
}

if(in_array("--table",$argv)){
    $output = new ConsoleOutputTable($sspages);
}else {
    $output = new ConsoleOutput($sspages);
}
$output->write();

