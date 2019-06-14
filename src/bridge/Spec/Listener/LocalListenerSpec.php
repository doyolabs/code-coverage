<?php

/*
 * This file is part of the doyo/code-coverage project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spec\Doyo\Bridge\CodeCoverage\Listener;

use Doyo\Bridge\CodeCoverage\Console\ConsoleIO;
use Doyo\Bridge\CodeCoverage\Event\CoverageEvent;
use Doyo\Bridge\CodeCoverage\Exception\SessionException;
use Doyo\Bridge\CodeCoverage\Listener\LocalListener;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\SessionInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocalListenerSpec extends ObjectBehavior
{
    public function let(
        SessionInterface $session,
        CoverageEvent $event,
        ProcessorInterface $processor,
        TestCase $testCase,
        ConsoleIO $consoleIO
    ) {
        $event->getProcessor()->willReturn($processor);
        $event->getConsoleIO()->willReturn($consoleIO);

        $processor->getCurrentTestCase()->willReturn($testCase);

        $this->beConstructedWith($session);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LocalListener::class);
    }

    public function it_should_subscribe_to_coverage_event()
    {
        $this->shouldImplement(EventSubscriberInterface::class);
        $this::getSubscribedEvents()->shouldHaveKey(CoverageEvent::refresh);
        $this::getSubscribedEvents()->shouldHaveKey(CoverageEvent::start);
        $this::getSubscribedEvents()->shouldHaveKey(CoverageEvent::complete);
    }

    public function its_refresh_should_reset_session(
        SessionInterface $session
    ) {
        $session->reset()->shouldBeCalledOnce();

        $this->refresh();
    }

    public function its_start_should_set_test_case(
        SessionInterface $session,
        TestCase $testCase,
        CoverageEvent $event
    ) {
        $session->setTestCase($testCase)->shouldBeCalledOnce();
        $session->save()->shouldBeCalledOnce();

        $this->start($event);
    }

    public function its_complete_should_merge_coverage(
        SessionInterface $session,
        CoverageEvent $event,
        ProcessorInterface $processor,
        ProcessorInterface $sessionProcessor,
        ConsoleIO $consoleIO
    ) {
        $e = new SessionException('test');

        $session->getProcessor()->willReturn($sessionProcessor);
        $session->hasExceptions()->willReturn(true);
        $session->getExceptions()->willReturn([$e]);

        $session->refresh()->shouldBeCalledOnce();
        $processor->merge($sessionProcessor)->shouldBeCalledOnce();

        $consoleIO
            ->coverageInfo(Argument::containingString('test'))
            ->shouldBeCalledOnce();

        $this->complete($event);
    }
}
