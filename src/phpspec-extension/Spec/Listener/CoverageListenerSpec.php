<?php

namespace Spec\Doyo\PhpSpec\CodeCoverage\Listener;

use Doyo\Bridge\CodeCoverage\CodeCoverageInterface;
use Doyo\PhpSpec\CodeCoverage\Listener\CoverageListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoverageListenerSpec extends ObjectBehavior
{
    function let(CodeCoverageInterface $coverage)
    {
        $this->beConstructedWith($coverage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageListener::class);
    }
}
