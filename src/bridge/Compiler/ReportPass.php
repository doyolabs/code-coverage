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

namespace Doyo\Bridge\CodeCoverage\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ReportPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $reports = $container->getParameter('reports');
        foreach ($reports as $type => $config) {
            $this->processConfig($container, $type, $config);
        }

        $coverage = $container->getDefinition('coverage');
        $coverage->addMethodCall('addSubscriber', [new Reference('report')]);
    }

    private function processConfig(ContainerBuilder $container, $type, $config)
    {
        if (!isset($config['target'])) {
            return;
        }

        if ('text' !== $type) {
            $config['target'] = getcwd().\DIRECTORY_SEPARATOR.$config['target'];
        }
        $report = $container->getDefinition('report');

        $id    = 'reports.'.$type;
        $class = $container->getParameter($id.'.class');

        $definition = new Definition($class);
        $definition->addArgument($config);
        $definition->setPublic(true);

        $container->setDefinition($id, $definition);

        $report->addMethodCall('addProcessor', [new Reference($id)]);
    }
}
