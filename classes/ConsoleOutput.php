<?php

namespace Selim;

class ConsoleOutput extends Output implements IOutput{
    public function write($format=''){
        foreach ($this->pages as $sspage){
            if($sspage instanceof SilverstripePage){
                echo self::formatSSpage($sspage,$format);
            }
        }
    }

    private static $format_default = "Site:            %s%nVersion:         %v%nDefaultAdmin:    %da%nEmailLogging:    %el%nEnvironmentType: %et%nModules:         %mo%n%n";

    private static function formatSSpage(SilverstripePage $sspage,$format = ''){
        $format = $format ? $format : self::$format_default;
        $placeholders = array(
            "%n" => PHP_EOL,
            "%s" => $sspage->getName(),
            "%v" => $sspage->getVersion(),
            "%da" => self::DefaultAdminText($sspage),
            "%el" => self::EmailLoggingText($sspage),
            "%et" => $sspage->getEnvironmentType(),
            "%mo" =>  self::ModuleText($sspage),
            "%cfgp" => $sspage->getConfigPhpPath(),
            "%cfgy" => $sspage->getConfigYmlPath(),
            "%root" => $sspage->getRootPath(),
        );

        foreach($placeholders as $p => $v){
            $format = str_replace($p,$v,$format);
        }
        return $format;
    }
}