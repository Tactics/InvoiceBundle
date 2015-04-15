<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Tools\Customer;
use Tactics\InvoiceBundle\Model\SchemeCustomerInfo;

class SchemeCustomerInfoManager extends ObjectManager
{
    /**
     * 
     * @param Customer $customer
     * @param type $schemeId
     * @param type $name
     * @param type $value
     * @return SchemeCustomerInfo
     */
    public function update(Customer $customer, $schemeId, $name, $value)
    {
        $info = $customer->getCustomerInfo($schemeId)[$name];
        if (!$value)
        {
            if ($info) $this->delete($info);
            return;
        }

        if (!$info)
        {       
            $info = parent::create();
            $info->setName($name);
            $info->setCustomer($customer->getCustomer());
            $info->setSchemeId($schemeId);
        }
        $info->setValue($value);
        $this->save($info);
        
        return $info;
    }
}
