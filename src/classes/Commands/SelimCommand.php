<?php

namespace Selim\Commands;

use Selim\SelimConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelimCommand extends Command{

    public function __construct($name = null)
    {
        parent::__construct();
        $this->config =  SelimConfig::getInstance();
    }

    protected function configure()
    {
        /*$this
            ->addOption(
                'config',
                null,
                InputOption::VALUE_OPTIONAL,
                'custom path to your config.json'
            );
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        /*
        $cfg_path = $input->getOption("config");
        if ($cfg_path) {
            $this->config->setPath($cfg_path);
        }
        */
    }

    protected $config;
}