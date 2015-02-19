<?php

namespace Tactics\InvoiceBundle\Events;

use Tactics\InvoiceBundle\Model\Invoice;
use Symfony\Component\EventDispatcher\Event;

/**
 * Description of InvoicePaymentEvent
 *
 * @author Joris
 */
class InvoicePaymentEvent extends Event
{
    private $invoice;
    private $amount;
    
    /**
     * Constructor
     * 
     * @param Invoice $invoice
     * @param float $amount
     */
    public function __construct(Invoice $invoice, $amount) 
    {
        $this->invoice = $invoice;
        $this->amount = $amount;
    }
    
    /**
     * 
     * @return Tactics\InvoiceBundle\Model\Invoice;
     */
    function getInvoice() {
        return $this->invoice;
    }

    /**
     * 
     * @return float
     */
    function getAmount() {
        return $this->amount;
    }
}
