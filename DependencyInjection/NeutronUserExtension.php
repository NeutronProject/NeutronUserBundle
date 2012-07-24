<?php

namespace Neutron\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Symfony\Component\Config\Definition\Processor;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeutronUserExtension extends Extension
{
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        
        
        $processor = new Processor();
        $configuration = new Configuration();
        
        $config = $processor->processConfiguration($configuration, $configs);
        
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        if (!empty($config['management'])) {
            $this->loadManagement($config['management'], $container, $loader);
        }
        
        if (!empty($config['group'])) {
            $this->loadGroup($config['group'], $container, $loader);
        }
        
        if (!empty($config['role'])) {
            $this->loadRole($config['role'], $container, $loader);
        }
        
        
        foreach (array('security', 'validator', 'mailer', 'resetting', 'profile') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

    }
    
    private function loadManagement(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('management.xml');
    
        $container->setAlias('neutron_user.management.form.handler', $config['form']['handler']);
        unset($config['form']['handler']);
    
        $this->remapParametersNamespaces($config, $container, array(
            'form' => 'neutron_user.management.form.%s',
            'notification' => 'neutron_user.management.notification.%s',
        ));
    }
    
    private function loadGroup(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $loader->load('group.xml');
        $container->setAlias('neutron_user.group_manager', $config['group_manager']);
        $container->setAlias('neutron_user.group.form.handler', $config['form']['handler']);
        $container->setAlias('neutron_user.controller.group', $config['group_controller']);
        unset($config['form']['handler']);

        
        $this->remapParametersNamespaces($config, $container, array(
            '' => array(
                'group_class' => 'neutron_user.model.group.class',
            ),
            'form' => 'neutron_user.group.form.%s',
        ));
    }
    
    private function loadRole(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    { 
        $loader->load('role.xml');
        $container->setAlias('neutron_user.role_manager', $config['role_manager']);
        $container->setAlias('neutron_user.role.form.handler', $config['form']['handler']);
        $container->setAlias('neutron_user.controller.role', $config['role_controller']);
        unset($config['form']['handler']);

        
        $this->remapParametersNamespaces($config, $container, array(
            '' => array(
                'role_class' => 'neutron_user.model.role.class',
            ),
            'form' => 'neutron_user.role.form.%s',
        ));
    }
    
    
    

    protected function remapParameters(array $config, ContainerBuilder $container, array $map)
    {
        foreach ($map as $name => $paramName) {
            if (array_key_exists($name, $config)) {
                $container->setParameter($paramName, $config[$name]);
            }
        }
    }
    
    protected function remapParametersNamespaces(array $config, ContainerBuilder $container, array $namespaces)
    {   
        foreach ($namespaces as $ns => $map) {
            if ($ns) {
                if (!array_key_exists($ns, $config)) {
                    continue;
                }
                $namespaceConfig = $config[$ns];
            } else {
                $namespaceConfig = $config;
            }
            if (is_array($map)) {
                $this->remapParameters($namespaceConfig, $container, $map);
            } else {
                foreach ($namespaceConfig as $name => $value) {
                    $container->setParameter(sprintf($map, $name), $value);
                }
            }
        }
    }
}
