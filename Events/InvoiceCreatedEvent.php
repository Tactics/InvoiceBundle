<?php

namespace Tactics\InvoiceBundle\Events;

use Symfony\Contracts\EventDispatcher\Event;
use Tactics\InvoiceBundle\Model\Invoice;

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

    /**
     * @return Invoice
     */
    function getInvoice()
    {
        return $this->invoice;
    }

    function getOptions()
    {
        return $this->options;
    }
}
