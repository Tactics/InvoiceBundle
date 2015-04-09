<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Propel\ObjectManager;

abstract class Customer
{
    protected $customer;      // The customer object
    protected $customerClass; // Persoon of Organisatie
    
    protected $customerInfoManager;
    protected $customerInfo;
    
    /**
     * 
     * @param mixed $customer the application customer
     * @param int $schemeId The accounting scheme id
     * @param ObjectManager $customerInfoManager
     */
    public function __construct($customer, $schemeId, ObjectManager $customerInfoManager)
    {        
        $this->customer = $customer;
        $this->customerClass = get_class($customer);
        $this->customerInfoManager = $customerInfoManager;
        
        $this->generateCustomerInfo($customerInfoManager, $schemeId);
    }
    
    /**
     * 
     * @param ObjectManager $customerInfoManager
     * @param int $schemeId
     * @return array[name] = value
     */
    abstract function generateCustomerInfo(ObjectManager $customerInfoManager, $schemeId);
    
    /**
     * 
     * @return array
     */
    public function getCustomerInfo()
    {
        return $this->customerInfo;
    }
}

