<?php

namespace Tactics\InvoiceBundle\Model;

abstract class Transformer implements TransformerInterface
{
    protected $class = '';
    
    public function __construct($class)
    {
        $this->class = $class;
    }
}
