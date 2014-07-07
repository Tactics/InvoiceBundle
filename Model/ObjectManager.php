<?php

namespace Tactics\InvoiceBundle\Model;

abstract class ObjectManager implements ObjectManagerInterface
{
    protected $transformer;
    
    public function __construct(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;        
    }
}