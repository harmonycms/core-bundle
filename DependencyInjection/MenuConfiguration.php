<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\MenuTreeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class MenuConfiguration
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection
 */
class MenuConfiguration implements ConfigurationInterface
{

    /**
     * The menu name
     *
     * @var string
     */
    protected $rootName = false;

    /**
     * Set the menu root name
     *
     * @param string $rootName the menu root name
     *
     * @return MenuConfiguration
     */
    public function setMenuRootName($rootName): MenuConfiguration
    {
        $this->rootName = $rootName;

        return $this;
    }

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('harmony_menu', 'array', new MenuTreeBuilder());
        $rootNode = $treeBuilder->getRootNode();

         $rootNode
            ->children()
                ->arrayNode('childrenAttributes')
                    ->prototype('variable')
                    ->end()
                ->end()
                ->menuNode('tree')
                    ->menuNodeHierarchy()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}