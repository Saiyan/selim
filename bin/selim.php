<?php

require_once __DIR__.'/../src/autoload.php';
require_once __DIR__.'/../vendor/autoload.php';
spl_autoload_register(array('AutoLoader', 'loadClass'));

use Selim\Commands\AddSiteCommand;
use Selim\Commands\DefaultCommand;
use Selim\Commands\RemoveSiteCommand;
use Selim\Commands\SecuritySiteCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new AddSiteCommand());
$application->add(new DefaultCommand());
$application->add(new RemoveSiteCommand());
$application->add(new SecuritySiteCommand());
$application->run();

exit(0);
