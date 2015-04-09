<?php

namespace Selim\Commands;

use Selim\Util;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveSiteCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('rm')
            ->setDescription('Remove a Site')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Identifier for your Site'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $cfg = $this->getSelimConfig($input);

        if ($cfg->siteExists($name)) {
            $cfg->removeSite($name);
            $cfg->write();
            echo "removed: '$name'".PHP_EOL;
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }
}