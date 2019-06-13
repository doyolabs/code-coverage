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

namespace Doyo\Bridge\CodeCoverage\Context;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doyo\Bridge\CodeCoverage\ContainerFactory;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Assert\Assert;

class ContainerContext implements Context
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @Given I have load container
     * @Given I have load container with:
     */
    public function iConfigureCodeCoverage(PyStringNode $node = null)
    {
        $config = [];
        if (null !== $node) {
            $config = Yaml::parse($node->getRaw());
        }

        $container = (new ContainerFactory($config, true))->getContainer();
        $container->set('console.input', new StringInput(''));
        $container->set('console.output', new StreamOutput(fopen('php://memory', '+w')));

        $this->container = $container;
    }

    /**
     * @Then service :name should loaded
     *
     * @param string $name
     */
    public function serviceShouldLoaded(string $name)
    {
        Assert::true(
            $this->container->has($name),
            'Service '.$name.' is not defined'
        );
        Assert::true(
            \is_object($this->container->get($name)),
            'Failed to create object for service '.$name
        );
    }

    /**
     * @Then service :name should not loaded
     *
     * @param string $name
     */
    public function serviceNotLoaded($name)
    {
        Assert::true(
            $this->container->has($name),
            'Service '.$name.' is not defined'
        );
        Assert::false(
            $this->container->get($name),
            'Service '.$name.' should not loaded'
        );
    }
}
