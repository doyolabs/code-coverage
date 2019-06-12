<?php


namespace Doyo\Behat\CodeCoverage;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Extension implements ExtensionInterface
{
    public function process(ContainerBuilder $container)
    {
        // TODO: Implement process() method.
    }

    public function getConfigKey()
    {
        // TODO: Implement getConfigKey() method.
    }

    public function initialize(ExtensionManager $extensionManager)
    {
        // TODO: Implement initialize() method.
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        // TODO: Implement configure() method.
    }

    public function load(ContainerBuilder $container, array $config)
    {
        // TODO: Implement load() method.
    }
}
