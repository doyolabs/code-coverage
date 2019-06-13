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
use Doyo\Bridge\CodeCoverage\Report\PHP;
use Doyo\Bridge\CodeCoverage\Report\Text;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use Webmozart\Assert\Assert;

class TextSpec extends ObjectBehavior
{
    public function let(
        ProcessorInterface $processor
    ) {
        $coverage = new CodeCoverage(new Dummy());
        $processor->getCodeCoverage()->willReturn($coverage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Text::class);
    }

    public function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\Text::class);
        $this->getOutputType()->shouldReturn(PHP::OUTPUT_CONSOLE);
        $this->getType()->shouldReturn('text');
    }

    public function it_should_produce_output_to_console(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    ) {
        $consoleIO->coverageInfo(Argument::containingString('Code Coverage Report:'))->shouldBeCalledOnce();

        $this->process($processor, $consoleIO);
    }

    public function it_should_produce_output_to_text(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO
    ) {
        $target            = sys_get_temp_dir().'/doyo/report/coverage.txt';
        $options['target'] = $target;

        $this->beConstructedWith($options);

        $this->process($processor, $consoleIO);
        Assert::directory(\dirname($target));
        Assert::file($target);
    }
}
