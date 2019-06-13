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

use Doyo\Bridge\CodeCoverage\Report\Clover;
use Doyo\Bridge\CodeCoverage\Report\ReportProcessorInterface;
use Doyo\Bridge\CodeCoverage\Report\XML;
use PhpSpec\ObjectBehavior;
use SebastianBergmann\CodeCoverage\Report\Xml\Facade;

class XMLSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(XML::class);
    }

    public function it_should_be_coverage_xml_report()
    {
        $this->shouldBeAnInstanceOf(ReportProcessorInterface::class);
        $this->getProcessorClass()->shouldReturn(Facade::class);
        $this->getOutputType()->shouldReturn(Clover::OUTPUT_DIR);
        $this->getType()->shouldReturn('xml');
    }
}
