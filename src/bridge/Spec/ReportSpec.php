<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Report;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ReportSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Report::class);
    }

    public function it_should_listen_to_coverage_report_event()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
        $this::getSubscribedEvents()->shouldHaveKeyWithValue(CoverageEvent::report, 'generate');
    }

    public function its_processor_should_be_mutable(
        ReportProcessorInterface $processor
    ) {
        $processor->getType()->shouldBeCalledOnce()
            ->willReturn('type');

        $this->getProcessors()->shouldReturn([]);
        $this->addProcessor($processor);
        $this->hasProcessor('type')->shouldBe(true);
        $this->getProcessors()->shouldHaveKeyWithValue('type', $processor);
    }

    public function its_generate_should_create_code_coverage_reports(
        ReportProcessorInterface $reportSuccess,
        ConsoleIO $consoleIO,
        CoverageEvent $coverageEvent,
        ProcessorInterface $processor
    ) {
        $coverageEvent
            ->getProcessor()
            ->shouldBeCalledOnce()
            ->willReturn($processor);
        $coverageEvent
            ->getConsoleIO()
            ->shouldBeCalledOnce()
            ->willReturn($consoleIO);

        $reportSuccess->getType()->willReturn('report-success');
        $reportSuccess->getTarget()->willReturn('target');
        $reportSuccess
            ->process($processor, $consoleIO)
            ->shouldBeCalledOnce();

        $this->addProcessor($reportSuccess);
        $this->generate($coverageEvent);
    }
}
