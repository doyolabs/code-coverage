<?php

namespace spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Dispatcher;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Doyo\Bridge\CodeCoverage\TestSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

class DispatcherSpec extends ObjectBehavior
{
    function let(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    )
    {
        $this->beConstructedWith($processor, $consoleIO);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Dispatcher::class);
    }

    function it_should_dispatch_coverage_event_start(
        ProcessorInterface $processor,
        TestCase $testCase,
        TestSubscriber $subscriber
    )
    {
        $subscriber->beforeStart(Argument::cetera())->shouldBeCalled();
        $subscriber->start(Argument::cetera())->shouldBeCalled();

        $processor->start($testCase)->shouldBeCalled();
        $this->addSubscriber($subscriber);
        $coverageEvent = $this->start($testCase);

        $coverageEvent->getTestCase()->shouldReturn($testCase);
    }
}
