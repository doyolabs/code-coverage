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

namespace Spec\Doyo\Bridge\CodeCoverage\Event;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Environment\RuntimeInterface;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use PhpSpec\ObjectBehavior;

class CoverageEventSpec extends ObjectBehavior
{
    public function let(
        ProcessorInterface $processor,
        ConsoleIO $consoleIO,
        RuntimeInterface $runtime
    ) {
        $this->beConstructedWith($processor, $consoleIO, $runtime);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CoverageEvent::class);
    }

    public function its_properties_should_be_mutable(
        ProcessorInterface $processor,
        RuntimeInterface $runtime,
        ConsoleIO $consoleIO
    ) {
        $this->getProcessor()->shouldReturn($processor);
        $this->getRuntime()->shouldReturn($runtime);
        $this->getConsoleIO()->shouldReturn($consoleIO);
    }
}
