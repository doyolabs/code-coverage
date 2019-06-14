<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\Session\AbstractSession;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractSessionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(TestSession::class);
        $this->beConstructedWith('abstract');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AbstractSession::class);
    }

    function it_should_init_session()
    {
        $config = [
            'filter' => [
                'whitelist' => [
                    'directory' => __DIR__
                ]
            ]
        ];

        $this->init($config);

        $this->save();
        $this->refresh();

        $this->getName()->shouldReturn('abstract');
        $this->getConfig()->shouldReturn($config);
    }
}
