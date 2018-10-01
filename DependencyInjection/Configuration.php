<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\TreeBuilder;
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
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root(HarmonyCoreExtension::ALIAS);

        $rootNode
            ->children()
                ->scalarNode('site_name')
                    ->isRequired()
                    ->info('The name displayed as the title of the site (e.g. company name, project name).')
                ->end()
            ->end()
            ->ignoreExtraKeys(true)
        ;

        return $treeBuilder;
    }
}