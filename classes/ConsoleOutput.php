<?php

require_once 'Util.php';
require_once 'Output.php';
require_once 'IOutput.php';

class ConsoleOutput extends Output implements IOutput{
    public function write() {
        foreach ($this->pages as $sspage) {
            echo self::formatSSpage($sspage);
        }
    }

    private static $format_default = "Site:            %s%nVersion:         %v%nDefaultAdmin:    %da%nEmailLogging:    %el%nEnvironmentType: %et%nModules:         %mo%n";

    private static function formatSSpage(\SilverstripePage $sspage){
        $format = self::$format_default;
        $placeholders = array(
            "%n" => PHP_EOL,
            "%s" => $sspage->getName(),
            "%v" => $sspage->getVersion(),
            "%da" => self::DefaultAdminText($sspage),
            "%el" => self::EmailLoggingText($sspage),
            "%et" => $sspage->getEnvironmentType(),
            "%mo" =>  self::ModuleText($sspage),
            "%cfgp" => $sspage->getConfigPhpPath(),
            "%cfgp" => $sspage->getConfigYmlPath(),
        );

        foreach($placeholders as $p => $v){
            $format = str_replace($p,$v,$format);
        }
        return $format;
    }
}