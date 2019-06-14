<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spec\Doyo\Bridge\CodeCoverage\Report;

use Doyo\Bridge\CodeCoverage\Report\Clover;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;

class CloverSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Clover::class);
    }

    public function it_should_be_coverage_clover_report()
    {
        $this->shouldBeAnInstanceOf(ReportProcessorInterface::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\Clover::class);
        $this->getOutputType()->shouldReturn(Clover::OUTPUT_FILE);
        $this->getType()->shouldReturn('clover');
    }
}
