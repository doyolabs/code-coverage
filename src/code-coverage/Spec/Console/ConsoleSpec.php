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

namespace Spec\Doyo\Bridge\CodeCoverage\Console;

use Doyo\Bridge\CodeCoverage\Console\Console;
use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Style\StyleInterface;

class ConsoleSpec extends ObjectBehavior
{
    public function let(
        StyleInterface $style
    ) {
        $this->beConstructedWith($style);
    }

    public function it_should_be_a_ConsoleIO()
    {
        $this->shouldHaveType(Console::class);
        $this->shouldImplement(ConsoleIO::class);
    }

    public function its_coverageSection_should_write_section_output(
        StyleInterface $style
    ) {
        $style->section('coverage: foo')->shouldBeCalledOnce();
        $this->coverageSection('foo');
    }

    public function its_coverageInfo_should_write_info_output(
        StyleInterface $style
    ) {
        $style->text('info')->shouldBeCalledOnce();
        $this->coverageInfo('info');
    }

    public function its_coverageInfo_should_write_error_output(
        StyleInterface $style
    ) {
        $style->error('foo')->shouldBeCalledOnce();
        $this->coverageError('foo');
    }
}
