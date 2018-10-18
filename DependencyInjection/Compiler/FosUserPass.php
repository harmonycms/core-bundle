<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection\Compiler;

use FOS\UserBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FosUserPass
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection\Compiler
 */
class FosUserPass implements CompilerPassInterface
{

    /** @var array $doctrineDrivers */
    protected $doctrineDrivers
        = [
            'orm'     => [
                'registry' => 'doctrine',
                'tag'      => 'doctrine.event_subscriber',
            ],
            'mongodb' => [
                'registry' => 'doctrine_mongodb',
                'tag'      => 'doctrine_mongodb.odm.event_subscriber',
            ],
            'couchdb' => [
                'registry'       => 'doctrine_couchdb',
                'tag'            => 'doctrine_couchdb.event_subscriber',
                'listener_class' => 'FOS\UserBundle\Doctrine\CouchDB\UserListener',
            ]
        ];

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    public function process(ContainerBuilder $container)
    {
        // TODO: make it dynamic, don't know yet on what to be based!
        $driver = 'orm';

        // Configuration
        $config = (new Processor())->processConfiguration(new Configuration(),
            $container->getExtensionConfig('fos_user'));

        // Set alias and parameter
        $container->setAlias('fos_user.doctrine_registry', $this->doctrineDrivers[$driver]['registry']);
        $container->setParameter('fos_user.backend_type_' . $driver, true);

        $definition = $container->getDefinition('fos_user.object_manager');
        $definition->setFactory([new Reference('fos_user.doctrine_registry'), 'getManager']);

        // Listener
        if ($config['use_listener']) {
            $listenerDefinition = $container->getDefinition('fos_user.user_listener');
            $listenerDefinition->addTag($this->doctrineDrivers[$driver]['tag']);
            if (isset($this->doctrineDrivers[$driver]['listener_class'])) {
                $listenerDefinition->setClass($this->doctrineDrivers[$driver]['listener_class']);
            }
        }
    }
}