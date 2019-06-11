<?php

namespace spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Dispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DispatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Dispatcher::class);
    }
}
