<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\Session\RemoteSession;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RemoteSessionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('spec-remote');
        $this->init([
            'env' => 'spec',
            'debug' => true
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RemoteSession::class);
    }

    function its_startSession_returns_false_with_empty_server_session_key()
    {
        $this::startSession()->shouldBe(false);
    }

    function its_startSession_returns_false_with_empty_server_test_case_key()
    {
        $_SERVER[RemoteSession::HEADER_SESSION_KEY] = 'spec-remote';

        $this::startSession()->shouldBe(false);
    }


    function it_should_start_session()
    {
        $_SERVER[RemoteSession::HEADER_SESSION_KEY] = 'spec-remote';
        $_SERVER[RemoteSession::HEADER_TEST_CASE_KEY] = 'test';

        $this::startSession()->shouldBe(true);
    }
}
