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

namespace Spec\Doyo\Bridge\CodeCoverage\Session;

use Doyo\Bridge\CodeCoverage\Exception\SessionException;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use Doyo\Bridge\CodeCoverage\Session\AbstractSession;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractSessionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(TestSession::class);
        $this->beConstructedWith('abstract');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AbstractSession::class);
    }

    public function it_should_init_session(
        TestCase $testCase
    )
    {
        $config = [
            'filter' => [
                'whitelist' => [
                    'directory' => __DIR__,
                ],
            ],
        ];

        $this->setTestCase($testCase);
        $this->init($config);

        $this->refresh();

        $this->getName()->shouldReturn('abstract');
        $this->getProcessor()->shouldBeAnInstanceOf(ProcessorInterface::class);
    }

    public function its_exceptions_should_be_mutable()
    {
        $this->hasExceptions()->shouldReturn(false);
        $this->getExceptions()->shouldBeEqualTo([]);

        $e = new \Exception('test');
        $this->addException($e);
        $this->hasExceptions()->shouldBe(true);
        $this->getExceptions()->shouldContain($e);
    }

    public function its_start_throw_exception_when_TestCase_is_null()
    {
        $this->shouldThrow(SessionException::class)
            ->during('start');
    }

    public function it_should_start_and_stop_coverage(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $processor->merge(Argument::type(ProcessorInterface::class))
            ->shouldBeCalledOnce();
        $testCase->getName()->willReturn('some-test');

        $this->setProcessor($processor);
        $this->setTestCase($testCase);
        $this->start();
        $this->stop();

        $this->hasExceptions()->shouldBe(false);
    }

    public function its_start_should_handle_exception(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $e = new \Exception('some error');

        $testCase->getName()->willThrow($e);

        $this->setTestCase($testCase);
        $this->setProcessor($processor);
        $this->start();
        $this->hasExceptions()->shouldBe(true);
    }

    public function its_stop_should_handle_exception(
        ProcessorInterface $processor,
        TestCase $testCase
    )
    {
        $testCase->getName()->willReturn('test');
        $e = new \Exception('test');
        $processor->merge(Argument::any())->willThrow($e);
        $this->setProcessor($processor);
        $this->setTestCase($testCase);

        $this->start();
        $this->stop();

        $this->hasExceptions()->shouldBe(true);
    }

    public function it_should_shutdown_properly(
        TestCase $testCase
    )
    {
        $testCase->getName()->willReturn('test');
        $this->setTestCase($testCase);
        $this->start();
        $this->shutdown();
    }
}
