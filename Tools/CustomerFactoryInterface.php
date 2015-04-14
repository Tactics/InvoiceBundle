<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface CustomerFactoryInterface
{
    /**
     * 
     * @param mixed $customer The application customer object or an Invoice
     */
    public function getCustomer($customer);
}

