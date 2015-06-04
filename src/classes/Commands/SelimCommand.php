<?php

namespace Selim\Commands;

use Selim\SelimApplication;
use Selim\SelimConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

abstract class SelimCommand extends Command{

    /**
     * @param InputInterface $input
     * @return SelimConfig
     */
    protected function getSelimConfig(InputInterface $input){
        return $this->getApplication()->getSelimConfig($input);
    }

    /**
     * @return SelimApplication
     */
    public function getApplication(){
        return parent::getApplication();
    }
}