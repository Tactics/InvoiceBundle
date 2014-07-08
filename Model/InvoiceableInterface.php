<?php

namespace Tactics\InvoiceBundle\Model;

interface InvoiceableInterface
{
    /**
     * @return Tactics\InvoiceBundle\Model\CustomerInterface
     */
    public function getCustomer();
    
   
    /**
     * @return array 
     */
    public function getInvoiceItems();
    
    //public function setInvoiceId();
}
