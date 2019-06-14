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

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\Processor;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Driver\Driver;
use SebastianBergmann\CodeCoverage\Filter;

class ProcessorSpec extends ObjectBehavior
{
    public function let(
        Driver $driver
    ) {
        $filter    = new Filter();
        $coverage  = new CodeCoverage($driver->getWrappedObject(), $filter);
        $filter->addFileToWhitelist(__FILE__);
        $this->beConstructedWith($coverage);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Processor::class);
    }

    public function its_code_coverage_should_be_mutable()
    {
        $this->getCodeCoverage()->shouldHaveType(CodeCoverage::class);
    }

    public function its_currentTestCase_should_be_mutable(
        TestCase $testCase
    ) {
        $this->getCurrentTestCase()->shouldBeNull();
        $this->setCurrentTestCase($testCase);
        $this->getCurrentTestCase()->shouldReturn($testCase);
    }

    public function its_stop_should_be_callable(
        Driver $driver,
        TestCase $testCase
    ) {
        $testCase->getName()->shouldBeCalled()->willReturn('some-id');
        $driver->start(true)->shouldBeCalled();
        $driver->stop()->shouldBeCalled()->willReturn([]);
        $this->start($testCase);
        $this->stop();
    }

    public function it_should_patch_coverage_data_when_test_completed(
        TestCase $testCase
    ) {
        $testCase->getName()->willReturn('some-test');
        $testCase->getResult()->willReturn(TestCase::RESULT_PASSED);

        $this->addTestCase($testCase);
        $this->complete();

        $coverage = $this->getCodeCoverage();
        $coverage->getTests()->shouldHaveKey('some-test');
        $coverage->getTests()->shouldHaveKeyWithValue('some-test', ['status' => TestCase::RESULT_PASSED]);
    }
}
