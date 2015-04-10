<?php

namespace Selim\Commands;

use File_Iterator_Factory;
use Selim\Util;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class FindSitesCommand extends SelimCommand{
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('find')
            ->setDescription('Find silverstripe sites on your hdd')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path where the script should start searching recursively'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cfg = $this->getSelimConfig($input);
        $path = realpath($input->getArgument("path"));
        $projects = array();
        $question_helper =  $this->getHelper("question");

        $output->write("Start searching _config.php files...");
        $fact = new File_Iterator_Factory();
        $iterator = $fact->getFileIterator($path,"php","_config");
        $output->writeln("OK");

        $output->write("Filter for project paths...");
        while($file = $iterator->current()){
            $content = Util::stripPhpComments(file_get_contents($file));
            if(preg_match("/\\\$project\\s=/",$content)){
                array_push($projects,dirname($file));
            }
            $iterator->next();
        }
        $output->writeln("OK");

        if(count($projects)){
            $sites_added = false;
            $output->writeln("found ".count($projects)." possible sites");
            foreach($projects as $p){
                $question = new Question("Please enter name for '$p' (leave empty to skip)");
                do {
                    $name = $question_helper->ask($input, $output, $question);
                }while($cfg->siteExists($name));

                if($name){
                    $sites_added = true;
                    $cfg->addSite($name,$p);
                }
            }
            if($sites_added) {
                $output->write("Writing config.json ...");
                $cfg->write();
                $output->writeln("OK");
            }
        }
    }
}