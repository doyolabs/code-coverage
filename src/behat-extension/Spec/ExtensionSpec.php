<?php

namespace Spec\Doyo\Behat\CodeCoverage;

use Doyo\Behat\CodeCoverage\Extension;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Extension::class);
    }

    function it_should_be_a_behat_extension()
    {
        $this->shouldImplement(\Behat\Testwork\ServiceContainer\Extension::class);
    }
}
