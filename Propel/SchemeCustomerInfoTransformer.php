<?php

namespace Tactics\InvoiceBundle\Propel;

class SchemeCustomerInfoTransformer extends Transformer
{
    private $scheme_transformer;    
        
    /**
     * Constructor
     * 
     * @param string $class
     * @param Transformer $scheme_transformer
     */
    public function __construct($class, Transformer $scheme_transformer)
    {
        $this->scheme_transformer = $scheme_transformer;
        
        parent::__construct($class);
    }
    
    /**
     * Geeft PropelSchemeCustomerInfo terug op basis van $schemeCustomerInfo
     * 
     * @param Tactics\InvoiceBundle\Model\SchemeCustomerInfo $schemeCustomerInfo
     * @return \PropelSchemeCustomerInfo $propelInfo
     */
    public function toOrm($schemeCustomerInfo)
    {
        $propelInfo = parent::toOrm($schemeCustomerInfo);
        
        if ($customer = $schemeCustomerInfo->getCustomer())
        {
            $propelInfo->setCustomerClass(get_class($customer));
            $propelInfo->setCustomerId($customer->getId());
        }
        
        if ($schemeCustomerInfo->getAccountingScheme())
        {
            $accScheme = $schemeCustomerInfo->getAccountingScheme();            
            $propelAccScheme = $this->scheme_transformer->toOrm($accScheme);
            $propelInfo->setPropelAccountingScheme($propelAccScheme); 
        }            

        return $propelInfo;
    }
    
    /**
     * Geeft domain account terug op basis van $propelSchemeCustomerInfo
     * 
     * @param \PropelSchemeCustomerInfo $propelSchemeCustomerInfo
     * @return \Tactics\InvoiceBundle\Model\SchemeCustomerInfo
     */
    public function fromOrm($propelSchemeCustomerInfo)
    {
        if (!$propelSchemeCustomerInfo)
        {
            return null;
        }
        
        $info = parent::fromOrm($propelSchemeCustomerInfo);
        
        $customer = Helper::classAndIdToObject($propelSchemeCustomerInfo->toArray(), 'Customer');
        if ($customer)
        {
          $info->setCustomer($customer);
        } 
        
        if ($propelSchemeCustomerInfo->getPropelAccountingScheme())
        {
            $propelAccScheme = $propelSchemeCustomerInfo->getPropelAccountingScheme();
            $accScheme = $this->scheme_transformer->fromOrm($propelAccScheme);
            $info->setAccountingScheme($accScheme);
        }           
        
        return $info;
    }      
}

