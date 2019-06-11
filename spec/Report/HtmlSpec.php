<?php

namespace spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;
use Doyo\Bridge\CodeCoverage\Report\Html;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SebastianBergmann\CodeCoverage\Report\Html\Facade as HtmlReportProcessor;

class HtmlSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Html::class);
    }

    function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->shouldImplement(ReportProcessorInterface::class);
    }
}
