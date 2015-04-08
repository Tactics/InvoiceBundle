<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface CustomerFactoryInterface
{
    public function getCustomer(Invoice $invoice);
}

