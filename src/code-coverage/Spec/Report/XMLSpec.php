<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\Clover;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use Doyo\Bridge\CodeCoverage\Report\XML;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade;

class XMLSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(XML::class);
    }

    function it_should_be_coverage_xml_report()
    {
        $this->shouldBeAnInstanceOf(ReportProcessorInterface::class);
        $this->getProcessorClass()->shouldReturn(Facade::class);
        $this->getOutputType()->shouldReturn(Clover::OUTPUT_DIR);
        $this->getType()->shouldReturn('xml');
    }
}
