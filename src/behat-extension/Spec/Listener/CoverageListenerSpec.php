<?php

namespace Spec\Doyo\Behat\CodeCoverage\Listener;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface;
use Behat\Testwork\EventDispatcher\Event\AfterTested;
use Behat\Testwork\Tester\Result\TestResult;
use Doyo\Behat\CodeCoverage\Listener\CoverageListener;
use Doyo\Bridge\CodeCoverage\CodeCoverageInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CoverageListenerSpec extends ObjectBehavior
{
    function let(
        CodeCoverageInterface $coverage
    )
    {
        $this->beConstructedWith($coverage, true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageListener::class);
    }

    function it_should_refresh_code_coverage(
        CodeCoverageInterface $coverage
    )
    {
        $coverage->refresh()->shouldBeCalledOnce();

        $this->refresh();
    }

    function it_should_start_code_coverage(
        CodeCoverageInterface $coverage,
        ScenarioTested $tested,
        ScenarioInterface $scenario,
        FeatureNode $feature
    )
    {
        $tested->getScenario()
            ->willReturn($scenario);
        $tested->getFeature()
            ->willReturn($feature);

        $scenario->getLine()->willReturn('line');
        $feature->getFile()->willReturn('file');

        $coverage
            ->start(Argument::type(TestCase::class))
            ->shouldBeCalledOnce();

        $this->start($tested);
    }

    function it_should_stop_code_coverage(
        AfterTested $tested,
        CodeCoverageInterface $coverage,
        TestResult $result
    )
    {
        $tested->getTestResult()->willReturn($result);
        $result->getResultCode()->willReturn(0);

        $coverage->setResult(0)->shouldBeCalledOnce();
        $coverage->stop()->shouldBeCalledOnce();

        $this->stop($tested);
    }

    function it_should_complete_code_coverage(
        CodeCoverageInterface $coverage
    )
    {
        $coverage->complete()->shouldBeCalledOnce();

        $this->complete();
    }
}
