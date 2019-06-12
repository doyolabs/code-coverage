<?php

namespace spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\ContainerFactory;
use Doyo\Bridge\CodeCoverage\Environment\Runtime;
use Doyo\Bridge\CodeCoverage\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;

class ContainerFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([], true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContainerFactory::class);
    }

    function it_should_create_container()
    {
        $container = $this->getContainer();

        $container->shouldBeAnInstanceOf(ContainerInterface::class);
        $container->get('report')->shouldHaveType(Report::class);
        $container->get('runtime')->shouldHaveType(Runtime::class);
    }
}
