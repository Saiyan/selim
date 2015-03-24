<?php

class AutoLoader
{
    public static function loadClass()
    {
        require_once __DIR__.'/../vendor/autoload.php';
        require_once __DIR__.'/../vendor/pear/console_table/Table.php';
        require_once __DIR__.'/classes/Output.php';
        require_once __DIR__.'/classes/IOutput.php';
        require_once __DIR__.'/classes/ConsoleOutput.php';
        require_once __DIR__.'/classes/ConsoleOutputTable.php';
        require_once __DIR__.'/classes/SilverstripePage.php';
        require_once __DIR__.'/classes/SiteConfig.php';
        require_once __DIR__.'/classes/Util.php';
        require_once __DIR__.'/classes/SelimConfig.php';
        require_once __DIR__.'/classes/SelimCLI.php';
        require_once __DIR__.'/classes/SecurityChecker.php';
    }
}
