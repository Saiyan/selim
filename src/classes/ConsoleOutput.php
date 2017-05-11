<?php

namespace Selim;

use Twig_Loader_Filesystem;

class ConsoleOutput extends Output implements IOutput
{

    private static function getTemplate($templateFile)
    {
        if(strlen($templateFile)){
            $templatePath = realpath($templateFile);
            if(!realpath($templateFile)) {
                echo "Could not read $templateFile.".PHP_EOL;
                die;
            }
            $templatePathInfo = pathinfo($templatePath);
            $dir = $templatePathInfo['dirname'];
            $filename = $templatePathInfo['filename'].'.'.$templatePathInfo['extension'];
            $loader = new Twig_Loader_Filesystem($dir);
            $twig = new \Twig_Environment($loader);
            $template = $twig->load($filename);
        }else{
            $loader = new Twig_Loader_Filesystem(__DIR__.'/../views/');
            $twig = new \Twig_Environment($loader);
            $template = $twig->load('default_default.twig');
        }
        return $template;
    }

    public function write($templateFile = '')
    {
        $template = self::getTemplate($templateFile);
        echo str_replace('\n', PHP_EOL, $template->render(array('pages' => $this->pages)));
    }

    private static function render(SilverstripePage $sspage, $templateFile){
        $template = self::getTemplate($templateFile);

        $values = array(
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

        return str_replace('\n', PHP_EOL, $template->render($values));
    }


}
