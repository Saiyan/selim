<?php

namespace Selim;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;

class SelimApplication extends Application {
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN'){
        parent::__construct($name,$version);
        $this->selim_config = SelimConfig::getInstance();
    }

    /**
     * @return SelimConfig
     */
    public function getSelimConfig(InputInterface $input){
        $config_path = $input->getOption('config');
        if($config_path) {
            $this->selim_config->setPath($config_path);
        }
        return $this->selim_config;
    }

    protected $selim_config;
}