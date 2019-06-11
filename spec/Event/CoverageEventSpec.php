<?php

namespace spec\Doyo\Bridge\CodeCoverage\Event;

use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoverageEventSpec extends ObjectBehavior
{
    function let(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $this->beConstructedWith($processor, $testCase);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageEvent::class);
    }

    function its_properties_should_be_mutable(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $this->getProcessor()->shouldReturn($processor);
        $this->getTestCase()->shouldReturn($testCase);
    }
}
