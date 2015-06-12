<?php

namespace Selim;

use Twig_Environment;
use Twig_Loader_Filesystem;

class HtmlOutput extends Output implements IOutput
{
    public function write($output_path = '')
    {
        if(!is_dir($output_path)){
            Util::reportError("'$output_path' is no directory");
            return;
        }

        if(is_writable($output_path)){
            $templatedir = realpath(__DIR__.'/../templates');
            $loader = new Twig_Loader_Filesystem($templatedir);
            $twig = new Twig_Environment($loader);

            $template = $twig->loadTemplate("index.html.twig");
            file_put_contents(
                $output_path.'/index.html',
                $template->render($this->ssPagestoTwigArray($this->pages))
            );

        }else{
            Util::reportError("Can't write to directory '$output_path'");
        }
    }

    /**
     * @return array
     */
    private function ssPagestoTwigArray()
    {
        return array(
            'page_title' => 'List of your Silverstripe Pages',
            'sspages' => $this->pages
        );
    }
}
