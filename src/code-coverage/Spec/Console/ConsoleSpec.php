<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Console;

use Doyo\Bridge\CodeCoverage\Console\Console;
use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Console\Style\StyleInterface;

class ConsoleSpec extends ObjectBehavior
{
    function let(
        StyleInterface $style
    )
    {
        $this->beConstructedWith($style);
    }

    function it_should_be_a_ConsoleIO()
    {
        $this->shouldHaveType(Console::class);
        $this->shouldImplement(ConsoleIO::class);
    }

    function its_coverageSection_should_write_section_output(
        StyleInterface $style
    )
    {
        $style->section('coverage: foo')->shouldBeCalledOnce();
        $this->coverageSection('foo');
    }

    function its_coverageInfo_should_write_info_output(
        StyleInterface $style
    )
    {
        $style->text('info')->shouldBeCalledOnce();
        $this->coverageInfo('info');
    }

    function its_coverageInfo_should_write_error_output(
        StyleInterface $style
    )
    {
        $style->error('foo')->shouldBeCalledOnce();
        $this->coverageError('foo');
    }
}
