<?php

require_once 'Output.php';
require_once 'IOutput.php';
require_once  __DIR__ . '/../vendor/pear/console_table/Table.php';
use \Console_Table;

class ConsoleOutputTable extends Output implements IOutput{
    private static function getCellFromPlaceholder($placeholder, $sspage = null) {
        switch ($placeholder) {
            case '%s':
                return $sspage ? $sspage->getName() : "Site";
            case '%v':
                return $sspage ? $sspage->getVersion() : "Version";
            case '%da':
                return $sspage ? self::DefaultAdminText($sspage) : "DefaultAdmin";
            case '%el':
                return $sspage ? self::EmailLoggingText($sspage) : "EmailLogging";
            case '%et':
                return $sspage ? $sspage->getEnvironmentType() : "EnvironmentType";
            case '%m':
                return $sspage ? self::ModuleText($sspage) : "Modules";
            case '%cfgp':
                return $sspage ? $sspage->getConfigPhpPath() : "_config.php";
            case '%cfgy':
                return $sspage ? $sspage->getConfigYmlPath() : "_config/config.yml";
            default:
                return "";
        }
    }

    private static function getRowFromColumns($columns, SilverstripePage $sspage = null) {
        $matches = array();
        preg_match_all("/(?<pl>%s|%v|%da|%el|%et|%m|%cfgp|%cfgy)/",$columns,$matches);
        $row = array();

        foreach($matches["pl"] as $m) {
            $c = self::getCellFromPlaceholder($m,$sspage);
            if ($c) {
                array_push($row, $c);
            }
        }
        return $row;
    }

    private static function getHeadFromColumns($columns) {
        return self::getRowFromColumns($columns);
    }

    public function write($columns = "%s%v%da%el%et%m") {
        $table = new Console_Table();

        $table->setHeaders(self::getHeadFromColumns($columns));

        foreach ($this->pages as $p) {
            $table->addRow(self::getRowFromColumns($columns,$p));
        }

        echo $table->getTable();
    }
}