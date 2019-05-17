<?php

namespace Harmony\Bundle\CoreBundle\Provider;

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
}