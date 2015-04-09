<?php

namespace Selim\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddSiteCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('add')
            ->setDescription('Add a Site')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Identifier for your Site'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to the project/mysite folder.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input,$output);
        $name = $input->getArgument('name');
        $path = $input->getArgument('path');
        $cfg = $this->getSelimConfig($input);

        if (!$cfg->siteExists($name)) {
            $cfg->addSite($name, $path);
            $cfg->write();
            echo "added: '$name'".PHP_EOL;
        } else {
            echo "Site with name '$name' already exists!".PHP_EOL;
        }
    }
}