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

        $this->addManagementSection($rootNode);
        
        $this->addGroupSection($rootNode);
        
        $this->addRoleSection($rootNode);

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
    
    private function addRoleSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('role')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('role_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('role_manager')->defaultValue('neutron_user.role_manager.default')->end()
                        ->scalarNode('role_controller')->defaultValue('neutron_user.controller.role.default')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('neutron_user_role_form_type')->end()
                                ->scalarNode('handler')->defaultValue('neutron_user.role.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('neutron_user_role_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                   ->end()
                ->end()
            ->end();
    }
    
    private function addGroupSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('group')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->scalarNode('group_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('group_manager')->defaultValue('neutron_user.group_manager.default')->end()
                        ->scalarNode('group_controller')->defaultValue('neutron_user.controller.group.default')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('neutron_user_group_form_type')->end()
                                ->scalarNode('handler')->defaultValue('neutron_user.group.form.handler.default')->end()
                                ->scalarNode('name')->defaultValue('neutron_user_group_form')->cannotBeEmpty()->end()
                            ->end()
                        ->end()
                   ->end()
                ->end()
            ->end();
    }
        
}
