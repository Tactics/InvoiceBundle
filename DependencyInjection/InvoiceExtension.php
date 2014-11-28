<?php

namespace Tactics\InvoiceBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Container;
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
    private $object_names = array(
        'invoice',
        'invoice_item',
        'vat',
        'accounting_scheme',
        'account',
        'product_configuration',
        'journal'
    );
    
    private $ormTransformerMap = array(
        'propel' => array(
            'default' => 'Tactics\InvoiceBundle\Propel\Transformer',
            'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceTransformer',
            'invoice_item' => 'Tactics\InvoiceBundle\Propel\InvoiceItemTransformer',
            'account' => 'Tactics\InvoiceBundle\Propel\AccountTransformer',
            'product_configuration' => 'Tactics\InvoiceBundle\Propel\ProductConfigurationTransformer'
        )
    );
    
    private $ormManagerMap = array(
        'propel' => array(
            'default' => 'Tactics\InvoiceBundle\Propel\ObjectManager',
            'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceManager'
        )
    );
    
    public function load(array $configs, ContainerBuilder $container)
    {   
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $container->setParameter('invoice_number_generator.class', $config['number_generator']);
        
        // set the dic parameters for each domain object
        foreach ($this->object_names as $object_name)
        {
            $transformerClass = isset($this->ormTransformerMap[$config['orm']][$object_name])
              ? $this->ormTransformerMap[$config['orm']][$object_name]
              : $this->ormTransformerMap[$config['orm']]['default'];
            
            $managerClass = isset($this->ormManagerMap[$config['orm']][$object_name])
              ? $this->ormManagerMap[$config['orm']][$object_name]
              : $this->ormManagerMap[$config['orm']]['default'];
            
            $className = ucfirst(Container::camelize($object_name));
            $container->setParameter("{$object_name}_class", "\Tactics\InvoiceBundle\Model\\{$className}");
            $container->setParameter("{$object_name}_transformer.class", $transformerClass);
            $container->setParameter("{$object_name}_manager.class", $managerClass);
        }       
    }
}
