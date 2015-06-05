<?php

require_once __DIR__.'/../vendor/autoload.php';
spl_autoload_register(array('AutoLoader', 'loadClass'));

use Selim\Commands\AddSiteCommand;
use Selim\Commands\DefaultCommand;
use Selim\Commands\FindSitesCommand;
use Selim\Commands\RemoveSiteCommand;
use Selim\Commands\SecuritySiteCommand;
use Selim\SelimApplication;
use Symfony\Component\Console\Input\InputOption;

$application = new SelimApplication();
// Add global Options to the Application
$application->getDefinition()->addOptions(array(
    new InputOption('--config', '-c', InputOption::VALUE_REQUIRED, 'Path of config.json that should be used.')
));

$application->add(new AddSiteCommand());
$application->add(new DefaultCommand());
$application->add(new FindSitesCommand());
$application->add(new RemoveSiteCommand());
$application->add(new SecuritySiteCommand());

$application->run();

exit(0);
