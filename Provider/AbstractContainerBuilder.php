<?php

namespace Harmony\Bundle\CoreBundle\Provider;

use PDOException;
use ProxyManager\Configuration;
use Symfony\Bridge\ProxyManager\LazyProxy\Instantiator\RuntimeInstantiator;
use Symfony\Component\DependencyInjection\ContainerBuilder as BaseContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use function class_exists;

/**
 * Class AbstractContainerBuilder
 *
 * @package Harmony\Bundle\CoreBundle\Provider
 */
abstract class AbstractContainerBuilder extends BaseContainerBuilder
{

    /**
     * AbstractContainerBuilder constructor.
     *
     * @param ParameterBagInterface|null $parameterBag
     */
    public function __construct(ParameterBagInterface $parameterBag = null)
    {
        parent::__construct($parameterBag);

        if (class_exists(Configuration::class) && class_exists(RuntimeInstantiator::class)) {
            $this->setProxyInstantiator(new RuntimeInstantiator());
        }
    }

    /**
     * Compiles the container.
     * This method passes the container to compiler
     * passes whose job is to manipulate and optimize
     * the container.
     * The main compiler passes roughly do four things:
     *  * The extension configurations are merged;
     *  * Parameter values are resolved;
     *  * The parameter bag is frozen;
     *  * Extension loading is disabled.
     *
     * @param bool $resolveEnvPlaceholders Whether %env()% parameters should be resolved using the current
     *                                     env vars or be replaced by uniquely identifiable placeholders.
     *                                     Set to "true" when you want to use the current ContainerBuilder
     *                                     directly, keep to "false" when the container is dumped instead.
     */
    public function compile(bool $resolveEnvPlaceholders = false)
    {
        try {
            $this->initConnection();
            $this->addDbParameters();
            $this->addDbConfig();
            $this->closeConnection();
        }
        catch (PDOException $e) {
            parent::compile($resolveEnvPlaceholders);

            return;
        }
        parent::compile($resolveEnvPlaceholders);
    }

    /**
     * Initializes the database connection
     *
     * @return void
     */
    abstract protected function initConnection(): void;

    /**
     * Adds the parameters from the database to the container's parameterBag
     *
     * @return void
     */
    abstract protected function addDbParameters(): void;

    /**
     * Adds configs from the database to the current configs
     *
     * @return void
     */
    abstract protected function addDbConfig(): void;

    /**
     * Closes the database connection
     *
     * @return void
     */
    abstract protected function closeConnection(): void;
}