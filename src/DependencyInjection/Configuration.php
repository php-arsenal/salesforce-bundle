<?php

namespace PhpArsenal\SalesforceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('salesforce');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('wsdl')
                    ->isRequired()
                ->end()
                ->scalarNode('username')
                    ->isRequired()
                ->end()
                ->scalarNode('password')
                    ->isRequired()
                ->end()
                ->scalarNode('token')
                    ->isRequired()
                ->end()
                ->scalarNode('logging')
                    ->defaultValue('%kernel.debug%')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
