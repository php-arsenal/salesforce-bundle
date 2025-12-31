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
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $processedConfiguration = $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');

        $xmlLoader = new Loader\XmlFileLoader($container, $fileLocator);
        $xmlLoader->load('soap_client.xml');
        $xmlLoader->load('rest_client.xml');

        $yamlLoader = new Loader\YamlFileLoader($container, $fileLocator);
        $yamlLoader->load('services.yaml');

        foreach ($processedConfiguration as $key => $value) {
            $container->setParameter('salesforce.soap_client.' . $key, $value);
        }

        if (true == $processedConfiguration['logging']) {
            $builder = $container->getDefinition('salesforce.soap_client.builder');
//            $builder->addMethodCall('withLog', array(new Reference('salesforce.logger')));
        }
    }


}
