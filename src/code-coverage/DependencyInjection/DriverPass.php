<?php


namespace Doyo\Bridge\CodeCoverage\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DriverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->processDriver($container);
    }

    private function processDriver(ContainerBuilder $container)
    {
        $driverClass = $container->get('runtime')->getDriverClass();
        $container->getParameterBag()->set('coverage.driver.class', $driverClass);

        $r = new \ReflectionClass($driverClass);
        if($r->hasMethod('setFilter')){
            $definition = $container->getDefinition('coverage.filter');
            $definition->addMethodCall('setFilter',[new Reference('coverage.filter')]);
        }
    }
}
