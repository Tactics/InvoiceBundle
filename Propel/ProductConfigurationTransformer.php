<?php

namespace Tactics\InvoiceBundle\Propel;

class ProductConfigurationTransformer extends Transformer
{
    private $vat_transformer;
    private $account_transformer;
    
    private $account_names = array(
        'gl_account',
        'analytical_1_account',
        'analytical_2_account',
        'analytical_3_account',
        'analytical_4_account',
        'analytical_5_account'
    );
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $vat_transformer
     * @param Transformer $account_transformer
     */
    public function __construct($class, Transformer $vat_transformer, Transformer $account_transformer)
    {
        $this->vat_transformer = $vat_transformer;
        $this->account_transformer = $account_transformer;
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelProductConfiguration terug op basis van $product_configuration
     * 
     * @param Tactics\InvoiceBundle\Model\ProductConfiguration $product_configuration
     * @return \PropelProductConfiguration
     */
    public function toOrm($product_configuration)
    {
        $propelInvoice = parent::toOrm($product_configuration);
        
        $propelInvoice->setProductClass(get_class($product_configuration->getProduct()));
        $propelInvoice->setProductId($product_configuration->getProduct()->getId());
        
        $vat = $product_configuration->getVat();
        if ($vat)
        {
            $propelConfig->setVatCode($vat->getCode());
        }
        
        foreach ($this->account_names as $account_name)
        {            
            $ucfirstCamelizedAccountName = Helper::camelize($account_name, true);
            $getter = "get{$ucfirstCamelizedAccountName}";
            $account = $product_configuration->$getter();
            if ($account)
            {
                $setter = "setPropelAccountRelatedBy{$ucfirstCamelizedAccountName}Code";
                $propelConfig->$setter($this->account_transformer->toOrm($account));
            }
        }
        
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
        
        // set vat
        $productConfig->setVat($this->vat_transformer->fromOrm($propel_product_configuration->getPropelVat()));
        
        // set accounts
        foreach ($this->account_names as $account_name)
        {
            $ucfirstCamelizedAccountName = Helper::camelize($account_name, true);
            $propelGetter = "getPropelAccountRelatedBy{$ucfirstCamelizedAccountName}Code";
            $domainSetter = "set{$ucfirstCamelizedAccountName}";
            $account = $propel_product_configuration->$propelGetter();                        
            $productConfig->$domainSetter($this->account_transformer->fromOrm($account));
        }
        
        return $productConfig;
    }
    
    
}

