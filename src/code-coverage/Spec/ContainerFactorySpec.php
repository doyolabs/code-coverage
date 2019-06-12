<?php

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\ContainerFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }
}