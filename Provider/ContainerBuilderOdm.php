<?php

namespace Harmony\Bundle\CoreBundle\Provider;

use Doctrine\MongoDB\Connection;
use Harmony\Bundle\CoreBundle\Model\Config;
use Harmony\Bundle\CoreBundle\Model\Extension;
use MongoCollection;
use MongoId;
use function array_merge_recursive;
use function array_shift;
use function in_array;
use function ltrim;
use function rtrim;

/**
 * Class ContainerBuilderOdm
 *
 * @package Harmony\Bundle\CoreBundle\Provider
 */
class ContainerBuilderOdm extends AbstractContainerBuilder
{

    /** @var Connection $databaseConnection */
    protected $databaseConnection;

    /** @var string $defaultDatabase */
    protected $defaultDatabase;

    /**
     * Initializes the database connection
     *
     * @return void
     */
    protected function initConnection(): void
    {
        $configs = $this->getExtensionConfig('doctrine_mongodb');

        $params = [];
        foreach ($configs as $config) {
            $params = array_merge_recursive($params, $config);
        }

        if (isset($params['connections'])) {
            foreach ($params['connections'] as $name => $values) {
                if (isset($values['server'])) {
                    $values['server']                       = ltrim($values['server'], '%env(');
                    $values['server']                       = rtrim($values['server'], ')%');
                    $params['connections'][$name]['server'] = $this->getEnv($values['server']);
                }
            }
        }

        if (isset($params['default_database'])) {
            $params['default_database'] = ltrim($params['default_database'], '%env(');
            $params['default_database'] = rtrim($params['default_database'], ')%');
            $this->defaultDatabase      = $this->getEnv($params['default_database']);
        }

        $mainConnection = array_shift($params['connections']);

        $this->databaseConnection = new Connection($mainConnection['server'], $mainConnection['options']);
        $this->databaseConnection->connect();
    }

    /**
     * Adds the parameters from the database to the container's parameterBag
     *
     * @return void
     */
    protected function addDbParameters(): void
    {
        if (false === $this->checkCollectionExist('ContainerParameter')) {
            return;
        }

        $collection = $this->databaseConnection->selectCollection($this->defaultDatabase, 'ContainerParameter');
        foreach ($collection->find() as $parameter) {
            $this->setParameter($parameter['name'], $parameter['value']);
        }
    }

    /**
     * Adds configs from the database to the current configs
     *
     * @return void
     * @throws \MongoException
     */
    protected function addDbConfig(): void
    {
        if (false === $this->checkCollectionExist('ContainerConfig')) {
            return;
        }

        $currentExtension = null;
        $extensions       = [];
        $configs          = [];

        $cursor = $this->databaseConnection->selectCollection($this->defaultDatabase, 'ContainerConfig')->find();
        foreach ($cursor as $result) {

            $extension     = $this->databaseConnection->selectCollection($this->defaultDatabase, 'ContainerExtension')
                ->createQueryBuilder()
                ->limit(1)
                ->field('_id')
                ->equals(new MongoId($result['extension']))
                ->getQuery()
                ->execute()
                ->toArray();
            $extensionName = isset($extension[$result['extension']]) ? $extension[$result['extension']]['name'] : null;

            if ($currentExtension != $result['extension'] && null !== $extensionName) {
                // The current extension has changed. We have to create a new Extension
                $currentExtension = $result['extension'];
                $extension        = new Extension();
                $extension->setName($extensionName);
                $extensions[$currentExtension] = $extension;
            }

            // New Config object
            $config = new Config();
            $config->setName($result['name']);
            $config->setValue($result['value']);

            if (isset($result['parent'])) {
                // The current config has a parent. We set the parent and the child
                $parentConfig = $configs[$result['parent']];
                $parentConfig->addChildren($config);
                $config->setParent($parentConfig);
            } else {
                // The current config has no parent so we link it to the extension.
                // (We should always link the config to an extension even if it has a parent but it makes it easier to build the config tree that way)
                $config->setExtension($extensions[$currentExtension]);
                $extensions[$currentExtension]->addConfig($config);
            }

            // Store the new config in the configs array to keep it for further use if it has children
            $configs[(string)$result['_id']] = $config;
        }

        foreach ($extensions as $extension) {
            $values = [];

            // Loop through configs without parent to get their config trees
            foreach ($extension->getConfigs() as $config) {
                $values[$config->getName()] = $config->getConfigTree();
            }

            // Adds the new config loaded from the database to the config of the extension
            $this->loadFromExtension($extension->getName(), $values);
        }
    }

    /**
     * Closes the database connection
     *
     * @return void
     */
    protected function closeConnection(): void
    {
        if ($this->databaseConnection->isConnected()) {
            $this->databaseConnection->close();
        }
    }

    /**
     * Check if a given collection name exist in the database
     *
     * @param string $collectionName
     *
     * @return bool
     */
    protected function checkCollectionExist(string $collectionName): bool
    {
        $arrayCollectionNames = [];
        /** @var MongoCollection $collection */
        foreach ($this->databaseConnection->selectDatabase($this->defaultDatabase)
                     ->listCollections() as $collection) {
            $arrayCollectionNames[] = $collection->getCollection()
                ->getCollectionName();
        }

        return in_array($collectionName, $arrayCollectionNames);
    }
}