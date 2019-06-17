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

use Prophecy\Argument;
use SebastianBergmann\CodeCoverage\Filter;
use Doyo\Bridge\CodeCoverage\Console\Application;
use Doyo\Bridge\CodeCoverage\ContainerFactory;
use Doyo\Bridge\CodeCoverage\ProcessorInterface;
use PhpSpec\ObjectBehavior;
use SebastianBergmann\CodeCoverage\CodeCoverage;
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
        $this->beConstructedWith([
            'debug' => true,
            'env' => 'spec',
            'imports' => [
                __DIR__.'/Fixtures/test-coverage.yaml'
            ]
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ContainerFactory::class);
    }

    function it_should_handle_imports()
    {
        $filter = $this->getContainer()->get('coverage.filter');
        $filter->shouldReturnAnInstanceOf(Filter::class);
        $filter->getWhitelistedFiles()->shouldHaveCount(2);
    }

    /**
     * @covers \Doyo\Bridge\CodeCoverage\ContainerFactory
     */
    public function it_should_create_container()
    {
        $this->getContainer()->shouldBeAnInstanceOf(ContainerInterface::class);
    }

    public function it_should_create_processor()
    {
        $this->createProcessor(true)->shouldReturnAnInstanceOf(ProcessorInterface::class);
    }

    public function it_should_create_code_coverage()
    {
        $this->createCodeCoverage(true)->shouldReturnAnInstanceOf(CodeCoverage::class);
    }

    public function it_should_create_application()
    {
        $this->createApplication()->shouldReturnAnInstanceOf(Application::class);
    }
}
