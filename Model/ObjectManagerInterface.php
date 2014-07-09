<?php

namespace Tactics\InvoiceBundle\Model;

interface ObjectManagerInterface
{
    // methods for managing the invoice object
    public function create();
    
    public function save($domain_object);
    
    public function find($id);
    
    public function delete($domain_object);
    
    public function getDomainObject($orm_object);
}

