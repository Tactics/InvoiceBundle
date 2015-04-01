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
        'journal',
        'scheme_customer_info'
    );
    
    private $ormTransformerMap = array(
        'propel' => array(
            'default' => 'Tactics\InvoiceBundle\Propel\Transformer',
            'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceTransformer',
            'product_configuration' => 'Tactics\InvoiceBundle\Propel\ProductConfigurationTransformer',
            'scheme_customer_info' => 'Tactics\InvoiceBundle\Propel\SchemeCustomerInfoTransformer'
        )
    );
    
    private $ormManagerMap = array(
        'propel' => array(
            'default' => 'Tactics\InvoiceBundle\Propel\ObjectManager',
            'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceManager'
        )
    );
    
    private $accountingSoftwareMap = array(
      'ProAcc' => array(
        //'customer_converter' => 'Tactics\InvoiceBundle\Tools\ProAcc\CustomerConverter',
          'invoice_converter' => 'Tactics\InvoiceBundle\Tools\ProAcc\InvoiceConverter',
          'payment_importer' => 'Tactics\InvoiceBundle\Tools\ProAcc\PaymentImporter'
      ),
      'Agresso' => array(
          'customer_converter' => 'Tactics\InvoiceBundle\Tools\Agresso\CustomerConverter',
          'invoice_converter' => 'Tactics\InvoiceBundle\Tools\Agresso\InvoiceConverter',
          'payment_importer' => 'Tactics\InvoiceBundle\Tools\Agresso\PaymentImporter'
      )
    );
    
    public function load(array $configs, ContainerBuilder $container)
    {   
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $container->setParameter('invoice_number_generator.class', $config['number_generator']);
        $container->setParameter('invoice_journal_generator.class', $config['journal_generator']);
        $container->setParameter('pdf_generator.class', $config['pdf_generator']);
        $container->setParameter('invoice.accounting_software', $config['accounting_software']);
        $container->setParameter('customer_converter.class', $this->accountingSoftwareMap[$config['accounting_software']]['customer_converter']);
        $container->setParameter('invoice_converter.class', $this->accountingSoftwareMap[$config['accounting_software']]['invoice_converter']);
        $container->setParameter('payment_importer.class', $this->accountingSoftwareMap[$config['accounting_software']]['payment_importer']);
        
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
