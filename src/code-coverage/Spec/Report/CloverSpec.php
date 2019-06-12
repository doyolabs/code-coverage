<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\Clover;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CloverSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Clover::class);
    }

    function it_should_be_coverage_clover_report()
    {
        $this->shouldBeAnInstanceOf(ReportProcessorInterface::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\Clover::class);
        $this->getOutputType()->shouldReturn(Clover::OUTPUT_FILE);
        $this->getType()->shouldReturn('clover');
    }
}
