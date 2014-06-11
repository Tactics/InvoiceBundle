<?php

namespace Tactics\InvoiceBundle\Model;

interface InvoiceManagerInterface
{
    public function create();
    
    public function save(Invoice $invoice);
    
    public function find($id);
    
    public function delete(Invoice $invoice);
}

