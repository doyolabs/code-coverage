<?php


namespace Doyo\Bridge\CodeCoverage\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ReportPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {

        $reports = $container->getParameter('reports');
        foreach($reports as $type => $config){
            $this->processConfig($container, $type, $config);
        }
    }

    private function processConfig(ContainerBuilder $container, $type, $config)
    {
        if(!isset($config['target'])){
            return;
        }

        $id = 'reports.'.$type;
        $class = $container->getParameter($id.'.class');

        $definition = new Definition($class);
        $definition->addArgument($config);
        $definition->addTag('coverage.reports');

        $container->setDefinition($id, $definition);
    }

}
