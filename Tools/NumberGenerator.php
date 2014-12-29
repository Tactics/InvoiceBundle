<?php
namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Tools\NumberGeneratorInterface;
use Tactics\InvoiceBundle\Model\Invoice;

class NumberGenerator implements NumberGeneratorInterface
{
    /**
     * Generates invoice number for given $invoice, base on $last_number
     * 
     * @param Invoice $invoice
     * @param string $last_number
     * @return int
     */
    public function generate(Invoice $invoice, $last_number)
    {     
        return (int) $last_number + 1;
    }
}