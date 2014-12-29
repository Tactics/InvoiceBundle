<?php

namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

interface PdfGeneratorInterface
{
    public function generate(Invoice $invoice);
}
