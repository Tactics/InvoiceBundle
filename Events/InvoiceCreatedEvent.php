<?php

namespace Tactics\InvoiceBundle\Events;

use Tactics\InvoiceBundle\Model\Invoice;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of InvoiceCreatedEvent
 *
 * @author Joris
 */
class InvoiceCreatedEvent extends Event
{
    private $invoice;
    private $options = array();
    
    /**
     * Constructor
     * 
     * @param Invoice $invoice
     * @param float $amount
     */
    public function __construct(Invoice $invoice, $options = array()) 
    {
        $this->invoice = $invoice;
        $this->options = $options;
    }
    
    function getInvoice() {
      return $this->invoice;
    }

    function getOptions() {
      return $this->options;
    }
}
