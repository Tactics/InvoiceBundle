<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Propel\ObjectManager;

abstract class Customer
{
    protected $invoice;       // The invoice
    protected $customer;      // The customer object
    protected $customerClass; // Persoon of Organisatie
    
    protected $customerInfoManager;
    protected $customerInfo;
    
    /**
     * 
     * @param Invoice $invoice
     * @param ObjectManager $customerInfoManager
     * @throws \Exception
     */
    public function __construct(Invoice $invoice, ObjectManager $customerInfoManager)
    {
        $this->invoice = $invoice;
        $this->customer = $invoice->getCustomer();
        $this->customerClass = get_class($this->customer);
        if (!in_array($this->customerClass, array('Persoon', 'Organisatie')))
        {
            throw new \Exception('Customer needs to be of class \'Persoon\' or \'Organisatie\'');
        }
        
        $this->customerInfoManager = $customerInfoManager;
        $this->customerInfo = $this->setCustomerInfo($customerInfoManager);
    }
    
    /**
     * 
     * @param type $customerInfoManager
     * @return array[name] = value
     */
    abstract function setCustomerInfo($customerInfoManager);
    
    /**
     * 
     * @return array
     */
    public function getCustomerInfo()
    {
        return $this->customerInfo;
    }
}

