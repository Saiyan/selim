<?php

require_once 'autoload.php';
spl_autoload_register(array('AutoLoader', 'loadClass'));

$config = Selim\SelimConfig::getInstance();
$sites = $config->getSites();

if(isset($argv[1]) && $argv[1] === "add" && isset($argv[2]) && $argv[3]){
    $name = $argv[2];
    $path = $argv[3];
    if(!$config->siteExists($name)){
        $config->addSite($name,$path);
        $config->write();
        echo "added: '$name'";
    }else{
        echo "Site with name '$name' already exists!";
    }
    return;
}

if(isset($argv[1]) && $argv[1] === "rm" && isset($argv[2])){
    $name = $argv[2];
    if($config->siteExists($name)){
        $config->removeSite($name);
        $config->write();
        echo "removed: '$name'";
    }else{
        echo "Site with name '$name' doesn't exists!";
    }
    return;
}

$filter_name = Selim\Util::findInArrayWithRegex($argv,"/^--filter-name=/");
if($filter_name){
    $sites = Selim\Util::filterSitesByName($sites, preg_replace("/^--filter-name=/","",$filter_name));
}

$sspages = array();
foreach ($sites as $sc) {
    if($sc instanceof \Selim\SiteConfig)
    array_push($sspages, new Selim\SilverstripePage($sc));
}

$filter_module = Selim\Util::findInArrayWithRegex($argv,"/^--filter-module=/");
if($filter_module) {
    $sspages = \Selim\Util::filterPagesByModules($sspages, preg_replace("/^--filter-module=/","",$filter_module));
}


$format = Selim\Util::findInArrayWithRegex($argv,"/^--format=/");
if($format) {
    $format = preg_replace("/^--format=/","",$format);
}else{
    $format = '';
}

if(in_array("--table",$argv)){
    $output = new Selim\ConsoleOutputTable($sspages);
}else {
    $output = new Selim\ConsoleOutput($sspages);
}

$output->write($format);

echo PHP_EOL;
