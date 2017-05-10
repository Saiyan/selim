<?php

namespace Selim\Commands;

use Selim\SecurityChecker;
use Selim\SilverstripePage;
use Selim\Util;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SecurityCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('security')
            ->setDescription('check security of a site')
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
            echo "Security-test for $name:".PHP_EOL;
            $site = $cfg->getSite($name);
            $sc = SecurityChecker::getInstance();
            $issues = $sc->findIssues(new SilverstripePage($site), true);

            if(count($issues)) {
                foreach ($issues as $iss) {
                    echo $iss;
                }
            }else{
                echo "No security issues found.";
            }
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }

}