<?php

namespace spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\CodeCoverage;
use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use spec\Doyo\Bridge\CodeCoverage\TestSubscriber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

class CodeCoverageSpec extends ObjectBehavior
{
    function let(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime,
        TestSubscriber $subscriber
    )
    {
        $this->beConstructedWith($processor, $consoleIO, $runtime);
        $runtime->canCollectCodeCoverage()->willReturn(true);
        $this->addSubscriber($subscriber);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CodeCoverage::class);
    }

    function it_should_dispatch_coverage_refresh_event(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    )
    {
        $processor->clear()->shouldBeCalledOnce();
        $subscriber->refresh(Argument::cetera())->shouldBeCalledOnce();

        $this->refresh();
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

        $this->start($testCase);
    }

    function it_should_dispatch_coverage_event_stop(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    )
    {
        $processor->stop()->shouldBeCalledOnce();
        $subscriber
            ->stop(Argument::type(CoverageEvent::class), Argument::cetera())
            ->shouldBeCalledOnce();

        $this->stop();
    }

    function it_should_dispatch_coverage_event_complete(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    )
    {
        $processor->complete()->shouldBeCalledOnce();
        $subscriber->complete(Argument::cetera())->shouldBeCalledOnce();
        $this->complete()->shouldHaveType(CoverageEvent::class);
    }
}
