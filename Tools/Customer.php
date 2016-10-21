<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\SchemeCustomerInfo;
use Tactics\InvoiceBundle\Propel\ObjectManager;

abstract class Customer
{
    protected $customer;      // The customer object
    protected $customerClass; // The customer class
    
    protected $customerInfoManager;
    protected $customerInfo;
    
    /**
     *
     * @param mixed $customer the application customer
     * @param ObjectManager $customerInfoManager
     */
    public function __construct($customer, ObjectManager $customerInfoManager)
    {
        $this->customer = $customer;
        $this->customerClass = get_class($customer);
        $this->customerInfoManager = $customerInfoManager;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     *
     * @param int $schemeId The accounting scheme id
     * @return SchemeCustomerInfo[]
     */
    public function getCustomerInfo($schemeId)
    {
        if (!$this->customerInfo) {
            $storedInfo = $this->findStoredCustomerInfo($schemeId);
            $defaults = array_combine($this->getFields(), array_fill(0, count($this->getFields()), null));
            $this->customerInfo = array_intersect_key(array_merge($defaults, $storedInfo), $defaults);
        }
        
        return $this->customerInfo;
    }
    
    /**
     * @param int $schemeId
     * @param string $field
     */
    public function getCustomerInfoValue($schemeId, $field)
    {
        if (!in_array($field, $this->getFields())){
            throw new \Exception("$field is geen geldig veld.");
        }
        $customerInfo = $this->getCustomerInfo($schemeId);
        return isset($customerInfo[$field]) ? $customerInfo[$field]->get() : '';
    }
    
    /**
     *
     * @param int $schemeId The accounting scheme id
     * @return array[name] = value
     */
    private function findStoredCustomerInfo($schemeId)
    {
        $infos = $this->customerInfoManager->search(array(
            'scheme_id' => $schemeId,
            'customer_id' => $this->customer->getId(),
            'customer_class' => $this->customerClass
        ));
        
        return array_combine(array_map(function ($info) {
            return $info->getName();
        }, $infos), $infos);
    }
    
    /**
     * @return string[]
     */
    abstract function getFields();
}

