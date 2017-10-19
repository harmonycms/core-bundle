<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class HarmonyCoreExtension
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection
 */
class HarmonyCoreExtension extends Extension
{

    /** HarmonyCMS alias name */
    const ALIAS = 'harmony';

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * Return HarmonyCMS alias name.
     *
     * @return string
     */
    public function getAlias(): string
    {
        return self::ALIAS;
    }
}