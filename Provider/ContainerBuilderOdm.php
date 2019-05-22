<?php

namespace Harmony\Bundle\CoreBundle\Provider;

use Doctrine\MongoDB\Connection;
use MongoCollection;
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
     */
    protected function addDbConfig(): void
    {
        if (false === $this->checkCollectionExist('ContainerConfig')) {
            return;
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
        foreach ($this->databaseConnection->selectDatabase($this->defaultDatabase)->listCollections() as $collection) {
            $arrayCollectionNames[] = $collection->getCollection()->getCollectionName();
        }

        return in_array($collectionName, $arrayCollectionNames);
    }
}