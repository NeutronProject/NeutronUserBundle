<?php

namespace Neutron\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

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
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neutron_user');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $this->addManagementSection($rootNode);

        return $treeBuilder;
    }
    
    private function addManagementSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('management')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('notification')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->booleanNode('enabled')->defaultFalse()->end()
                                ->arrayNode('template')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('add')->defaultValue('NeutronUserBundle:Management\Mail:add-user-email.html.twig')->end()
                                        ->scalarNode('edit')->defaultValue('NeutronUserBundle:Management\Mail:edit-user-email.html.twig')->end()
                                        ->scalarNode('delete')->defaultValue('NeutronUserBundle:Management\Mail:delete-user-email.html.twig')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                               ->scalarNode('type')->defaultValue('neutron_user_management')->end()
                                ->scalarNode('handler')->defaultValue('neutron_user.management.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('neutron_user_management_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                   ->end()
                ->end()
            ->end();
    }
        
}
