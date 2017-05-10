<?php

namespace Selim;

use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;

class ConsoleOutput extends Output implements IOutput
{

    public function write($format = '')
    {
        foreach ($this->pages as $sspage) {
            if ($sspage instanceof SilverstripePage) {
                //echo self::formatSSpage($sspage, $format);
                echo self::render($sspage);
            }
        }
    }

    private static function render(SilverstripePage $sspage){
        $loader = new FilesystemLoader(__DIR__.'/../views/%name%');

        $templating = new PhpEngine(new TemplateNameParser(), $loader);

        echo $templating->render('default_default.php', array('firstname' => 'Fabien'));

        $values = array(
            "newline" => PHP_EOL,
            "name" => $sspage->getName(),
            "version" => $sspage->getVersion(),
            "daText" => $sspage->hasDefaultAdmin(),
            "elText" => $sspage->hasEmailLogging(),
            "etText" => $sspage->getEnvironmentType(),
            "modules" =>  $sspage->getModules(),
            "cfg_php_path" => $sspage->getConfigPhpPath(),
            "cfg_yaml_path" => $sspage->getConfigYmlPath(),
            "root_path" => $sspage->getRootPath(),
        );

        echo $templating->render('default_default.php', $values);
    }

    private static $format_default = "Site:            %s%nVersion:         %v%nDefaultAdmin:    %da%nEmailLogging:    %el%nEnvironmentType: %et%nModules:         %mo%n%n";

    private static function formatSSpage(SilverstripePage $sspage, $format = '')
    {
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

        foreach ($placeholders as $p => $v) {
            $format = str_replace($p, $v, $format);
        }

        return $format;
    }
}
