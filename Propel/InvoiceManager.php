<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Tactics\InvoiceBundle\Model\InvoiceableInterface;
use Tactics\InvoiceBundle\Model\Invoice;

class InvoiceManager extends ObjectManager
{
    private $number_generator;
    private $journal_generator;
    private $pdf_generator;
    
    public function __construct($class, Model\TransformerInterface $transformer, $number_generator, $journal_generator, $pdf_generator)
    {
        parent::__construct($class, $transformer);
        
        $this->number_generator = $number_generator;
        $this->journal_generator = $journal_generator;
        $this->pdf_generator = $pdf_generator;
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
        $invoice->setSchemeId(isset($options['scheme_id']) ? $options['scheme_id'] : null);
        
        if ($object)
        {
            foreach ($object->getInvoiceItems($options) as $item)
            {
                $invoice->addItem($item);
            }
            $invoice->setCustomer($object->getCustomer());
        }
        
        return $invoice;
    }

    /**
     * 
     * @param Invoice $domainObject
     * @return type
     */
    public function save($domainObject)
    {
        $domainObject->setJournalCode($this->journal_generator->generate($domainObject));
        $domainObject->setDate(time());
        $domainObject->setDateDue(strtotime('+30 days'));
        
        if (!$domainObject->getId())
        {
          return $this->saveNew($domainObject);
        }
        
        $ormObject = $this->transformer->toOrm($domainObject);
        $result = $ormObject->save();
        
        return $result;
    }
    
    /**
     * saves a new invoice:
     *  - generates invoice number and structured communication
     *  - sets the new id to the domainObject
     * 
     * @param Invoice $invoice
     * @return int 
     */
    private function saveNew(Invoice $invoice)
    {
        while (true)
        {
            try
            {
                $invoice->setNumber($this->generateNumber($invoice));
                $invoice->setStructuredCommunication($this->generateStructuredCommunication($invoice));

                $ormObject = $this->transformer->toOrm($invoice);
                $result = $ormObject->save();

                // setting the id
                foreach ($this->pk_php_name as $pkName)
                {
                    $pkSetter = 'set' . $pkName;
                    $pkGetter = 'get' . $pkName;
                    $invoice->$pkSetter($ormObject->$pkGetter());
                }

                return $result;
            }
            catch (\Exception $e)
            {
                $ormObject->setAlreadyInSave(false);
            }
        }
    }
    
    /**
     * Creates PDF
     * 
     * @param Invoice $invoice
     * @return type
     */
    public function createPdf(Invoice $invoice)
    {
        return $this->pdf_generator->generate($invoice);
    }
    
    /**
     * Generates invoice number
     * 
     * @param type $invoice
     * @return type
     */
    private function generateNumber($invoice)
    {
        // retrieve last invoice from same journal
        $lastInvoice = $this->searchOne(
            array(
                'journal_code' => $invoice->getJournalCode()
            ),
            'number', // sort by number
            false // descending
        );

        $lastNumber = isset($lastInvoice) ? $lastInvoice->getNumber() : null;
        $nextNumber = $this->number_generator->generate($invoice, $lastNumber);
        
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
