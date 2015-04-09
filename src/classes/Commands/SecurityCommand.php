<?php

namespace Selim\Commands;

use Selim\Util;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SecuritySiteCommand extends SelimCommand{
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
        parent::execute($input,$output);
        $name = $input->getArgument('name');

        if ($this->config->siteExists($name)) {
            echo "Security-test for $name:".PHP_EOL;
            $site = $this->config->getSite($name);
            $sc = new \Selim\SecurityChecker(new \Selim\SilverstripePage($site));
            $vulns = $sc->findVulnerabilities(true);
            foreach ($vulns as $vul) {
                $severity = $vul["severity"] ? $vul["severity"] : "Warning";
                Util::forceStringMinLength($severity, 9);
                echo "$severity ".$vul["title"].PHP_EOL;
            }
        } else {
            Util::reportError("Site with name '$name' doesn't exists!");
        }
    }

}