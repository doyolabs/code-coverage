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

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use Doyo\Bridge\CodeCoverage\Driver\Dummy;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\RemoteSession;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\Environment\Runtime;

class RemoteSessionSpec extends ObjectBehavior
{
    public function let(
        ProcessorInterface $processor,
        Dummy $driver
    ) {
        $filter = new Filter();
        $coverage = new CodeCoverage($driver->getWrappedObject(), $filter);
        $this->beConstructedWith('spec-remote', $coverage, false);
        $processor->clear()->willReturn(null);
        $this->setProcessor($processor);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RemoteSession::class);
    }

    public function it_should_init_coverage_session()
    {
        $this::init('spec-remote',[]);
        $this->getProcessor()->shouldImplement(ProcessorInterface::class);
    }

    public function it_should_start_new_session(
        ProcessorInterface $processor
    ) {
        $runtime = new Runtime();
        
        if (!$runtime->canCollectCodeCoverage()) {
            throw new SkippingException('not in phpdbg or xdebug');
        }

        $_SERVER[RemoteSession::HEADER_SESSION_KEY]   = 'spec-remote';
        $_SERVER[RemoteSession::HEADER_TEST_CASE_KEY] = 'spec-test-case';

        $this->setProcessor($processor);
        $this->save();

        $this->startSession()->shouldReturn(true);
        $this->refresh();
        $this->getName()->shouldReturn('spec-remote');
        $this->getTestCase()->shouldHaveType(TestCase::class);
        $this->getTestCase()->getName()->shouldReturn('spec-test-case');
    }

    public function it_should_not_start_session_with_undefined_session()
    {
        $this->reset();
        unset($_SERVER[RemoteSession::HEADER_SESSION_KEY]);
        unset($_SERVER[RemoteSession::HEADER_TEST_CASE_KEY]);
        $this->startSession()->shouldBe(false);

        $_SERVER[RemoteSession::HEADER_SESSION_KEY] = 'spec-remote';

        $this->startSession()->shouldBe(false);
    }

    public function its_doStartSession_should_start_coverage()
    {
        $runtime = new Runtime();
        if (!$runtime->canCollectCodeCoverage()) {
            throw new SkippingException('not in phpdbg or xdebug');
        }

        $this->reset();
        $_SERVER[RemoteSession::HEADER_SESSION_KEY]   = 'spec-remote';
        $_SERVER[RemoteSession::HEADER_TEST_CASE_KEY] = 'test-case';

        $this->doStartSession();
        $this->save();
        $this->refresh();
        $this->getTestCase()->shouldBeAnInstanceOf(TestCase::class);
    }

    public function its_doStartSession_should_start_coverage_error() {
        $this->reset();
        $_SERVER[RemoteSession::HEADER_SESSION_KEY]   = 'spec-remote';
        $_SERVER[RemoteSession::HEADER_TEST_CASE_KEY] = 'test-case';

        $this->hasExceptions()->shouldBe(false);
        $this->doStartSession();
        $this->hasExceptions()->shouldBe(true);
    }
}
