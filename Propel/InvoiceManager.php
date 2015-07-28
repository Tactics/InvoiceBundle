<?php

namespace Tactics\InvoiceBundle\Propel;

use Tactics\InvoiceBundle\Model;
use Tactics\InvoiceBundle\Propel\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tactics\InvoiceBundle\Model\InvoiceableInterface;
use Tactics\InvoiceBundle\Model\Invoice;
use Tactics\InvoiceBundle\Events\InvoiceEvents;
use Tactics\InvoiceBundle\Events\InvoiceCreatedEvent;

class InvoiceManager extends ObjectManager
{
    private $number_generator;
    private $journal_generator;
    private $pdf_generator;
    private $event_dispatcher;
    private $options_generator;
    
    public function __construct($class, Model\TransformerInterface $transformer, $number_generator, $journal_generator, $pdf_generator, EventDispatcherInterface $eventDispatcher, $options_generator)
    {
        parent::__construct($class, $transformer);
        
        $this->number_generator = $number_generator;
        $this->journal_generator = $journal_generator;
        $this->pdf_generator = $pdf_generator;
        $this->event_dispatcher = $eventDispatcher;
        $this->options_generator = $options_generator;
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
        /*@var $invoice Invoice*/
        $invoice = parent::create();
        $invoice->setSchemeId(isset($options['scheme_id']) ? $options['scheme_id'] : null);
        $invoice->setRef(isset($options['ref']) ? $options['ref'] : null);
        $invoice->setStructuredCommunication(isset($options['structured_communication']) ? $options['structured_communication'] : null);
        $invoice->setJournalCode(isset($options['journal_code']) ? $options['journal_code'] : null);
        
        if ($object)
        {
            foreach ($object->getInvoiceItems($options) as $item)
            {
                $invoice->addItem($item);
            }
            $invoice->setCustomer($object->getCustomer());
            $this->options_generator->generate($object, $invoice);
        }
        
        return $invoice;
    }

    /**
     * 
     * @param Invoice $domainObject
     * @return type
     */
    public function save($domainObject, $options = array())
    {
        if (!$domainObject->getId())
        {


            if(!$domainObject->getJournalCode()) $domainObject->setJournalCode($this->journal_generator->generate($domainObject));
            $domainObject->setDate(time());
            $domainObject->setDateDue(strtotime('+30 days'));
            $result =  $this->saveNew($domainObject);


            $event = new InvoiceCreatedEvent($domainObject, $options);
            $this->event_dispatcher->dispatch(InvoiceEvents::CREATED, $event);
          
            return $result;
        }
        
        $ormObject = $this->transformer->toOrm($domainObject);
        $result = $ormObject->save();
        
        return $result;
    }
    
    /**
     * Aanmaken van creditnote
     * 
     * @param Invoice $invoice
     */
    public function createCreditNote(Invoice $invoice)
    {
      /* @var $creditNote Invoice */
      $creditNote = $this->create(null, array('scheme_id' => $invoice->getSchemeId()));
      $creditNote->setCustomer($invoice->getCustomer());
      $creditNote->setRef($invoice->getRef());
      foreach ($invoice->getItems() as $item)
      {        
        if ($item->getType() === 'text') continue;
        $creditedItem = clone $item;
        $creditedItem->setId(null);
        $creditedItem->setGroupDescription(sprintf('CREDITNOTA VOOR FACTUUR %s VAN %s', $invoice->getNumber(), $invoice->getDate('d/m/Y')));
        $creditedItem->setUnitPrice(bcsub(0, $creditedItem->getUnitPrice(), 2));
        $creditNote->addItem($creditedItem);
      }
      
      return $creditNote;
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
     * saves a new invoice:
     *  - generates invoice number and structured communication
     *  - sets the new id to the domainObject
     * 
     * @param Invoice $invoice
     * @return int 
     */
    private function saveNew(Invoice $invoice)
    {
        $ormObject = $this->transformer->toOrm($invoice);
        while (true)
        {
            try
            {
                $invoice->setNumber($this->generateNumber($invoice));
                if (!$invoice->getStructuredCommunication())
                {
                    $invoice->setStructuredCommunication($this->generateStructuredCommunication($invoice));
                }

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
        return $this->number_generator->generateStructuredCommunication($invoice);
    }
}
