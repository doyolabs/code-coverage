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
    protected $tempDir;

    function let()
    {
        $this->tempDir = sys_get_temp_dir().'/doyo/test-report';

        $options = [
            'target' => $this->tempDir,
            'type' => 'html',
            'fileSystemType' => 'dir',
            'lowUpperBound' => 20,
            'highLowerBound' => 90,
            'generator' => 'some-generator'
        ];
        $this->beConstructedWith($options);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Html::class);
    }

    function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->shouldImplement(ReportProcessorInterface::class);
    }

    function it_should_be_create_processor_properly()
    {
        $this->getType()->shouldReturn('html');
        $this->getFileSystemType()->shouldReturn('dir');
        $this->getTarget()->shouldReturn($this->tempDir);
    }
}
