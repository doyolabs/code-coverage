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

namespace Spec\Doyo\Bridge\CodeCoverage;

use Doyo\Bridge\CodeCoverage\ContainerFactory;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContainerFactorySpec.
 *
 * @covers \Doyo\Bridge\CodeCoverage\ContainerFactory
 */
class ContainerFactorySpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith([], true);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerFactory::class);
    }

    /**
     * @covers \Doyo\Bridge\CodeCoverage\ContainerFactory
     */
    public function it_should_create_container()
    {
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }
}
