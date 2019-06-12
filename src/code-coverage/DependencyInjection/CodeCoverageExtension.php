<?php


namespace Doyo\Bridge\CodeCoverage\DependencyInjection;


use Doyo\Bridge\CodeCoverage\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CodeCoverageExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {

        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new XmlFileLoader($container, $locator);

        $configuration = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('reports', $configuration['reports']);
        $container->setParameter('config.filter', $configuration['filter']);

        $loader->load('code_coverage.xml');
        $loader->load('reports.xml');
    }

    public function getAlias()
    {
        return 'coverage';
    }


}
