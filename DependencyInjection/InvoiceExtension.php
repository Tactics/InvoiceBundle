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
      'invoice', 'vat'
    );
    
    private static $ormTransformerMap = array(
      'propel' => array(
        'invoice' => 'Tactics\InvoiceBundle\Propel\InvoiceTransformer',
        'vat' => 'Tactics\InvoiceBundle\Propel\Transformer'
       )
    );
    
    private static $ormManagerMap = array(
      'propel' => array(
        'invoice' => 'Tactics\InvoiceBundle\Propel\ObjectManager',
        'vat' => 'Tactics\InvoiceBundle\Propel\ObjectManager'
      )
    );
    
    public function load(array $configs, ContainerBuilder $container)
    {   
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        // set the dic parameters for each domain object
        foreach ($this->object_names as $object_name)
        {
            $className = ucfirst(Container::camelize($object_name));
            $container->setParameter("{$object_name}_class", "\Tactics\InvoiceBundle\Model\\{$className}");
            $container->setParameter("{$object_name}_transformer.class", self::$ormTransformerMap[$config['orm']][$object_name]);
            $container->setParameter("{$object_name}_manager.class", self::$ormManagerMap[$config['orm']][$object_name]);
        }       
    }
}
