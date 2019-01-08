<?php

namespace Harmony\Bundle\CoreBundle\DependencyInjection;

use Harmony\Bundle\CoreBundle\Component\Config\Definition\Builder\TreeBuilder;
use Harmony\Bundle\CoreBundle\Manager\SettingsManagerInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class Configuration
 *
 * @package Harmony\Bundle\CoreBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(HarmonyCoreExtension::ALIAS);
        $rootNode    = $treeBuilder->getRoot();

        $scopes = [
            SettingsManagerInterface::SCOPE_ALL,
            SettingsManagerInterface::SCOPE_GLOBAL,
            SettingsManagerInterface::SCOPE_USER,
        ];

        $rootNode
            ->children()
                ->arrayNode('settings')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('scope')
                                ->defaultValue('all')
                                ->validate()
                                    ->ifNotInArray($scopes)
                                    ->thenInvalid('Invalid scope %s. Valid scopes are: '.implode(', ', array_map(function ($s) { return '"'.$s.'"'; }, $scopes)).'.')
                                ->end()
                            ->end()
                            ->scalarNode('type')->defaultValue(TextType::class)->end()
                            ->variableNode('options')
                                ->info('The options given to the form builder')
                                ->defaultValue(array())
                                ->validate()
                                    ->always(function ($v) {
                                        if (!is_array($v)) {
                                            throw new InvalidTypeException();
                                        }
                                        return $v;
                                    })
                                ->end()
                            ->end()
                            ->variableNode('constraints')
                                ->info('The constraints on this option. Example, use constraits found in Symfony\Component\Validator\Constraints')
                                ->defaultValue(array())
                                ->validate()
                                    ->always(function ($v) {
                                        if (!is_array($v)) {
                                            throw new InvalidTypeException();
                                        }
                                        return $v;
                                    })
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->ignoreExtraKeys(true)
        ;

        return $treeBuilder;
    }
}