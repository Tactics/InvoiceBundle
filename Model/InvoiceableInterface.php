<?php

namespace Tactics\InvoiceBundle\Model;

interface InvoiceableInterface
{
    public function getInvoiceItems();
    
    //public function setInvoiceId();
}
