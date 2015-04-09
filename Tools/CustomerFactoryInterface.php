<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface CustomerFactoryInterface
{
    /**
     * 
     * @param mixed $customer The application customer object or an Invoice
     * @param int $schemeId
     */
    public function getCustomer($customer, $schemeId = null);
}

