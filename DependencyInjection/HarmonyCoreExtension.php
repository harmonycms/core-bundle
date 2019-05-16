<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Doctrine\Bundle\MongoDBBundle\DependencyInjection\Compiler\DoctrineMongoDBMappingsPass;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry as MongoDBManagerRegistry;
use Doctrine\Common\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Exception;
use InvalidArgumentException;
use Rollerworks\Bundle\RouteAutowiringBundle\RouteImporter;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use function class_exists;
use function dirname;

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
     * @throws InvalidArgumentException When provided tag is not defined in this extension
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->processConfiguration(new Configuration(), $configs);

        $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
        $loader->load('services.yaml');

        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        // Alias service for `doctrine_mongodb` who is not previded by default by DoctrineMongodbBundle
        if (class_exists(DoctrineMongoDBMappingsPass::class) && isset($bundles['DoctrineMongoDBBundle'])) {
            $container->setAlias(PersistenceManagerRegistry::class, MongoDBManagerRegistry::class);
        }

        $routeImporter = new RouteImporter($container);
        $routeImporter->addObjectResource($this);
        $routeImporter->import('@HarmonyCoreBundle/Resources/config/routing.yaml', 'main');
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