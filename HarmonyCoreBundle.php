<?php

namespace Harmony\Bundle\CoreBundle;

use Harmony\Bundle\CoreBundle\DependencyInjection\Compiler\FosUserPass;
use Harmony\Bundle\CoreBundle\DependencyInjection\Compiler\RouteAutowiringPass;
use Harmony\Bundle\CoreBundle\DependencyInjection\Compiler\SettingsProviderPass;
use Harmony\Bundle\CoreBundle\DependencyInjection\HarmonyCoreExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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

    /**
     * Builds the bundle.
     * It is only ever called once when the cache is empty.
     * This method can be overridden to register compilation passes,
     * other extensions, ...
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new FosUserPass())->addCompilerPass(new SettingsProviderPass());
        $container->addCompilerPass(new RouteAutowiringPass(), PassConfig::TYPE_OPTIMIZE);
    }
}