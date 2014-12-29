<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface NumberGeneratorInterface
{
    public function generate(Invoice $invoice, $lastNumber);
}
