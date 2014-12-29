<?php
namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Tools\JournalGeneratorInterface;
use Tactics\InvoiceBundle\Model\Invoice;

class JournalGenerator implements JournalGeneratorInterface
{
    /**
     * Generates journal number for given $invoice
     * 
     * @param Invoice $invoice
     * @return int
     */
    public function generate(Invoice $invoice)
    {
      return 1;
    }
}