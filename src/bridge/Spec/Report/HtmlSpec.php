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
use Doyo\Bridge\CodeCoverage\Report\Html;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use PhpSpec\ObjectBehavior;

class HtmlSpec extends ObjectBehavior
{
    protected $tempDir;

    public function let()
    {
        $this->tempDir = sys_get_temp_dir().'/doyo/test-report';

        $options = [
            'target'         => $this->tempDir,
            'type'           => 'html',
            'fileSystemType' => 'dir',
            'lowUpperBound'  => 20,
            'highLowerBound' => 90,
            'generator'      => 'some-generator',
        ];
        $this->beConstructedWith($options);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Html::class);
    }

    public function it_should_be_a_report_processor()
    {
        $this->shouldBeAnInstanceOf(AbstractReportProcessor::class);
        $this->shouldImplement(ReportProcessorInterface::class);
    }

    public function it_should_be_create_processor_properly()
    {
        $this->getType()->shouldReturn('html');
        $this->getTarget()->shouldReturn($this->tempDir);
    }
}
