<?php

namespace Tactics\InvoiceBundle\Model;

use Tactics\InvoiceBundle\Model\InvoiceTransformerInterface;
use Tactics\InvoiceBundle\Model\Invoice;

class PropelInvoiceTransformer implements InvoiceTransformerInterface
{
    public function toOrm(Invoice $invoice) {
        $propelInvoice = new \Invoice();
        $propelInvoice->fromArray($invoice->toArray());        
        
        return $propelInvoice;
    }

    public function fromOrm($propel_invoice) {
        $invoice = new Invoice();
        $invoice->fromArray($propel_invoice->toArray());        
        
        return $invoice;
    }
}
