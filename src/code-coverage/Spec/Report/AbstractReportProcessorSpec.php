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

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

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

    public function let()
    {
        $this->beAnInstanceOf(TestAbstractReportProcessor::class);
        $this->defaultOptions = [
            'target'         => sys_get_temp_dir().'/doyo/report/target',
            'type'           => 'test',
            'fileSystemType' => 'file',
        ];
    }

    public function it_is_initializable()
    {
        $this->beConstructedWith($this->defaultOptions);
        $this->shouldHaveType(AbstractReportProcessor::class);
    }

    public function it_should_create_processor_based_on_their_default_value()
    {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $this->getTarget()->shouldReturn($options['target']);

        $this->getProcessor()->getFoo()->shouldReturn('Foo Bar');
        $this->getProcessor()->getHello()->shouldReturn('Hello World');
    }

    public function it_should_customize_constructor_parameters_for_processor()
    {
        $options = array_merge($this->defaultOptions, [
            'hello' => 'Hello World Foo Bar',
        ]);
        $this->beConstructedWith($options);

        $this->getProcessor()->getFoo()->shouldReturn('Foo Bar');
        $this->getProcessor()->getHello()->shouldReturn('Hello World Foo Bar');
    }

    public function it_should_process_report(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    ) {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $coverage = new CodeCoverage(new Dummy());
        $processor->getCodeCoverage()->shouldBeCalled()->willReturn($coverage);
        $consoleIO->coverageInfo(Argument::containingString($options['target']))->shouldBeCalled();

        $this->process($processor, $consoleIO);
    }

    public function it_should_handle_report_process_error(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    ) {
        $options = $this->defaultOptions;
        $this->beConstructedWith($options);
        $processor->getCodeCoverage()->shouldBeCalled()->willThrow(new \Exception('some-error'));
        $consoleIO->coverageInfo(Argument::containingString($options['type']))->shouldBeCalled();
        $consoleIO->coverageInfo(Argument::containingString('some-error'))->shouldBeCalled();

        $this->process($processor, $consoleIO);
    }
}
