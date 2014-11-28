<?php

namespace Tactics\InvoiceBundle\Model;

interface InvoiceableInterface
{
    /**
     * @return array containing Tactics\InvoiceBundle\Model\InvoiceItem
     */
    public function getInvoiceItems();
    
    /**
     * @return Tactics\InvoiceBundle\Model\CustomerInterface
     */
    public function getCustomer();
    
    /**
     * 
     */
    //public function addInvoice($invoice);
}
