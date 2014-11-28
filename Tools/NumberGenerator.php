<?php
namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

class NumberGenerator
{
    /**
     * Generates invoice number for given $invoice, base on $last_number
     * 
     * @param Invoice $invoice
     * @param string $last_number
     * @return string
     */
    public function generateNumber(Invoice $invoice, $last_number)
    {     
        return (int) $last_number + 1;
    }
}