<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection\Compiler;

use Harmony\Bundle\CoreBundle\Manager\SettingsManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class SettingsProviderPass
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection\Compiler
 */
class SettingsProviderPass implements CompilerPassInterface
{

    /** @var string $tag */
    private $tag;

    /**
     * SettingsProviderPass constructor.
     *
     * @param string $tag
     */
    public function __construct(string $tag = 'settings_manager.provider')
    {
        $this->tag = $tag;
    }

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $services = [];
        foreach ($container->findTaggedServiceIds($this->tag) as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['provider'])) {
                    throw new \LogicException($this->tag . ' tag must be set with provider name');
                }
                $services[$attribute['priority'] ?? 0][$attribute['provider']] = new Reference($id);
            }
        }

        if (count($services) > 0) {
            ksort($services);
            $services = array_merge(...$services);
        }

        $container->getDefinition(SettingsManager::class)->setArgument('$providers', $services);
    }
}