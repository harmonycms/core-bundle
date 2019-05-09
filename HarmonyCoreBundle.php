<?php

namespace Harmony\Bundle\CoreBundle;

use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class HarmonyCoreBundle
 *
 * @package Harmony\Bundle\CoreBundle
 */
class HarmonyCoreBundle extends Bundle
{

    /** Constants */
    const VERSION = '0.1';
    const NAME    = 'HarmonyCMS';

    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     * @throws \LogicException
     */
    public function getContainerExtension(): ExtensionInterface
    {
        return new HarmonyCoreExtension();
    }
}