<?php

namespace Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition as SymfonyArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Class ArrayNodeDefinition
 *
 * @package Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder
 */
class ArrayNodeDefinition extends SymfonyArrayNodeDefinition
{

    /**
     * Get children node definitions.
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Appends a child node definition.
     *
     * @param NodeDefinition $node
     *
     * @return $this
     */
    public function appendChild(NodeDefinition $node): ArrayNodeDefinition
    {
        $this->children[$node->name] = $node;

        return $this;
    }
}