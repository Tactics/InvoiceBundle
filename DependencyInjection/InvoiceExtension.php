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
      'propel' => 'Tactics\InvoiceBundle\Model\PropelInvoiceTransformer'
    );
    
    private static $ormManagerMap = array(
      'propel' => 'Tactics\InvoiceBundle\Model\PropelInvoiceManager'
    );
    
    public function load(array $configs, ContainerBuilder $container)
    {   
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        // set the invoice manager class based on the chosen orm
        $container->setParameter('invoice_transformer.class', self::$ormTransformerMap[$config['orm']]);
        $container->setParameter('invoice_manager.class', self::$ormManagerMap[$config['orm']]);
    }
}
