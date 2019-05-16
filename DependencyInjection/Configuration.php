<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(HarmonyCoreExtension::ALIAS);

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('theme_default')->defaultNull()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}