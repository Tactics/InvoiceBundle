<?php

namespace Tactics\InvoiceBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Description of Sf2BridgeExtension
 *
 * @author Joris Hontelé <joris.hontele@tactics.be>
 */
class InvoiceExtension extends Extension
{
    private static $ormTransformerMap = array(
      'propel' => array(
        'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceTransformer',
        'vat' => 'Tactics\InvoiceBundle\Propel\VatTransformer'
       )
    );
    
    private static $ormManagerMap = array(
      'propel' => array(
        'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceManager',
        'vat' => 'Tactics\InvoiceBundle\Propel\VatManager'
      )
    );
    
    public function load(array $configs, ContainerBuilder $container)
    {   
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        // set the manager classes based on the chosen orm
        $container->setParameter('invoice_transformer.class', self::$ormTransformerMap[$config['orm']]['invoice']);
        $container->setParameter('invoice_manager.class', self::$ormManagerMap[$config['orm']]['invoice']);
        $container->setParameter('vat_transformer.class', self::$ormTransformerMap[$config['orm']]['vat']);
        $container->setParameter('vat_manager.class', self::$ormManagerMap[$config['orm']]['vat']);
    }
}
