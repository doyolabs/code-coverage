<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;
use Doyo\Bridge\CodeCoverage\Report\PHP;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PHPSpec extends ObjectBehavior
{
    function let()
    {
        $options = [
            'target' => sys_get_temp_dir().'/doyo/report/test.cov'
        ];
        $this->beConstructedWith($options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PHP::class);
    }

    function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\PHP::class);
        $this->getOutputType()->shouldReturn(PHP::OUTPUT_FILE);
        $this->getType()->shouldReturn('php');
    }
}
