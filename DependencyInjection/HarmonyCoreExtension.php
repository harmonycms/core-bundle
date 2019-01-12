<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class HarmonyCoreExtension
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection
 */
class HarmonyCoreExtension extends Extension implements PrependExtensionInterface
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
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yaml');
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

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function prepend(ContainerBuilder $container)
    {
        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        // Yaml loader
        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));

        if (isset($bundles['DoctrineBundle'])) {
            $loader->load('doctrine.yaml');
        }

        if (isset($bundles['HelisSettingsManagerBundle'])) {
            $loader->load('settings_manager.yaml');
        }
    }
}