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

use Doyo\Bridge\CodeCoverage\Report\AbstractReportProcessor;
use Doyo\Bridge\CodeCoverage\Report\PHP;
use PhpSpec\ObjectBehavior;

class PHPSpec extends ObjectBehavior
{
    public function let()
    {
        $options = [
            'target' => sys_get_temp_dir().'/doyo/report/test.cov',
        ];
        $this->beConstructedWith($options);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PHP::class);
    }

    public function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->getProcessorClass()->shouldReturn(\SebastianBergmann\CodeCoverage\Report\PHP::class);
        $this->getOutputType()->shouldReturn(PHP::OUTPUT_FILE);
        $this->getType()->shouldReturn('php');
    }
}
