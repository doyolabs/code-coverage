<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <me@itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\CodeCoverage;
use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CodeCoverageSpec.
 *
 * @covers \Doyo\Bridge\CodeCoverage\CodeCoverage
 */
class CodeCoverageSpec extends ObjectBehavior
{
    public function let(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime,
        TestSubscriber $subscriber
    ) {
        $this->beConstructedWith($processor, $consoleIO, $runtime);
        $runtime->canCollectCodeCoverage()->willReturn(true);
        $this->addSubscriber($subscriber);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CodeCoverage::class);
    }

    public function it_should_dispatch_coverage_refresh_event(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    ) {
        $processor->clear()->shouldBeCalledOnce();
        $subscriber->refresh(Argument::cetera())->shouldBeCalledOnce();

        $this->refresh();
    }

    public function it_should_dispatch_coverage_event_start(
        ProcessorInterface $processor,
        TestCase $testCase,
        TestSubscriber $subscriber
    ) {
        $subscriber->beforeStart(Argument::cetera())->shouldBeCalled();
        $subscriber->start(Argument::cetera())->shouldBeCalled();

        $processor->start($testCase)->shouldBeCalled();

        $this->start($testCase);
    }

    public function it_should_dispatch_coverage_event_stop(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    ) {
        $processor->stop()->shouldBeCalledOnce();
        $subscriber
            ->stop(Argument::type(CoverageEvent::class), Argument::cetera())
            ->shouldBeCalledOnce();

        $this->stop();
    }

    public function it_should_dispatch_coverage_event_complete(
        ProcessorInterface $processor,
        TestSubscriber $subscriber
    ) {
        $processor->complete()->shouldBeCalledOnce();
        $subscriber->complete(Argument::cetera())->shouldBeCalledOnce();
        $this->complete()->shouldHaveType(CoverageEvent::class);
    }

    public function its_complete_display_error_when_no_coverage_driver_available(
        RuntimeInterface $runtime,
        ConsoleIO $consoleIO
    )
    {
        $runtime->canCollectCodeCoverage()->willReturn(false);
        $consoleIO->coverageError(Argument::containingString('code coverage driver'))->shouldBeCalledOnce();
        $this->complete();
    }
}
