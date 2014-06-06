<?php

namespace Tactics\InvoiceBundle\Model;

use Tactics\InvoiceBundle\Model\Invoice;

interface InvoiceTransformerInterface
{
    public function toOrm(Invoice $invoice);

    public function fromOrm($invoice); // transformable interface of zoiets nog?
}
