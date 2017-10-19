<?php

namespace Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as SymfonyNodeBuilder;

/**
 * Class NodeBuilder
 *
 * @package Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder
 */
class NodeBuilder extends SymfonyNodeBuilder
{

    /**
     * NodeBuilder constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->nodeMapping['array'] = __NAMESPACE__ . '\\ArrayNodeDefinition';
    }
}