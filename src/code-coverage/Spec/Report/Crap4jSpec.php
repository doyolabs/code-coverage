<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\Clover;
use Doyo\Bridge\CodeCoverage\Report\Crap4j;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class Crap4jSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Crap4j::class);
    }

    function it_should_be_coverage_crap4j_report()
    {
        $this->shouldBeAnInstanceOf(ReportProcessorInterface::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\Crap4j::class);
        $this->getOutputType()->shouldReturn(Clover::OUTPUT_FILE);
        $this->getType()->shouldReturn('crap4j');
    }
}
