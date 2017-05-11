<?php

namespace Selim\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigPathCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('cfg')
            ->setDescription('Prints location of config.json that is used.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cfg = $this->getSelimConfig($input);
        $output->writeln($cfg->getPath());
    }
}