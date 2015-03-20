<?php

class AutoLoader
{
    public static function loadClass()
    {
        require_once 'vendor/autoload.php';
        require_once 'vendor/pear/console_table/Table.php';
        require_once 'src/classes/Output.php';
        require_once 'src/classes/IOutput.php';
        require_once 'src/classes/ConsoleOutput.php';
        require_once 'src/classes/ConsoleOutputTable.php';
        require_once 'src/classes/SilverstripePage.php';
        require_once 'src/classes/SiteConfig.php';
        require_once 'src/classes/Util.php';
        require_once 'src/classes/SelimConfig.php';
        require_once 'src/classes/SelimCLI.php';
        require_once 'src/classes/SecurityChecker.php';
    }
}
