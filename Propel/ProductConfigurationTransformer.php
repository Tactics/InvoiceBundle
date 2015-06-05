<?php

namespace Tactics\InvoiceBundle\Propel;

class ProductConfigurationTransformer extends Transformer
{
    /**
     * Geeft PropelProductConfiguration terug op basis van $product_configuration
     * 
     * @param Tactics\InvoiceBundle\Model\ProductConfiguration $product_configuration
     * @return \PropelProductConfiguration
     */
    public function toOrm($product_configuration)
    {
        $propelConfig = parent::toOrm($product_configuration);
        
        $propelConfig->setProductClass(get_class($product_configuration->getProduct()));
        $propelConfig->setProductId($product_configuration->getProduct()->getId());
        
        return $propelConfig;
    }
    
    /**
     * Geeft domain account terug op basis van $propel_product_configuration
     * 
     * @param \PropelProductConfiguration $propel_product_configuration
     * @return \Tactics\InvoiceBundle\Propel\ProductConfiguration
     */
    public function fromOrm($propel_product_configuration)
    {
        $productConfig = parent::fromOrm($propel_product_configuration);        
        $product = Helper::classAndIdToObject($propel_product_configuration->toArray(), 'Product');
        if ($productConfig)
        {
          $productConfig->setProduct($product);
        }
        
        return $productConfig;
    }
    
    
}

