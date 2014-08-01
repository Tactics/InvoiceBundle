<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Model\InvoiceableInterface;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Tools\PdfCreator;

class InvoiceManager extends ObjectManager
{
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\InvoiceableInterface $object
     * @return Tactics\InvoiceBundle\Model\Invoice
     * 
     * @todo setNumber in transactie gieten? dagboek + code moet unique zijn => indien error opnieuw proberen
     */
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
            $invoice->setJournalCode($object->getJournalCode());
        }
        
        $invoice->setDate(time());
        $invoice->setDateDue(strtotime('+30 days'));
        
        $invoice->setNumber($this->generateNumber($invoice));
        $invoice->setStructuredCommunication($this->generateStructuredCommunication($invoice));
                
        return $invoice;
    }
    
    public function createPdf(Invoice $invoice)
    {
        $pdfCreator = new PdfCreator();
        
        return $pdfCreator->createPdf($invoice);
    }
    
    private function generateNumber($invoice)
    {
        $lastInvoice = $this->searchOne(
            array(
                'journal_code' => $invoice->getJournalCode()
            ),
            'number', // sort by number
            false // descending
        );
        
        return $lastInvoice ? $lastInvoice->getNumber() + 1 : 1;
    }
    
    /**
     * @param \Tactics\InvoiceBundle\Model\Invoice $invoice
     * @return string structured communication
     * @throws sfException
     */
    private function generateStructuredCommunication(Invoice $invoice)
    {
        $strCom = substr($invoice->getJournalCode(), 0, 1);  // first digit = journal code
        $strCom .= date('y'); // last 2 numbers of full year
        $strCom .= sprintf("%07u",  $invoice->getNumber()); // zero padded invoice number        

        if (strlen($strCom) != 10) {
            throw new \sfException('There was a problem generating the structured communication: ' . $strCom);
        }

        $digit97 = sprintf("%02u", (bcmod($strCom, 97)));

        // Geen 00 als digit97
        if ($digit97 == 0)
        {
            $digit97 = 97;
        }

        return $strCom . (string) $digit97;
    }
}
