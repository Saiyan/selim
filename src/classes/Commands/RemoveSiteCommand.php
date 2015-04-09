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
        parent::execute($input,$output);
        $name = $input->getArgument('name');

        if ($this->config->siteExists($name)) {
            $this->config->removeSite($name);
            $this->config->write();
            echo "removed: '$name'".PHP_EOL;
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }
}