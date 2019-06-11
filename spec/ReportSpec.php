<?php

namespace spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Report;
use Doyo\Bridge\CodeCoverage\Exception\ReportException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;

class ReportSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Report::class);
    }

    function it_should_listen_to_coverage_report_event()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
        $this::getSubscribedEvents()->shouldHaveKeyWithValue(CoverageEvent::report,'generate');
    }

    function its_processor_should_be_mutable(
        ReportProcessorInterface $processor
    )
    {
        $processor->getType()->shouldBeCalledOnce()
            ->willReturn('type');

        $this->getProcessors()->shouldReturn([]);
        $this->addProcessor($processor);
        $this->hasProcessor('type')->shouldBe(true);
        $this->getProcessors()->shouldHaveKeyWithValue('type', $processor);
    }

    function its_generate_should_create_code_coverage_reports(
        ReportProcessorInterface $reportSuccess,
        ReportProcessorInterface $reportFail,
        ConsoleIO $consoleIO,
        CoverageEvent $coverageEvent,
        ProcessorInterface $processor
    )
    {
        $coverageEvent
            ->getProcessor()
            ->shouldBeCalledOnce()
            ->willReturn($processor)
        ;
        $coverageEvent
            ->getConsoleIO()
            ->shouldBeCalledOnce()
            ->willReturn($consoleIO);

        $reportSuccess->getType()->willReturn('report-success');
        $reportSuccess->getTarget()->willReturn('target');
        $reportSuccess
            ->process($processor, $consoleIO)
            ->shouldBeCalledOnce()
        ;

        $this->addProcessor($reportSuccess);
        $this->generate($coverageEvent);
    }
}
