<?php

namespace Spec\Doyo\PhpSpec\CodeCoverage\Listener;

use Doyo\Bridge\CodeCoverage\CodeCoverageInterface;
use Doyo\Bridge\CodeCoverage\TestCase;
use Doyo\PhpSpec\CodeCoverage\Listener\CoverageListener;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class CoverageListenerSpec
 *
 * @covers \Doyo\PhpSpec\CodeCoverage\Listener\CoverageListener
 */
class CoverageListenerSpec extends ObjectBehavior
{
    function let(CodeCoverageInterface $coverage)
    {
        $this->beConstructedWith($coverage);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CoverageListener::class);
    }

    function its_beforeExample_should_start_coverage(
        ExampleEvent $exampleEvent,
        SpecificationNode $specification,
        ExampleNode $example,
        CodeCoverageInterface $coverage
    )
    {
        $exampleEvent->getExample()->willReturn($example);
        $example->getSpecification()->willReturn($specification);
        $specification->getTitle()->willReturn('spec')->shouldBeCalledOnce();
        $example->getTitle()->willReturn('title')->shouldBeCalledOnce();

        $coverage->start(Argument::type(TestCase::class))
            ->shouldBeCalled();
        $this->beforeExample($exampleEvent);
    }

    function its_afterExample_should_stop_coverage(
        ExampleEvent $exampleEvent,
        CodeCoverageInterface $coverage
    )
    {
        $exampleEvent->getResult()->shouldBeCalled()->willReturn(0);

        $coverage->setResult(0)->shouldBeCalled();
        $coverage->stop()->shouldBeCalled();

        $this->afterExample($exampleEvent);
    }

    function its_afterSuite_should_complete_coverage(
        CodeCoverageInterface $coverage
    )
    {
        $coverage->complete()->shouldBeCalledOnce();
        $this->afterSuite();
    }
}
