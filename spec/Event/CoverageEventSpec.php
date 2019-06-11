<?php

namespace spec\Doyo\Bridge\CodeCoverage\Event;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoverageEventSpec extends ObjectBehavior
{
    function let(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime
    )
    {
        $this->beConstructedWith($processor, $consoleIO, $runtime);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageEvent::class);
    }

    function its_properties_should_be_mutable(
        ProcessorInterface $processor,
        RuntimeInterface $runtime,
        ConsoleIO $consoleIO
    )
    {
        $this->getProcessor()->shouldReturn($processor);
        $this->getRuntime()->shouldReturn($runtime);
        $this->getConsoleIO()->shouldReturn($consoleIO);
    }
}
