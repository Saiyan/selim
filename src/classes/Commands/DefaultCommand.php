<?php

namespace Selim\Commands;

use Selim\ConsoleOutput;
use Selim\ConsoleOutputTable;
use Selim\SilverstripePage;
use Selim\SiteConfig;
use Selim\Util;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('start')
            ->setDescription('Analyze all sites added.')
            ->addOption(
                'filter-name',
                'fn',
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by name'
            )->addOption(
                'filter-version',
                'fv',
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by version'
            )->addOption(
                'filter-module',
                'fm',
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by installed modules'
            )->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Define a output format.'
            )->addOption(
                'table',
                't',
                InputOption::VALUE_NONE,
                'Print all sites as a table'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cfg = $this->getSelimConfig($input);
        $sites = $cfg->getSites();

        $filter_name = $input->getOption("filter-name");
        if (strlen($filter_name)) {
            $sites = Util::filterSitesByName($sites, $filter_name);
        }

        $sspages = array();
        foreach ($sites as $sc) {
            if ($sc instanceof SiteConfig) {
                array_push($sspages, new SilverstripePage($sc));
            }
        }

        $filter_version = $input->getOption("filter-version");
        if (strlen($filter_version)) {
            $sspages = Util::filterPagesByVersion($sspages, $filter_version);
        }

        $filter_module = $input->getOption("filter-module");
        if (strlen($filter_module)) {
            $sspages = Util::filterPagesByModules($sspages, $filter_module);
        }

        if ($input->getOption("table")) {
            $out = new ConsoleOutputTable($sspages);
        } else {
            $out = new ConsoleOutput($sspages);
        }

        $format = $input->getOption("format");
        if (strlen($format)) {
            $out->write($format);
        } else {
            $out->write();
        }
    }
}