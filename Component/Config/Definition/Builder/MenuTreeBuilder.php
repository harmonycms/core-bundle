<?php

namespace Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Class MenuTreeBuilder
 *
 * @package Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder
 */
class MenuTreeBuilder extends NodeBuilder
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->nodeMapping['menu'] = __NAMESPACE__ . '\\MenuNodeDefinition';
    }

    /**
     * Creates a child menu node
     *
     * @param  string $name The name of the node
     *
     * @return MenuNodeDefinition|NodeDefinition
     */
    public function menuNode($name)
    {
        return $this->node($name, 'menu');
    }
}