<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Model\InvoiceableInterface;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Tools\PdfCreator;

class InvoiceManager extends ObjectManager
{
    private $number_generator;
    private $journal_generator;
    
    public function __construct($class, Model\TransformerInterface $transformer, $number_generator, $journal_generator)
    {
        parent::__construct($class, $transformer);
        
        $this->number_generator = $number_generator;
        $this->journal_generator = $journal_generator;
    }
    
    /**
     * 
     * @param \Tactics\InvoiceBundle\Model\InvoiceableInterface $object
     * @return Tactics\InvoiceBundle\Model\Invoice
     * 
     * @todo setNumber in transactie gieten? dagboek + code moet unique zijn => indien error opnieuw proberen
     */
    public function create(InvoiceableInterface $object = null, $options = array())
    {
        $invoice = parent::create();
        
        if ($object)
        {
            foreach ($object->getInvoiceItems($options) as $item)
            {
                $invoice->addItem($item);
            }
            $invoice->setCustomer($object->getCustomer());
            $invoice->setJournalCode($this->journal_generator->generate($invoice));
        }
        
        $invoice->setDate(time());
        $invoice->setDateDue(strtotime('+30 days'));
        
        $invoice->setNumber($this->generateNumber($invoice));
        $invoice->setStructuredCommunication($this->generateStructuredCommunication($invoice));
                
        return $invoice;
    }
    
    /**
     * Creates PDF
     * 
     * @param Invoice $invoice
     * @return type
     */
    public function createPdf(Invoice $invoice)
    {
        $pdfCreator = new PdfCreator();
        
        return $pdfCreator->createPdf($invoice);
    }
    
    /**
     * Generates invoice number
     * 
     * @param type $invoice
     * @return type
     */
    private function generateNumber($invoice)
    {
        $lastInvoice = $this->searchOne(
            array(
                'journal_code' => $invoice->getJournalCode()
            ),
            'number', // sort by number
            false // descending
        );
        
        $lastNumber = isset($lastInvoice) ? $lastInvoice->getNumber() : null;
        $nextNumber = $this->number_generator->generateNumber($invoice, $lastNumber);
        
        return $nextNumber;
    }
    
    /**
     * Generates structured communication message
     * 
     * @param \Tactics\InvoiceBundle\Model\Invoice $invoice
     * @return string structured communication
     * @throws sfException
     */
    private function generateStructuredCommunication(Invoice $invoice)
    {
        $strCom = sprintf("%07s00%1u", $invoice->getNumber(), $invoice->getJournalCode());

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
