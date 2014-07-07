<?php

namespace Tactics\InvoiceBundle\Model;

interface ObjectManagerInterface
{
    // methods for managing the invoice object
    public function create();
    
    public function save(TransformableInterface $domain_object);
    
    public function find($id);
    
    public function delete(TransformableInterface $domain_object);
}

