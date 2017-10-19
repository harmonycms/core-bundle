<?php

namespace Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder as SymfonyTreeBuilder;

/**
 * This is the entry class for building a config tree.
 *
 * @package Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder
 */
class TreeBuilder extends SymfonyTreeBuilder
{

    /**
     * Return protected root property from Symfony TreeBuilder class.
     *
     * @return ArrayNodeDefinition|NodeDefinition The root node (as an ArrayNodeDefinition when the type is 'array')
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Creates the root node.
     *
     * @param string      $name    The name of the root node
     * @param string      $type    The type of the root node
     * @param NodeBuilder $builder A custom node builder instance
     *
     * @return ArrayNodeDefinition|NodeDefinition The root node (as an ArrayNodeDefinition when the type is 'array')
     * @throws \RuntimeException When the node type is not supported
     */
    public function setRoot($name, $type = 'array', NodeBuilder $builder = null)
    {
        $builder = $builder ?: new NodeBuilder();

        return $this->root = $builder->node($name, $type)->setParent($this);
    }
}