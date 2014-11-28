<?php
namespace Tactics\InvoiceBundle\Tools;

use Tactics\InvoiceBundle\Model\Invoice;

class JournalGenerator
{
    private static $config = array(
        'invoice' => array(
            'vat' => 1,
            'no_vat' => 3
        ),
        'credit_note' => array(
            'vat' => 2,
            'no_vat' => 4
        )
    );
            
    /**
     * Generates journal number for given $invoice
     * 
     * @param Invoice $invoice
     * @return string
     */
    public function generate(Invoice $invoice)
    {     
        $vat = bccomp($invoice->getVat(), 0, 2) === 1 ? 'vat' : 'no_vat';
        $type = bccomp($invoice->getTotal(), 0, 2) === -1 ? 'credit_note' : 'invoice';
        
        return self::$config[$type][$vat];
    }
}