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
            $sc = new SecurityChecker(new SilverstripePage($site));
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