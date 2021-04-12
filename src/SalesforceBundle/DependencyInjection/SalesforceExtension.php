<?php

namespace PhpArsenal\SalesforceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SalesforceExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('soap_client.xml');
        $loader->load('rest_client.xml');
        foreach ($config['soap_client'] as $key => $value) {
            $container->setParameter('arsenal.soap_client.' . $key, $value);
        }

        if (true == $config['soap_client']['logging']) {
            $builder = $container->getDefinition('arsenal.soap_client.builder');
//            $builder->addMethodCall('withLog', array(new Reference('arsenal_salesforce.logger')));
        }
    }


}
