<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Model\InvoiceableInterface;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Tools\PdfCreator;

class InvoiceManager extends ObjectManager
{
    public function create(InvoiceableInterface $object = null)
    {
        $invoice = parent::create();
        
        if ($object)
        {
            foreach ($object->getInvoiceItems() as $item)
            {
                $invoice->addItem($item);
            }
            $invoice->setCustomer($object->getCustomer());
        }
        
        $invoice->setDate(time());
        
        return $invoice;
    }
    
    public function createPdf(Invoice $invoice)
    {
        $pdfCreator = new PdfCreator();
        
        return $pdfCreator->createPdf($invoice);
    }
}
