<?php

namespace Harmony\Bundle\CoreBundle\Provider;

/**
 * Class ContainerBuilderOdm
 *
 * @package Harmony\Bundle\CoreBundle\Provider
 */
class ContainerBuilderOdm extends AbstractContainerBuilder
{

    /**
     * Initializes the database connection
     *
     * @return void
     */
    protected function initConnection(): void
    {
        // TODO: Implement initConnection() method.
    }

    /**
     * Adds the parameters from the database to the container's parameterBag
     *
     * @return void
     */
    protected function addDbParameters(): void
    {
        // TODO: Implement addDbParameters() method.
    }

    /**
     * Adds configs from the database to the current configs
     *
     * @return void
     */
    protected function addDbConfig(): void
    {
        // TODO: Implement addDbConfig() method.
    }

    /**
     * Closes the database connection
     *
     * @return void
     */
    protected function closeConnection(): void
    {
        // TODO: Implement closeConnection() method.
    }
}