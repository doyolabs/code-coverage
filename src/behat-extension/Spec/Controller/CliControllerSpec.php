<?php

namespace Spec\Doyo\Behat\CodeCoverage\Controller;

use Behat\Testwork\Cli\Controller;
use Doyo\Behat\CodeCoverage\Controller\CliController;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Command\Command;

class CliControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CliController::class);
    }

    function it_should_be_a_behat_cli_controller()
    {
        $this->shouldImplement(Controller::class);
    }

    function it_should_add_coverage_option(
        Command $command
    )
    {
        $command
            ->addOption('coverage', Argument::cetera())
            ->shouldBeCalledOnce();

        $this->configure($command);
    }
}
