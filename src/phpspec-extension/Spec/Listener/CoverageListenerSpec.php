<?php

namespace Spec\Doyo\PhpSpec\CodeCoverage\Listener;

use Doyo\PhpSpec\CodeCoverage\Listener\CoverageListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoverageListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageListener::class);
    }
}
