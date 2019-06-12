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

namespace Doyo\Bridge\CodeCoverage\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class CoveragePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $this->processDriver($container);
        $this->processFilter($container);
    }

    private function processDriver(ContainerBuilder $container)
    {
        $driverClass = $container->get('runtime')->getDriverClass();
        $container->getParameterBag()->set('coverage.driver.class', $driverClass);

        $r = new \ReflectionClass($driverClass);
        if ($r->hasMethod('setFilter')) {
            $definition = $container->getDefinition('coverage.filter');
            $definition->addMethodCall('setFilter', [new Reference('coverage.filter')]);
        }
    }

    private function processFilter(ContainerBuilder $container)
    {
        $config     = $container->getParameter('config.filter');
        $definition = $container->getDefinition('coverage.filter');

        foreach ($config as $options) {
            $options['basePath'] = '';
            $this->filterWhitelist($definition, $options, 'add');
            $exclude = $options['exclude'];
            foreach ($exclude as $item) {
                $item['basePath'] = '';
                $this->filterWhitelist($definition, $item, 'remove');
            }
        }
    }

    private function filterWhitelist(Definition $definition, $options, $method)
    {
        $basePath  = $options['basePath'];
        $suffix    = $options['suffix'] ?: '.php';
        $prefix    = $options['prefix'] ?: '';
        $type      = $options['directory'] ? 'directory' : 'file';
        $directory = $options['directory'];
        $file      = $options['file'];

        if (preg_match('/\/\*(\..+)/', $directory, $matches)) {
            $suffix    = $matches[1];
            $directory = str_replace($matches[0], '', $directory);
        }

        $methodSuffix = 'add' === $method ? 'ToWhitelist' : 'FromWhitelist';
        if ('directory' === $type) {
            $definition->addMethodCall($method.'Directory'.$methodSuffix, [$directory, $suffix, $prefix]);
        }

        if ('file' === $type) {
            $definition->addMethodCall($method.'File'.$methodSuffix, [$file]);
        }
    }
}
