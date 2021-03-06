<?php

require_once __DIR__.'/../vendor/autoload.php';

$test_cfg = __DIR__."/config.json";

touch($test_cfg);
$cfg = \Selim\SelimConfig::getInstance();
$cfg->setPath($test_cfg,false);

require_once 'helper/TestPage.php';

register_shutdown_function(function() {
    $test_cfg = __DIR__."/config.json";
    if(file_exists($test_cfg)) {
        unlink($test_cfg);
    }
});