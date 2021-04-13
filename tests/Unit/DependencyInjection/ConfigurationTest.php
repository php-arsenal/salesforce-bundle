<?php

namespace PhpArsenal\SalesforceBundle\Tests\Unit\DependencyInjection;

use PhpArsenal\SalesforceBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \PhpArsenal\SalesforceBundle\DependencyInjection\Configuration
 */
class ConfigurationTest extends TestCase
{
    /**
     * @covers ::getConfigTreeBuilder()
     */
    public function testConfiguration(): void
    {
        $inputOutput = [
            'wsdl' => '%kernel.project_dir%/Resources/wsdl/%env(SALESFORCE_WSDL)%',
            'username' => '%env(SALESFORCE_USERNAME)%',
            'password' => '%env(SALESFORCE_PASSWORD)%',
            'token' => '~',
        ];

        $configuration = new Configuration();

        $configNode = $configuration->getConfigTreeBuilder()->buildTree();
        $resultConfig = $configNode->finalize($configNode->normalize($inputOutput));

        $this->assertEquals(array_merge_recursive($inputOutput, [
            'logging' => '%kernel.debug%',
        ]), $resultConfig);
    }
}