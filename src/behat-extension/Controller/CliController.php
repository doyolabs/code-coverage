<?php

namespace Doyo\Behat\CodeCoverage\Controller;

use Behat\Testwork\Cli\Controller;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CliController implements Controller
{
    public function configure(SymfonyCommand $command)
    {
        $command->addOption('coverage', null, InputOption::VALUE_NONE, 'Collecting code coverage');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // TODO: Implement execute() method.
    }
}
