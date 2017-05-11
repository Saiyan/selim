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
                null,
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by name'
            )->addOption(
                'filter-version',
                null,
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by version'
            )->addOption(
                'filter-module',
                null,
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by installed modules'
            )->addOption(
                'template',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to a twig template which will be used to print out the pages.'
            )->addOption(
                'html',
                null,
                InputOption::VALUE_NONE,
                'Print all sites with the default html template.'
            )->addOption(
                'table',
                null,
                InputOption::VALUE_NONE,
                'Print all sites as a table'
            )->addOption(
                'filter-da',
                null,
                InputOption::VALUE_NONE,
                'regex, filter sites where Security::setDefaultAdmin() is used in config.php'
            )->addOption(
                'filter-env',
                null,
                InputOption::VALUE_REQUIRED,
                'regex, filter sites by environment type'
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

        if ($input->getOption("filter-da")) {
            $sspages = Util::filterPagesByDefaultAdmin($sspages, true);
        }

        $filter_env = $input->getOption("filter-env");
        if ($input->getOption("filter-env")) {
            $sspages = Util::filterPagesByEnvironmentType($sspages, $filter_env);
        }

        if ($input->getOption("table")) {
            $out = new ConsoleOutputTable($sspages);
        } else {
            $out = new ConsoleOutput($sspages);
            if ($input->getOption("html")) {
                $out->setTemplate('default_html.twig');
            }
        }

        $template = $input->getOption("template");
        if (strlen($template)) {
            $out->write($template);
        } else {
            $out->write();
        }
    }
}