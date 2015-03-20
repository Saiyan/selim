<?php

class AutoLoader
{
    public static function loadClass()
    {
        require_once 'vendor/autoload.php';
        require_once 'vendor/pear/console_table/Table.php';
        require_once 'classes/Output.php';
        require_once 'classes/IOutput.php';
        require_once 'classes/ConsoleOutput.php';
        require_once 'classes/ConsoleOutputTable.php';
        require_once 'classes/SilverstripePage.php';
        require_once 'classes/SiteConfig.php';
        require_once 'classes/Util.php';
        require_once 'classes/SelimConfig.php';
        require_once 'classes/SelimCLI.php';
        require_once 'classes/SecurityChecker.php';
    }
}
