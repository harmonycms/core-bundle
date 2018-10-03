<?php

namespace Harmony\Bundle\CoreBundle;

use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class HarmonyCoreBundle
 *
 * @package Harmony\Bundle\CoreBundle
 */
class HarmonyCoreBundle extends Bundle
{

    /** Constants */
    const VERSION         = '1.0.0';
    const NAME            = 'HarmonyCMS';
    const SYMFONY_APP_DIR = 'app';
    const SYMFONY_WEB_DIR = 'web';

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