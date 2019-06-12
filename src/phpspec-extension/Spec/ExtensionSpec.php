<?php

namespace Spec\Doyo\PhpSpec\CodeCoverage;

use Doyo\PhpSpec\CodeCoverage\Extension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Extension::class);
    }

    function it_should_be_phpspec_extension()
    {
        $this->shouldImplement(\PhpSpec\Extension::class);
    }
}
