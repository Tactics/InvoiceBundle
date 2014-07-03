<?php

namespace Tactics\InvoiceBundle\Model;

interface InvoiceManagerInterface
{
    // methods for managing the invoice object
    public function create();
    
    public function save(Invoice $invoice);
    
    public function find($id);
    
    public function delete(Invoice $invoice);
    
    // methods for managing the VAT codes
    public function getAllVats();
}

