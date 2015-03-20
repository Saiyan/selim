<?php

require_once '../autoload.php';
spl_autoload_register(array('AutoLoader', 'loadClass'));

$selimCli = new \Selim\SelimCLI();

if (isset($argv[1]) && $argv[1] === "add" && isset($argv[2]) && $argv[3]) {
    $selimCli->addSite($argv[2], $argv[3]);
    exit(0);
}

if (isset($argv[1]) && $argv[1] === "rm" && isset($argv[2])) {
    $selimCli->removeSite($argv[2]);
    exit(0);
}

if (isset($argv[1]) && $argv[1] == "security" && isset($argv[2])) {
    $selimCli->securityCheck($argv[2]);
    exit(0);
}

$selimCli->start($argv);

exit(0);
