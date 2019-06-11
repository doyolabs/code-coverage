<?php

namespace spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SebastianBergmann\CodeCoverage\CodeCoverage;

class AbstractReportProcessorSpec extends ObjectBehavior
{
    private $defaultOptions;

    function let()
    {
        $this->beAnInstanceOf(TestAbstractReportProcessor::class);
        $this->defaultOptions = [
            'target' => sys_get_temp_dir().'/doyo/report/target',
            'type' => 'test',
            'fileSystemType' => 'file',
        ];
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AbstractReportProcessor::class);
    }

    function it_should_create_processor_based_on_their_default_value()
    {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $this->getType()->shouldReturn($options['type']);
        $this->getTarget()->shouldReturn($options['target']);
        $this->getFileSystemType()->shouldReturn($options['fileSystemType']);

        $this->getProcessor()->getFoo()->shouldReturn('Foo Bar');
        $this->getProcessor()->getHello()->shouldReturn('Hello World');
    }

    function it_should_customize_constructor_parameters_for_processor()
    {
        $options = array_merge($this->defaultOptions, [
            'hello' => 'Hello World Foo Bar',
        ]);
        $this->beConstructedWith($options);

        $this->getProcessor()->getFoo()->shouldReturn('Foo Bar');
        $this->getProcessor()->getHello()->shouldReturn('Hello World Foo Bar');
    }

    function it_should_process_report(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    )
    {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $coverage = new CodeCoverage(new Dummy());
        $processor->getCodeCoverage()->shouldBeCalled()->willReturn($coverage);
        $consoleIO->coverageInfo(Argument::containingString($options['target']))->shouldBeCalled();

        $this->process($processor, $consoleIO);
    }

    function it_should_handle_report_process_error(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    )
    {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $processor->getCodeCoverage()->shouldBeCalled()->willThrow(new \Exception('some-error'));
        $consoleIO->coverageError(Argument::containingString($options['type']))->shouldBeCalled();
        $consoleIO->coverageError(Argument::containingString('some-error'))->shouldBeCalled();

        $this->process($processor, $consoleIO);
    }
}
