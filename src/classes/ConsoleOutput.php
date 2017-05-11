<?php

namespace Selim;

use Twig_Loader_Filesystem;

class ConsoleOutput extends Output implements IOutput
{
    private $template = 'default_default.twig';

    private function getTemplateWrapper($templateFile)
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
            $template = $twig->load($this->template);
        }
        return $template;
    }

    public function write($templateFile = '')
    {
        $template = $this->getTemplateWrapper($templateFile);
        echo str_replace('\n', PHP_EOL, $template->render(array('pages' => $this->pages)));
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }
}
