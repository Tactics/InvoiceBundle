<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface JournalGeneratorInterface
{
    public function generate(Invoice $invoice);
}
