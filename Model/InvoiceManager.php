<?php

namespace Tactics\InvoiceBundle\Model;

abstract class InvoiceManager implements InvoiceManagerInterface
{
    protected $transformer;
    
    public function __construct(InvoiceTransformerInterface $transformer)
    {
        $this->transformer = $transformer;        
    }
}