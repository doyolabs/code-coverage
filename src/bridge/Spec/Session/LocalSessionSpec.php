<?php

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\Session\LocalSession;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocalSessionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('spec-local');
        $this->init(['env' => 'spec', 'debug' => true]);
        $this->reset();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(LocalSession::class);
    }

    /**
     * Note: Error should throw because of null TestCase
     */
    function its_startSession_should_handle_error()
    {
        /* @todo Recheck if exceptions really added to cache */
        $this::startSession('spec-local')->shouldBe(false);
        $this->refresh();
    }

    function it_should_start_session(
        TestCase $testCase
    )
    {
        $this->setTestCase($testCase);
        $this->save();

        $this::startSession('spec-local')->shouldBe(true);
    }
}
