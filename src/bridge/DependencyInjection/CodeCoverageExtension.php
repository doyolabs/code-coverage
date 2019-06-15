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

namespace Doyo\Bridge\CodeCoverage\DependencyInjection;

use Doyo\Bridge\CodeCoverage\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CodeCoverageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader  = new XmlFileLoader($container, $locator);

        $configuration = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('config', $configuration);
        $container->setParameter('reports', $configuration['reports']);
        $container->setParameter('config.filter', $configuration['filter']);
        $container->setParameter('coverage.options', $configuration['coverage']);
        $container->setParameter('sessions', $configuration['sessions']);

        $loader->load('code_coverage.xml');
        $loader->load('reports.xml');
    }

    public function getAlias()
    {
        return 'coverage';
    }
}
