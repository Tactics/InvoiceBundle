<?php
namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Tools\PdfGeneratorInterface;
use Tactics\InvoiceBundle\Model\Invoice;

class PdfGenerator implements PdfGeneratorInterface
{
    /**
     * Generates pdf for given $invoice
     * 
     * @param Invoice $invoice     
     * @return nothing yet
     * @throws Exception
     */
    public function generate(Invoice $invoice)
    {     
        throw new \Exception('Default Pdf Generator not implemented yet.', 404);
    }
}