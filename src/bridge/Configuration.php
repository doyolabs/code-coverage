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

namespace Doyo\Bridge\CodeCoverage;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @var callable
     */
    private $stringToArrayNormalizer;

    public function __construct()
    {
        $this->stringToArrayNormalizer  = function ($v) {
            return [
                'target' => $v,
            ];
        };
    }

    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $this->configure($treeBuilder->root('config'));

        return $treeBuilder;
    }

    public function configure(ArrayNodeDefinition $node)
    {
        $this->configureCoverageSection($node);
        $this->configureSessionSection($node);
        $this->configureReportSection($node);
        $this->configureFilterSection($node);

        return $node;
    }

    private function configureCoverageSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('imports')
                    ->scalarPrototype()->end()
                ->end()
                ->booleanNode('xdebug_patch')->defaultTrue()->end()
                ->booleanNode('debug')->defaultFalse()->end()
                ->arrayNode('coverage')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('processUncoveredFilesFromWhitelist')->defaultFalse()->end()
                        ->booleanNode('checkForUnintentionallyCoveredCode')->defaultFalse()->end()
                        ->booleanNode('forceCoversAnnotation')->defaultFalse()->end()
                        ->booleanNode('checkForMissingCoversAnnotation')->defaultFalse()->end()
                        ->booleanNode('checkForUnexecutedCoveredCode')->defaultFalse()->end()
                        ->booleanNode('addUncoveredFilesFromWhitelist')->defaultTrue()->end()
                        ->booleanNode('disableIgnoredLines')->defaultFalse()->end()
                        ->booleanNode('ignoreDeprecatedCode')->defaultFalse()->end()
                        ->arrayNode('unintentionallyCoveredSubclassesWhitelist')
                            ->scalarPrototype()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Configure remote section.
     *
     * @return ArrayNodeDefinition
     */
    private function configureSessionSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('sessions')
                    ->useAttributeAsKey('name', false)
                    ->arrayPrototype()
                        ->children()
                            ->enumNode('driver')
                                ->values(['local', 'remote'])
                                ->defaultValue('local')
                            ->end()
                            ->scalarNode('remote_url')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    private function configureReportSection(ArrayNodeDefinition $builder)
    {
        $builder
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('reports')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->append($this->addOptionsNode('clover'))
                        ->append($this->addOptionsNode('crap4j'))
                        ->append($this->addOptionsNode('html'))
                        ->append($this->addOptionsNode('php'))
                        ->arrayNode('text')
                            ->beforeNormalization()
                                ->ifString()->then($this->stringToArrayNormalizer)
                            ->end()
                            ->children()
                                ->scalarNode('target')->defaultValue('console')->end()
                            ->end()
                        ->end()
                        ->append($this->addOptionsNode('xml'))
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param string $name
     *
     * @return ArrayNodeDefinition
     */
    private function addOptionsNode($name)
    {
        $treeBuilder = new ArrayNodeDefinition($name);

        return $treeBuilder
            ->beforeNormalization()
                ->ifString()->then($this->stringToArrayNormalizer)
            ->end()
            ->scalarPrototype()->end();
    }

    private function configureFilterSection(ArrayNodeDefinition $builder)
    {
        $stringNormalizer = function ($v) {
            return ['directory' => $v];
        };

        $builder
            ->children()
                ->arrayNode('filter')
                    ->arrayPrototype()
                        ->beforeNormalization()
                            ->ifString()->then($stringNormalizer)
                        ->end()
                        ->children()
                            ->scalarNode('directory')->defaultNull()->end()
                            ->scalarNode('file')->defaultNull()->end()
                            ->scalarNode('suffix')->defaultValue('.php')->end()
                            ->scalarNode('prefix')->defaultValue('')->end()
                            ->arrayNode('exclude')
                                ->arrayPrototype()
                                    ->beforeNormalization()
                                        ->ifString()->then($stringNormalizer)
                                    ->end()
                                    ->children()
                                        ->scalarNode('directory')->defaultNull()->end()
                                        ->scalarNode('file')->defaultNull()->end()
                                        ->scalarNode('suffix')->defaultNull()->end()
                                        ->scalarNode('prefix')->defaultNull()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
